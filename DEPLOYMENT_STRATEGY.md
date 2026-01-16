# Chiến lược Triển khai: Vercel cho Testing & Tích hợp Fal.ai

## 🎯 Mục tiêu
Triển khai Studio AI lên Vercel để test online và giải quyết vấn đề **Public URL** cho hình ảnh (để gửi cho Fal.ai thay vì chuỗi Base64), với quy trình cập nhật tự động.

## 🏗️ Tổng quan Kiến trúc

Vercel là nền tảng "Serverless", nghĩa là nó có 2 giới hạn chính với Laravel:
1.  **Không có Database vĩnh viễn**: Không thể cài MySQL trực tiếp trên Vercel.
2.  **Không lưu file vĩnh viễn**: Thư mục `storage/app/public` sẽ bị xóa sạch sau mỗi request. Bạn không thể lưu ảnh upload của user tại đây.

Để giải quyết vấn đề này và tích hợp Fal.ai mượt mà, chúng ta cần **"Serverless Stack"** (Bộ công cụ miễn phí):

| Thành phần | Dịch vụ Khuyên dùng (Gói Free) | Vai trò |
| :--- | :--- | :--- |
| **Hosting** | **Vercel** | Chạy mã nguồn PHP & Giao diện (Hobby Plan). |
| **Database** | **TiDB Cloud** (Khuyên dùng) hoặc **Aiven** | MySQL Compatible. TiDB Free Tier rất hào phóng (5GB/tháng). |
| **Lưu trữ (Storage)** | **Cloudflare R2** (Khuyên dùng) | Lưu hình ảnh & cung cấp **Public URL** cho Fal.ai. |
| **Đồng bộ** | **GitHub** | Kết nối Code từ máy local -> Vercel (Tự động Deploy). |

---

## 💡 Giải pháp Lưu trữ Miễn phí: Cloudflare R2
Vercel không lưu file, nên bạn cần một dịch vụ lưu trữ ngoài.
**Cloudflare R2** là lựa chọn tốt nhất cho Testing vì:
*   **Miễn phí 10GB** dung lượng lưu trữ (Thoải mái cho testing).
*   **Không tính phí băng thông** (Egress fees) - Khác với AWS S3 (tính tiền khi tải file).
*   **Tương thích S3**: Dùng driver S3 có sẵn của Laravel, chỉ cần đổi cấu hình.

---

## 🛠️ Hướng dẫn Triển khai Chi tiết

### Giai đoạn 1: Chuẩn bị tại Local
1.  **Cài đặt S3 Driver**: Laravel cần cái này để nói chuyện với Cloudflare R2.
    ```bash
    composer require league/flysystem-aws-s3-v3
    ```
2.  **Tạo file `vercel.json`**: Tạo file này ở thư mục gốc dự án để cấu hình Vercel.
    ```json
    {
        "version": 2,
        "framework": null,
        "functions": {
            "api/index.php": { "runtime": "vercel-php@0.7.0" }
        },
        "routes": [
            {
                "src": "/(.*)",
                "dest": "/api/index.php"
            }
        ],
        "env": {
            "APP_ENV": "production",
            "APP_DEBUG": "true",
            "APP_URL": "https://${VERCEL_URL}",
            "APP_CONFIG_CACHE": "/tmp/config.php",
            "APP_EVENTS_CACHE": "/tmp/events.php",
            "APP_PACKAGES_CACHE": "/tmp/packages.php",
            "APP_ROUTES_CACHE": "/tmp/routes.php",
            "APP_SERVICES_CACHE": "/tmp/services.php",
            "VIEW_COMPILED_PATH": "/tmp",
            "CACHE_DRIVER": "array",
            "LOG_CHANNEL": "stderr",
            "SESSION_DRIVER": "cookie"
        }
    }
    ```
3.  **Tạo file `api/index.php`**: Điểm đầu vào cho Vercel.
    ```php
    <?php
    require __DIR__ . '/../public/index.php';
    ```

### Giai đoạn 2: Thiết lập Hạ tầng (Infrastructure)
1.  **GitHub**: Đẩy code hiện tại lên một GitHub Repository (Private/Public tùy bạn).
2.  **Database (Chọn 1)**:
    *   **TiDB Cloud (Khuyên dùng)**: Tạo tài khoản -> Create Cluster (Serverless) -> Lấy thông tin kết nối (TiDB tương thích hoàn toàn với MySQL).
    *   **Aiven**: Tạo tài khoản Free -> Create MySQL Service -> Lấy thông tin `Host`, `User`, `Password`, `Database`.
    *   **Neon**: Tạo tài khoản Free -> Create Postgres -> Lấy connection string.
3.  **Storage (Cloudflare R2)**:
    *   Đăng ký Cloudflare -> Vào mục **R2**.
    *   Tạo Bucket (ví dụ: `studio-ai-test`).
    *   Vào "Manage R2 API Tokens" -> Tạo Token -> Lấy `Access Key ID`, `Secret Access Key`, và `Endpoint`.
    *   **Quan trọng**: Bật "Public Access" cho Bucket hoặc cài đặt Custom Domain để có đường dẫn ảnh công khai.

### Giai đoạn 3: Kết nối & Deploy
1.  **Vào Vercel Dashboard** -> "Add New Project" -> Import GitHub Repo của bạn.
2.  **Cấu hình Environment Variables**: Nhập các key sau vào phần Settings của Vercel:
    *   `DB_CONNECTION`: `mysql` (hoặc `pgsql`)
    *   `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (Lấy từ bước 2)
    *   `FILESYSTEM_DISK`: `s3` (Bắt buộc để dùng R2)
    *   `AWS_ACCESS_KEY_ID`: (Key ID của R2)
    *   `AWS_SECRET_ACCESS_KEY`: (Secret Key của R2)
    *   `AWS_DEFAULT_REGION`: `auto`
    *   `AWS_BUCKET`: `studio-ai-test`
    *   `AWS_ENDPOINT`: (Endpoint của R2, ví dụ: `https://<accountid>.r2.cloudflarestorage.com`)
    *   `AWS_USE_PATH_STYLE_ENDPOINT`: `false`
3.  **Deploy**: Bấm nút "Deploy". Vercel sẽ build và chạy web của bạn.

### Giai đoạn 4: Quy trình Làm việc & Update tự động
Đây là quy trình để bạn fix lỗi và tự động cập nhật lên Vercel:

1.  **Code tại máy**: Sửa lỗi, thêm tính năng trên máy tính của bạn.
2.  **Đẩy code lên GitHub**:
    ```bash
    git add .
    git commit -m "Fix lỗi upload ảnh"
    git push origin main
    ```
3.  **Xong**: Vercel sẽ tự động phát hiện code mới trên GitHub -> Tự động kéo về -> Tự động Build -> Tự động Deploy phiên bản mới trong 1-2 phút.

## 💡 Tổng kết
Để test online miễn phí và giải quyết vụ ảnh cho Fal.ai:
1.  Dùng **Vercel** để chạy web.
2.  Dùng **Cloudflare R2 (Free 10GB)** để lưu ảnh.
3.  Dùng **TiDB Cloud Serverless (Free 5GB)** để lưu database (MySQL Compatible).
4.  Dùng **GitHub** để tự động cập nhật code mỗi khi push.
