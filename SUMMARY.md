# Studio AI - Living Specification (Tài Liệu Sống)

> [!CAUTION]
> **LIVING DOCUMENT**: Tài liệu này không cố định! Nó phản ánh **trạng thái hiện tại** của dự án.
> Mọi thay đổi về tính năng, workflow so với thiết kế ban đầu (Original Spec) PHẢI được cập nhật vào đây ngay lập tức.
> **Mục đích**: Source of Truth cho Dev hiện tại & Future Team. Tránh việc tính năng bị "lạc trôi" hoặc code sai lệch so với thực tế.

**Last Updated**: 2026-01-10
**Based on Original Spec**: v1.3

---

## 1. Feature Status Overview

| Feature Group | Feature Name | Priority | Status | Deviation from Original |
| :--- | :--- | :--- | :--- | :--- |
| **Foundation** | **Auth & RBAC** | P0 | ✅ **Done** | Added `avatar` to Users table. Use `firstOrCreate` for seeders. |
| **Foundation** | **Models & DB** | P0 | ✅ **Done** | Fixed Migration Order logic. |
| **Foundation** | **User Management** | P1 | ✅ **Done** | Admin CRUD & Lock. |
| **Storage Hub** | **Prompt Library** | P1 | ✅ **Done** | Search, Filter, Sort, Duplicate, Edit, Image Thumbnails. |
| **Storage Hub** | **Image Library** | P1 | ✅ **Done** | Auto-sync from Prompt Creation. Gallery Grid. |
| **Storage Hub** | **Model Library** | P1 | ✅ **Done** | Admin-only Management, User Read-only. |
| **Feature** | **Prompt Creation** | P1 | ✅ **Done** | 3-pane workflow, Edit Mode support, Image Reference storage. |
| **Feature** | **Batch Processor** | P1 | ⏳ Pending | - |
| **Feature** | **Product Staging** | P2 | ⏳ Pending | - |
| **Feature** | **Virtual Model** | P2 | ⏳ Pending | - |

---

## 2. Detailed Feature Specifications (Current State)

### 2.1 Authentication & Permissions
- **Status**: Hoàn tất Phase 1.
- **Implementation**: Laravel Breeze + Spatie.
- **Roles**: Admin, Manager, User (Đã seed).
- **Current Logic**:
    - User chỉ thấy data của mình.
    - Manager thấy all data nhưng chỉ edit được System Libs.
    - Admin sờ được mọi thứ.

### 2.2 Storage Hub (Sắp triển khai)
*(Giữ nguyên logic từ Spec v1.3, chưa có thay đổi)*
- **Prompt Library**: Store prompts with `wizard_data` JSON. Track `creation_method` (Manual/Image/Wizard).
- **Image Library**: Display Only. Viewer.js integration for Zoom/Rotate.
- **Model Library**: System Models only for now (Admin managed).

### 2.3 Auto Prompt Wizard
*(Giữ nguyên logic từ Spec v1.3)*
- 5 Steps: Core Info -> Presenting -> Technical -> Polish -> Finish.
- **Note**: Will need dynamic options API.

### 2.4 AI Engines (Batch / Staging / Virtual Model)
*(Giữ nguyên logic từ Spec v1.3)*
- **Batch Processor**: Gemini Vision analyze -> Fal.ai generate.
- **Queue System**: Cần đảm bảo dùng Queue database driver để xử lý job lâu dài.

---

## 3. Log Thay Đổi Tính Năng (Change Log)
*Format: [Date] [Feature] - Nội dung thay đổi so với kế hoạch ban đầu*

- **2026-01-10**:
    - [RBAC] Chốt phương án sử dụng `firstOrCreate` cho Seeder để đảm bảo tính Idempotent (Chạy nhiều lần không lỗi).
    - [Database] Xác định `batches` chạy sau `products` để tránh lỗi khóa ngoại.
    - [User] Thêm trường `avatar` trực tiếp vào bảng `users` thay vì tách bảng profile riêng (Simplification).
    - [Admin] Triển khai **User Management** (CRUD, Lock, Assign Roles) vào core system.

### 6. Admin Management (New!)
*   **User Management**:
    *   **List**: Xem danh sách User, tìm kiếm, lọc theo Role.
    *   **Action**: Tạo mới, Chỉnh sửa thông tin, Reset mật khẩu.
    *   **Security**: Khóa (Lock) và mở khóa tài khoản.
    *   **Role**: Gán quyền Admin, Manager, User.

### 7. Refactoring (Maintenance)
*   **Image System**:
    *   **Centralized**: Gom tụ điểm lưu trữ về 1 folder duy nhất.
    *   **Display Logic**: Image Library chỉ hiển thị, nguồn ảnh từ Prompt Creation.
    *   **Fix**: Symbolic link repair.
    *   **Viewer**: Integrated Viewer.js for advanced image viewing.
    *   **Method Tracking**: Added `method` field to Prompts (Manual/Image/Wizard).
