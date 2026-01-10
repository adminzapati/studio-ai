# Studio AI - Core Project Knowledge (Kiến Thức Cốt Lõi)

> [!IMPORTANT]
> **SỐNG CÒN**: Tài liệu này chứa các kiến thức nền tảng, các quyết định kỹ thuật quan trọng và các quy tắc "bất di bất dịch" của dự án.
> **CẬP NHẬT**: Bắt buộc cập nhật file này khi có thay đổi về kiến trúc, luồng dữ liệu chính, hoặc logic nghiệp vụ quan trọng.

---

## 1. Nguyên Tắc Phát Triển (Development Principles)
- **Source of Truth**: 
  - **Original Requirements**: `studio-ai-complete-specification.md` (Base).
  - **Current State**: `LIVING_SPECIFICATION.md`. **Quan trọng**: File này cập nhật trạng thái thực tế của tính năng. Nếu có thay đổi logic khi code, CẬP NHẬT file này ngay lập tức.
- **Ngôn ngữ**:
  - **Code/Comments**: Tiếng Anh (Chuẩn).
  - **Documents/Logs/Commits**: Tiếng Việt (Để team vận hành dễ nắm bắt).
- **Versioning**: Tuân thủ Semantic Versioning.
  - Development: `0.xx.xx`
  - Production Release: `1.0.0` thay đổi lớn.

## 2. Kiến Trúc Kỹ Thuật (Technical Architecture)
### Stack
- **Framework**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js (Stack "TALL" nhẹ).
- **Auth**: Laravel Breeze + Spatie Permissions.
- **AI Integration**: Google Gemini (Prompt Analysis) + Fal.ai (Image Generation).

### Critical Decisions
- **Blade over React/Vue**: Chọn Blade + Alpine để tối ưu tốc độ phát triển và giảm phức tạp build step cho team nội bộ.
- **Idempotent Seeding**: Các file Seeder (đặc biệt là `RolesAndPermissionsSeeder`) bắt buộc dùng `firstOrCreate`. Lí do: Để có thể chạy `db:seed` an toàn trên môi trường Production mà không gây lỗi duplicate data.
- **UI/UX Standard**: Áp dụng quy trình **UI/UX Pro Max**.
  - **Tool**: Sử dụng `.shared/ui-ux-pro-max/scripts/search.py` để tìm kiếm Color Palette, Typography, và Structure trước khi code.
  - **Rules**:
    - **No Emojis as Icons**: Bắt buộc dùng SVG (Heroicons/Lucide).
    - **Cursor Pointer**: Mọi card/element clickable phải có `cursor-pointer`.
    - **Contrast**: Kiểm tra kỹ độ tương phản Light Mode (đặc biệt là Glassmorphism phải có background opacity cao >80%).
    - **Hover**: Transition mượt mà (duration-200), không layout shift.
- **Documentation Protocol**:
  - **PROJECT_HISTORY.md**: BẮT BUỘC cập nhật file này sau mỗi phiên code hoặc khi hoàn thành một feature.
  - **LIVING_SPECIFICATION.md**: Cập nhật ngay khi có thay đổi về trạng thái tính năng.

## 3. Hệ Thống Phân Quyền (RBAC Strategy)
Dự án sử dụng mô hình 3 Roles cứng:
1.  **Admin**: Quyền tuyệt đối. Quản lý System Settings, User, và cấu hình AI Wizard.
2.  **Manager**: Team Leader/Vận hành. Có quyền xem tất cả dữ liệu (View All) để giám sát, chỉnh sửa thư viện hệ thống (System Libs).
3.  **User**: Nhân viên. Chỉ thao tác trên dữ liệu của chính mình (Own Data) và xem thư viện hệ thống (Read Only).

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

## 6. Cấu Trúc Routes (Route Structure)
Theo Spec v1.3 Section 7.1:
```
/dashboard              -> Dashboard (All users)

/features/*             -> AI Features (All users)
  /batch                  Batch Processor
  /beautifier             Product Beautifier
  /staging                Product Staging
  /virtual-model          Virtual Try-on
  /wizard                 Auto Prompt Wizard

/storage/*              -> User Resources (All users, own data)
  /products               My Products
  /prompts                My Prompts
  /images                 My Images
  /models                 Model Library (Read-only for Users)

/history                -> Processing History (Own for User, All for Admin/Manager)

/admin/*                -> Admin Only (Middleware: role:Admin)
  /users                  User Management
  /settings               System Settings
  /wizard-options         Wizard Step Options
  /model-presets          AI Model Presets
```

