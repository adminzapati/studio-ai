# Studio AI - Core Project Knowledge (Kiến Thức Cốt Lõi)

> [!IMPORTANT]
> **SỐNG CÒN**: Tài liệu này chứa các kiến thức nền tảng, các quyết định kỹ thuật quan trọng và các quy tắc "bất di bất dịch" của dự án.
> **CẬP NHẬT**: Bắt buộc cập nhật file này khi có thay đổi về kiến trúc, luồng dữ liệu chính, hoặc logic nghiệp vụ quan trọng.

---

## 1. Nguyên Tắc Phát Triển (Development Principles)
- **Source of Truth**: 
  - **Original Requirements**: `BLUEPRINT.md` (Base).
  - **Current State**: `SUMMARY.md`. **Quan trọng**: File này cập nhật trạng thái thực tế của tính năng và hướng dẫn sử dụng. Updated auto-magically.
- **Ngôn ngữ**:
  - **Code/Comments**: Tiếng Anh (Chuẩn).
  - **Documents/Logs/Commits**: Tiếng Việt (Để team vận hành dễ nắm bắt).
- **Versioning**: Tuân thủ Semantic Versioning.
  - Development: `0.xx.xx`
  - Production Release: `1.0.0` thay đổi lớn.

## 2. Clean Architecture (Kiến Trúc Sạch)

### Nguyên Tắc Cốt Lõi
Studio AI áp dụng **Clean Architecture** để đảm bảo:
- **Testability**: Mỗi layer test độc lập.
- **Maintainability**: Thay đổi 1 layer không ảnh hưởng layer khác.
- **Separation of Concerns**: Mỗi layer có 1 nhiệm vụ duy nhất.

### Layer Structure

| Layer | Vị trí | Nhiệm vụ |
|-------|--------|----------|
| **UI** | `resources/views`, `resources/js` | Blade templates, Alpine components |
| **Presentation** | `app/Http`, `app/ViewModels` | Controllers, Requests, ViewModels |
| **Domain** | `app/Domain` | UseCases, Entities, Repository Interfaces |
| **Data** | `app/Data`, `app/Models` | Repository Implementations, API Clients |
| **Core** | `app/Core` | Logging, Events, Utilities |

### Quy Tắc Truy Cập Layer

> [!IMPORTANT]
> **LUẬT BẤT DI BẤT DỊCH**: Layer trên chỉ được gọi layer dưới. KHÔNG BAO GIỜ gọi ngược.

```
UI → Presentation → Domain ← Data
                      ↑
                     Core (utilities only)
```

- **UI** chỉ gọi **Presentation** (ViewModels, Controllers).
- **Presentation** chỉ gọi **Domain** (UseCases).
- **Domain** KHÔNG gọi bất kỳ layer nào (pure business logic).
- **Data** implements interfaces từ **Domain**.

### Data Flow Pattern

```
Request → Controller → UseCase → Repository Interface
                                        ↓
                            Repository Implementation
                                        ↓
                               Eloquent / API Client
```

### Áp Dụng Thực Tế

1. **Controller** (Presentation): Nhận request, validate, gọi UseCase, trả response.
2. **UseCase** (Domain): Chứa business logic, gọi Repository interface.
3. **Repository** (Data): Implement interface, tương tác DB/API.
4. **Entity** (Domain): Pure data class, không phụ thuộc Eloquent.
5. **Mapper** (Data): Chuyển đổi Eloquent Model ↔ Domain Entity.

### Convention cho Service Layer

- **AI Services** (`app/Data/Api/`): Gemini, Fal.ai clients.
- **UseCases** (`app/Domain/UseCases/`): 1 UseCase = 1 hành động nghiệp vụ.
- **Repositories** (`app/Domain/Repositories/`): Interface chỉ định hợp đồng.

## 3. Hệ Thống Phân Quyền (RBAC Strategy)
Dự án sử dụng mô hình 3 Roles cứng:
1.  **Admin**: Quyền tuyệt đối. Quản lý System Settings, User, và cấu hình AI Wizard.
2.  **Manager**: Team Leader/Vận hành. Có quyền xem tất cả dữ liệu (View All) để giám sát, chỉnh sửa thư viện hệ thống (System Libs).
3.  **User**: Nhân viên. Chỉ thao tác trên dữ liệu của chính mình (Own Data) và xem thư viện hệ thống (Read Only).
    - **Library Access**: User có thể xem/sử dụng/duplicate Prompt & Image của người khác, nhưng **KHÔNG** được phép Edit/Delete (Chỉ Owner/Admin mới có quyền này).
    - **Duplication Rule**: Khi User A nhân bản prompt của User B, prompt mới thuộc quyền sở hữu của User A (Full Rights).

**Lưu ý Dev**: Khi check quyền trong code, ưu tiên check `ProductPolicy` hoặc `$user->can('permission_name')`, hạn chế check role cứng `$user->hasRole('Admin')` trừ trường hợp đặc biệt.

## 4. Mô Hình Dữ Liệu Cốt Lõi (Core Data Models)
### Thứ tự khởi tạo (Migration Order)
Rất quan trọng để tránh lỗi Foreign Key:
1.  `users` (Core)
2.  `products` (Phụ thuộc User)
3.  `batches` (Phụ thuộc Product & User)
4.  `generations` (Phụ thuộc Batch)

### Các Module Chính
- **Storage Hub**: Nơi lưu trữ tài nguyên tái sử dụng (Prompts, Model Presets, Backgrounds).
- **Processing**: Nơi thực thi AI. Dữ liệu luân chuyển theo luồng: `Input Images` -> `Batch` (Group) -> `Generations` (Kết quả).

## 5. Quy Ước Coding (Conventions)
- **Service Layer**: Logic gọi AI (Gemini, Fal) phải tách ra Service riêng (`App\Services\Ai\...`), không viết trong Controller.
- **JSON Fields**: Các trường linh động (Product specs, Prompt wizard data) lưu dạng JSON trong DB thay vì tách bảng con nếu không cần join/query phức tạp.
- **Tracking**: Mọi Prompt được tạo ra phải lưu rõ nguồn gốc (`method`: manual/image/wizard) để phục vụ Analytics sau này.

### 5.2 Fal.ai Integration Standards (Quan trọng)
- **Domains**:
  - **Sync**: `https://fal.run/{model_id}` (Cho task nhanh < 10s)
  - **Queue**: `https://queue.fal.run/{model_id}` (Cho task AI heavy)
  - **Storage**: `https://fal.media/files/upload` (Upload file generic)
  - **WebSocket**: `wss://ws.fal.run/{model_id}` (Realtime)
  - **Banned**: KHÔNG dùng `api.fal.ai` hoặc `rest.fal.ai` (Domain cũ/không tồn tại).
- **Storage Upload**:
  - Luôn dùng `multipart/form-data` upload lên endpoints của Fal.media.
  - **Base64**: Hạn chế tối đa dùng Base64 cho ảnh lớn (>4MB) để tránh lỗi Database Packet Size. Ưu tiên upload lấy URL.
- **Queue Handling**: 
  - Submit request nhận `request_id`.
  - Poll `status_url` hoặc `response_url` để lấy kết quả (`status: "COMPLETED"`).
### 4.5 Debug Info API Structure (Standard v1.5)
Để đảm bảo khả năng debug và integration nhất quán giữa Dev & Prod, API trả về `debug_info` (khi `dev_mode=true`) phải tuân thủ cấu trúc của Fal.ai API Payload (GPT-Image 1.5):

```json
{
  "fal_api_request": {
    "prompt": "String...",
    "image_urls": [
      "https://url-to-model-image.com/file.jpg", // Model Image (URL String)
      "https://url-to-product-image.com/file.png" // Product Image (URL String)
    ],
    "image_size": "square_hd", // "square_hd" (1024x1024), "1536x1024", "1024x1536", or "auto"
    "background": "auto",
    "quality": "high",
    "input_fidelity": "high",
    "num_images": 1,
    "output_format": "png"
  }
}
```
- **Development Mode Handling**:
  - Khi làm việc với file ảnh ở local (`dev_mode=true`), luôn sử dụng Absolute Path (`Storage::disk('local')->path()`) để đọc file.
  - Tránh dùng HTTP URL (`url()`, `asset()`) để gọi nội bộ (internal curl/get) vì sẽ gây ra loop/deadlock trên PHP Server đơn luồng.

## 6. Cấu Trúc Routes (Route Structure)
Theo Spec v1.3 Section 7.1:
```
/dashboard              -> Dashboard (All users)

/features/*             -> AI Features (All users)
  /batch                  Batch Processor
  /beautifier             Product Beautifier
  /virtual-model          Virtual Try-on

/storage/*              -> User Resources (All users, own data)
  /prompts                Prompts Library
  /images                 Images Library
  /models                 Model Presets

/history                -> Processing History (Own for User, All for Admin/Manager)

```
/admin/*                -> Admin Only (Middleware: role:Admin)
  /users                  User Management
  /settings               System Settings
  /wizard-options         Wizard Step Options
```

### Storage Configuration (Important)
- **Symlink**: Bắt buộc chạy `php artisan storage:link` để public images có thể truy cập được.
- **Centralized Path**: Tất cả ảnh user upload được lưu tại `storage/app/public/prompts/`.
- **Image Library**: Là view Read-only, hiển thị ảnh từ thư mục trên. Không cho phép upload trực tiếp tại đây.

## 7. UI/UX Design Workflow

### UI/UX Pro Max Integration
Studio AI sử dụng **UI/UX Pro Max** workflow để đảm bảo thiết kế giao diện đạt tiêu chuẩn chuyên nghiệp.

#### Quy Trình Thiết Kế
Khi phát triển/cải thiện giao diện, tuân thủ workflow sau:

1. **Phân Tích Yêu Cầu**
   - Product type: SaaS Dashboard
   - Style: Minimalism + Glassmorphism
   - Industry: Fashion E-Commerce

2. **Nghiên Cứu Design System** (sử dụng `search.py`)
   ```bash
   # Product recommendations
   python .shared/ui-ux-pro-max/scripts/search.py "saas dashboard" --domain product
   
   # Style guidelines  
   python .shared/ui-ux-pro-max/scripts/search.py "minimal clean" --domain style
   
   # Typography
   python .shared/ui-ux-pro-max/scripts/search.py "modern professional" --domain typography
   
   # Color palette
   python .shared/ui-ux-pro-max/scripts/search.py "saas" --domain color
   
   # UX best practices
   python .shared/ui-ux-pro-max/scripts/search.py "animation accessibility" --domain ux
   
   # Stack guidelines
   python .shared/ui-ux-pro-max/scripts/search.py "card layout" --stack html-tailwind
   ```

3. **Design System Chuẩn**
   - **Typography:** Poppins (headings) + Open Sans (body)
   - **Colors:** Trust Blue (#2563EB primary), Orange (#F97316 CTA)
   - **Spacing:** Consistent padding (p-6, space-y-4)
   - **Cards:** rounded-2xl shadow-sm p-6
   - **Transitions:** duration-200 ease-out

#### Checklist Trước Khi Deploy UI

✅ **Visual Quality**
- [ ] Không dùng emoji icons (dùng SVG từ Heroicons/Lucide)
- [ ] Icons có kích thước đồng nhất (w-6 h-6)
- [ ] Hover states sử dụng `transition-colors duration-200`

✅ **Interaction**
- [ ] Tất cả clickable elements có `cursor-pointer`
- [ ] Hover feedback rõ ràng
- [ ] Focus states cho keyboard navigation

✅ **Light/Dark Mode**
- [ ] Text contrast ≥ 4.5:1 cho light mode
- [ ] Glass elements visible (opacity ≥ 80%)
- [ ] Borders visible trong cả 2 modes

✅ **Accessibility**
- [ ] Images có alt text
- [ ] Form inputs có labels
- [ ] Hỗ trợ `prefers-reduced-motion`

## 8. Quy Chuẩn Prompt Engineering (AI Standards)

### Cấu Trúc Prompt Chuẩn (V2)
Mọi prompt sinh ra cho Fashion/E-commerce phải tuân thủ cấu trúc sau để đảm bảo chất lượng và khả năng tái sử dụng:

**Pattern (2 Templates - Art Director Approved):**

**Type A: Model Shot (Có Người)**
`Fashion product photography, [Model Description] wearing [Generic Product], [Pose/Action], [Detailed Environment & Props], [Lighting & Atmosphere], [Camera Angle], [Quality Modifiers] --ar [Ratio]`

**Type B: Product Only (Tĩnh Vật)**
`Product photography, [Generic Product], [Placement], [Detailed Environment & Props], [Lighting & Atmosphere], [Camera Angle], [Quality Modifiers] --ar [Ratio]`

### Các Quy Tắc Quan Trọng
1. **Generic Naming**: KHÔNG mô tả màu sắc/chi tiết sản phẩm cụ thể (ví dụ: dùng "sandals" thay vì "black leather sandals"). Để Stable Diffusion/Flux tự fill hoặc dùng ControlNet.
2. **Environment First**: Luôn mô tả Surface, Background và Props chi tiết để tạo chiều sâu không gian.
3. **Lighting & Shadows**: Bắt buộc có mô tả ánh sáng (soft box, window light) và bóng đổ (soft shadows, highlights) để đạt độ chân thực (Photorealism).
4. **Separation**: Đầu vào từ Image Analysis luôn phải tách biệt `analysis` (để user hiểu) và `prompt` (để máy hiểu).

