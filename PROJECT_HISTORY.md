# Studio AI - Lịch Sử & Nhật Ký Dự Án

Tài liệu này theo dõi lịch sử, nhật ký và các thay đổi phiên bản của dự án Studio AI.
**Quy tắc Versioning:** Các phiên bản trước khi deploy production sẽ có dạng `0.xx.xx`. Phiên bản Production đầu tiên sẽ là `1.0.0`.

## [0.1.0] - 2026-01-10

### Trạng Thái
- **Giai đoạn**: Phase 1: Foundation (Hoàn tất Setup & DB)
- **Mục tiêu Spec**: v1.3.0 (Studio AI Complete Specification)
- **Framework**: Laravel 12.x (Đã cài đặt khung cơ bản)

### Thay đổi
- Thiết lập hệ thống ghi nhật ký ban đầu.
- Xác định đặc tả dự án trong `studio-ai-complete-specification.md` (Phiên bản 1.3).
- Kiến trúc hệ thống và thiết kế cơ sở dữ liệu đã được định nghĩa trong spec.
- Đã xác minh cài đặt cơ bản của Laravel.

### Nhật ký
- [2026-01-10 15:24] Khởi tạo hệ thống. Đã nhận vai trò ghi log.
- [2026-01-10 15:26] Phân tích `studio-ai-complete-specification.md`. Xác định 3 module chính và 3 vai trò người dùng.
- [2026-01-10 15:27] Cập nhật ngôn ngữ nhật ký sang Tiếng Việt và áp dụng quy tắc versioning (Pre-production: 0.xx.xx).
- [2026-01-10 15:50] Khởi tạo Phase 1: Foundation. Đang cài đặt Laravel Breeze và Spatie Permission. Tạo file `TASK.md`.
- [2026-01-10 16:15] Hoàn tất Phase 1 Foundation:
    - Cài đặt & Cấu hình Laravel Breeze, Spatie Permission.
    - Tạo Models & Migrations cho toàn bộ hệ thống (Auth, Storage, Core, Processing).
    - Triển khai RolesAndPermissionsSeeder (Admin, Manager, User).
    - Verify Database Seeding thành công.

## [0.2.0] - 2026-01-10

### Trạng Thái
- **Giai đoạn**: Phase 2: User Tools (Storage Hub UI)
- **Tính năng mới**: Prompt Library, Image Library

### Thay đổi
- Triển khai **Storage Hub** với chuẩn UI/UX mới (Pro Max).
- Cập nhật quy trình phát triển: Bắt buộc ghi log vào `PROJECT_HISTORY.md`.

### Nhật ký
- [2026-01-10 16:30] Triển khai **Prompt Library UI**:
    - Backend: `PromptController` (CRUD), Route resource.
    - Frontend: Áp dụng **UI/UX Pro Max**. Bento Grid Layout cho Index. Form Create/Edit 2 cột hiện đại. Show view chi tiết.
- [2026-01-10 16:45] Triển khai **Image Library UI**:
    - Backend: `ImageController` (Upload/Delete), Storage Link public disk.
    - Frontend: Gallery Grid Layout (Masonry-style), Upload Form with Drag & Drop.
- [2026-01-10 16:48] Cập nhật `PROJECT_KNOWLEDGE.md`: Thêm quy tắc về Documentation Protocol.
- [2026-01-10 17:00] Triển khai **Virtual Model Library UI**:
    - Backend: `ModelController` với phân quyền (Admin Create/Delete, User Read-only).
    - Frontend: Gallery Grid hiển thị Model Presets. Giao diện Add Model cho Admin.
- [2026-01-10 17:15] Triển khai **Auto Prompt Wizard**:
    - Backend: `PromptOptionsSeeder` (Dữ liệu mẫu cho thông số 5 bước), `WizardController` (Phục vụ UI).
    - Frontend: **Alpine.js Wizard UI** (5 Steps, Progress Bar, Selection Logic).
    - Logic: Tự động ghép nối (concatenation) các thông số đã chọn thành Prompt hoàn chỉnh. Chức năng Copy & Save to Library.
- [2026-01-10 17:30] **UI/UX Pro Max Redesign (SaaS Edition)**:
    - **Layout Architecture**: Chuyển đổi từ Top Navigation sang **Sidebar Navigation** (Left-side, Fixed w-64).
    - **Sidebar Enhancements**: 
        - Tích hợp tính năng **Collapse/Expand**.
        - Di chuyển nút điều khiển xuống **Bottom Actions** (gần Settings) để tối ưu trải nghiệm người dùng 1 tay (Fitts's Law).
    - **Dashboard**: Thiết kế lại toàn bộ giao diện Dashboard theo phong cách Bento Grid.
        - Stats Cards: Hiển thị thống kê Prompts, Images, Storage.
        - Quick Actions: Card lớn với Icon minh họa 3D/SVG cho Auto Wizard & Batch Processor.
        - System Status: Widget theo dõi trạng thái các AI Services.
    - **Visuals**: Áp dụng chuẩn đổ bóng (shadow-sm/md), bo góc (rounded-2xl), và typography (Figtree) hiện đại.
- [2026-01-10 18:00] **User Management (Admin Module)**:
    - **Database**: Thêm cột `is_active` vào bảng `users` (Migration `add_is_active_to_users_table`).
    - **Back-office UI**:
        - List Users: Bảng quản lý User với Avatar, Role, Status.
        - Create/Edit: Form thêm/sửa thông tin User, gán Role (Admin, Manager, User).
        - Lock/Unlock: Tính năng khóa tài khoản nhanh.
    - **Integration**: Menu "User Management" chỉ hiển thị với Role Admin trên Sidebar.
- [2026-01-10 18:30] **Route & Sidebar Restructure (Spec v1.3 Section 6 & 7)**:
    - **Routes (`routes/web.php`)**: Tái cấu trúc thành 4 groups:
        - `features.*`: Batch Processor, Beautifier, Staging, Virtual Model, Auto Prompt.
        - `storage.*`: Products, Prompts, Images, Models.
        - `history.*`: Processing History.
        - `admin.*`: Users, Settings, Wizard Options, Model Presets (Middleware `role:Admin`).
    - **Controllers**: Tạo 9 controllers mới (Features/4, Storage/1, Admin/3, History/1).
    - **Sidebar (`sidebar.blade.php`)**: Cập nhật theo Spec v1.3 Section 7.1:
        - Nhóm: Features (5 items), Storage (4 items), History (1 item), Admin (4 items - Admin only).
        - Color-coded dividers for each group.
    - **Placeholder Views**: Tạo 12 view stubs cho tất cả routes mới.

## [0.3.0] - 2026-01-13 (Current)

### Trạng Thái
- **Giai đoạn**: Refactoring
- **Thay đổi lớn**: Loại bỏ tính năng thừa, hợp nhất Models, chuẩn hóa UI tiếng Anh.

### Thay đổi
- **Loại bỏ (Deprecated)**:
    - Tính năng "My Product" (Code & UI).
    - Tính năng "Auto Prompt Wizard" (Code & UI).
- **Hợp nhất (Merge)**:
    - "Model Library" và "Model Presets" thành một tính năng duy nhất "Model Presets".
    - User và Admin dùng chung Controller `ModelPresetController` (User read-only).
- **Giao diện (UI/UX)**:
    - Sidebar: Đổi tên "My Prompts" -> "Prompts", "My Images" -> "Images Library".
    - Ngôn ngữ: Chuẩn hóa 100% tiếng Anh cho Sidebar và các Label chính.
- **Tài liệu**: Cập nhật `PROJECT_KNOWLEDGE.md` với quy tắc ngôn ngữ.

### Nhật ký
- [2026-01-13 11:00] Thực hiện Refactoring theo yêu cầu:
    - Xóa `ProductController`, `WizardController` và các View liên quan.
    - Xóa `ModelController` (bản cũ).
    - Expose `ModelPresetController@index` cho Public User tại route `storage/model-presets`.
    - Cập nhật Sidebar: Xóa link cũ, thêm link mới, rename label.

## [0.3.1] - 2026-01-13

### Trạng Thái
- **Mục tiêu**: Hoàn thiện Prompt Creation Workflow.
- **Tính năng mới**: 3-pane Layout (Image/Wizard/Manual), Gemini Integration (Text & Vision), Settings System.

### Thay đổi
- **Backend**:
    - `GeneratePromptUseCase`: Logic gọi Gemini AI.
    - `PromptController`: API `generate` và Controller cho view mới.
    - `Setting`: Model & Migration lưu cấu hình hệ thống (Gemini API Key).
    - `GeminiClient`: Cập nhật lấy API Key từ DB.
- **Frontend**:
    - `create.blade.php`: Viết lại hoàn toàn với Alpine.js (3 tabs, AJAX, Preview).
    - `index.blade.php`: Thêm thanh Search & Filter.

### Nhật ký
- [2026-01-13 12:00] triển khai **Prompt Creation Workflow**:
    - Tạo `GeneratePromptUseCase` xử lý 3 luồng: Image Analysis, Wizard Generation, Manual Optimization.
    - Setup `settings` table để admin tự quản lý Gemini API Key.
    - Update UI Create Prompt theo thiết kế 3-pane hiện đại.
    - Cập nhật tài liệu `BLUEPRINT.md` và `SUMMARY.md`.

## [0.3.2] - 2026-01-13

### Trạng Thái
- **Mục tiêu**: Nâng cấp chất lượng Prompt Generation (AI Engineering).
- **Tính năng mới**: Prompt Structure v2, Auto Environment Detection, Generic Product Naming.

### Thay đổi
- **Prompt Engineering**:
    - **Separated Output**: Tách biệt kết quả `Image Analysis` (JSON) và `Generated Prompt`.
    - **Environment Detection**: Gemini tự động phân tích và thêm mô tả chi tiết về surface, background, props, lighting.
    - **Model vs Product Mode**: Tự động phát hiện ảnh có người mẫu hay không để điều chỉnh cấu trúc prompt.
    - **Reusability**: Sử dụng tên sản phẩm chung (generic product type) thay vì mô tả màu sắc cụ thể, giúp prompt có thể tái sử dụng.
- **UI/UX**:
    - Hiển thị phần "Image Analysis" tách biệt trong bảng kết quả.
    - Fix lỗi hiển thị `[object Object]` cho Analysis.

## [0.3.3] - 2026-01-13

### Trạng Thái
- **Mục tiêu**: Hoàn thiện Prompt Management & Image Integration.
- **Tính năng mới**: Advanced Search/Filter, CRUD Actions, Image Gallery Integration.

### Thay đổi
- **Prompt Management**:
    - **Search & Filter**: Tìm kiếm theo tên/prompt, lọc theo Category, lọc theo Favorite.
    - **Sorting**: Sắp xếp theo Mới nhất/Cũ nhất/A-Z/Z-A.
    - **Actions**:
        - **Duplicate**: Nhân bản prompt.
        - **Copy**: Sao chép nội dung prompt vào clipboard.
        - **Delete**: Xóa prompt.
        - **Edit**: Sửa prompt (sử dụng lại giao diện Create với dữ liệu cũ).
- **Image Integration**:
    - **Prompt Images**: Lưu hình ảnh tham chiếu của prompt vào Database.
    - **Image Library Sync**: Tự động lưu ảnh upload từ Prompt Creation vào `Image Library` với tag tương ứng.
    - **Gallery View**: Hiển thị ảnh thumbnails trong danh sách Prompt.
- **UI/UX**:
    - **Toast Notifications**: Thông báo trạng thái (Copy success, Saved, etc.).
    - **Layout**: Grid hiển thị Prompt kèm hình ảnh hiện đại.

### Nhật ký
- [2026-01-13 14:00] Nâng cấp **Prompt Management**:
    - Cập nhật `PromptController` logic search, filter, sort.
    - Thêm route `duplicate` và logic xử lý.
    - Update `index.blade.php` với bộ lọc và layout mới.
- [2026-01-13 15:00] Tích hợp **Image Library**:
    - Migration: Thêm `image_path` vào bảng `saved_prompts`.
    - Logic: Lưu ảnh vào `ImageLibrary` model khi lưu prompt.
    - Update form `Create` hỗ trợ Edit Mode (PUT method).
- [2026-01-13 17:00] **Refactor Image Storage System**:
    - Centralize Storage: Chuyển toàn bộ ảnh upload về `storage/app/public/prompts/`.
    - Symlink Fix: `php artisan storage:link` để fix lỗi 404 image display.
    - Image Library: Chuyển sang chế độ **Display Only** (Bỏ nút Upload). Ảnh được sync tự động từ Prompt Creation.

## [0.3.4] - 2026-01-13

### Trạng Thái
- **Mục tiêu**: Tinh chỉnh UI/UX và Logic quản lý.
- **Tính năng mới**: Prompt Method Tracking, Advanced Image Viewer.

### Thay đổi
- **Prompt Library**:
    - **Creation Method**: Thêm trường `method` (manual, image, wizard) để theo dõi nguồn gốc prompt.
    - **UI**: Hiển thị Badge màu (Tag) theo method trên Card. Thêm bộ lọc Method Dropdown.
    - **Pagination**: Cấu hình 12 items/page.
- **Image Library**:
    - **Viewer Integration**: Tích hợp thư viện **Viewer.js** cho trải nghiệm xem ảnh full-screen (Zoom, Rotate, Flip).
    - **UI**: Thay nút "View Full" mở tab mới bằng Popup Viewer hiện đại.
    - **Pagination**: Nâng giới hạn lên 20 items/page.
- **Fixes**:
    - Sửa lỗi 404 image đường dẫn do xung đột route `storage/prompts` và folder `storage/prompts`. Renamed folder thành `prompt-images`.

### Nhật ký
- [2026-01-13 18:00] **UI Enhancements**:
    - Migration: Thêm column `method` vào bảng `saved_prompts`.
    - Update Logic: `PromptController` lưu method từ tab active của Create Form.
    - Frontend:
        - `create.blade.php`: Bind active tab to hidden input.
        - `index.blade.php` (Prompts): Add Method Filter & Tags.
        - `index.blade.php` (Images): Integrate Viewer.js CDN & Script.
    - Config: Set pagination 12 (Prompts) & 20 (Images).
