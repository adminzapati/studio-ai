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

