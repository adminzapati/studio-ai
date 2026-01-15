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
    - **Removal**: Removed "Product Staging" feature (Code & UI).

## [0.3.5] - 2026-01-14

### Trạng Thái
- **Mục tiêu**: Public tính năng Products Virtual.
- **Tính năng mới**: Products Virtual (Virtual Try-On Workflow).

### Thay đổi
- **Products Virtual**:
    - **Workflow**: Upload ảnh Model + Product -> Gemini phân tích -> Fal.ai ghép ảnh (Edit Image).
    - **Features**: 
        - Quota Tracking (Daily/Total).
        - Gemini Vision Analysis (prompt generation).
        - Fal.ai Integration (fal-ai/gpt-image-1/edit-image).
        - Save result to Image Library.
    - **UI**: Giao diện 2 cột (Upload vs Result), Preview ảnh, Download options.

### Nhật ký
- [2026-01-14 10:00] **Documenting Products Virtual**:
    - Tính năng đã được implement hoàn chỉnh với Controller `ProductsVirtualController` và View `features/products-virtual/index`.
    - Sử dụng `FalAiClient` mới nhất và `GeminiClient` để xử lý hybrid workflow.
    - Tích hợp hệ thống Quota để giới hạn lượt dùng của User.

## [0.3.6] - 2026-01-14

### Trạng Thái
- **Mục tiêu**: Stabilize & Enhance Products Virtual Feature.
- **Tính năng mới**: Prompt Library Integration, Advanced Prompt Logic (Art Director Mode).

### Thay đổi
- **Integration**:
    - **Prompt Library Selector**: Tích hợp tính năng chọn Prompt từ thư viện ngay tại màn hình Products Virtual.
    - **API**: Thêm tính năng trả về JSON cho `PromptController@index` để phục vụ AJAX fetch.
- **AI Engineering**:
    - **Art Director Prompt**: Nâng cấp System Prompt của Gemini lên level "Art Director".
    - **Templates**: Tách biệt 2 template rõ ràng: "Fashion Model" (Có người) và "Product Only" (Tĩnh vật).
- **Fixes**:
    - **Route Error**: Sửa lỗi 500 Route not defined do typo `storage.storage`.
    - **Path Resolution**: Fix lỗi không tìm thấy file ảnh trong Dev Mode do vấn đề `storage/app/private`. Sử dụng `Storage::disk('local')->path()` chuẩn.
    - **Deadlock**: Fix lỗi treo tiến trình PHP khi tải ảnh local qua HTTP URL.

### Nhật ký
- [2026-01-14 14:00] **Debugging & Stabilization**:
    - Phát hiện và xử lý vấn đề Laravel Storage Path (Private vs Public). Update Controller dùng Absolute Path.
    - Fix lỗi Gemini trả về prompt "rác" do không đọc được ảnh input.
- [2026-01-14 15:00] **AI Logic Upgrade**:
    - Refine System Prompt trong `GeminiClient`: Thêm yêu cầu bắt buộc về Camera Angle, Lighting, Micro-details.
    - Implement Few-Shot Learning: Đưa ví dụ chuẩn vào prompt để định hướng style.
- [2026-01-14 15:45] **Component Integration**:
    - Implement Modal "Prompt Library" với giao diện UI Pro Max (Glassmorphism, Grid).
    - Kết nối Frontend Products Virtual với Backend Prompts API.
    - Hoàn tất test luồng Select Prompt -> Bypass Analyze -> Ready to Generate.

## [0.3.7] - 2026-01-14

### Trạng Thái
- **Mục tiêu**: Fix bugs & Standardize API for Products Virtual.
- **Tính năng mới**: Fal.ai API Compatible Debug Info.

### Thay đổi
- **Products Virtual Controller**:
    - **Fix "Selected Prompt" Bug**: Sửa lỗi logic validation (bắt buộc `model_image` khi đã có `prompt_id`) và xử lý null image path.
    - **Fal.ai API Standard**: Cập nhật cấu trúc `debug_info` trả về đúng chuẩn API của Fal.ai (`fal_api_request` object) để dễ dàng debug và integration.
    - **Dev Mode Enhancement**: Mock response trả về đầy đủ cấu trúc như Production.

### Nhật ký
- [2026-01-14 18:30] **Bug Fixes & Standardization**:
    - Fix lỗi 500 khi chọn Prompt từ Library do validation rules.
    - Cập nhật hàm `analyze` và `generate` trả về `debug_info` có key `fal_api_request` chứa đầy đủ: `prompt`, `image_urls`, `image_size`, `background`, `quality`, `input_fidelity`, `num_images`, `output_format`.
    - Verify thành công luồng: Select Prompt -> Upload Product -> Submit -> View Debug Info (Correct JSON Structure).

## [0.3.8] - 2026-01-15

### Trạng Thái
- **Mục tiêu**: Upgrade AI Model to latest version.
- **Tính năng mới**: Fal.ai GPT-Image 1.5 Integration.

### Thay đổi
- **API Client**:
    - Upgrade endpoint từ `gpt-image-1` lên `gpt-image-1.5`.
    - Update Parameters: `aspect_ratio` -> `image_size`. Support `input_fidelity`.
    - Fix `image_urls`: Chuyển từ Object sang List of Strings chuẩn API.
### Thay đổi
- **API Client**:
    - Upgrade endpoint từ `gpt-image-1` lên `gpt-image-1.5`.
    - Update Parameters: `aspect_ratio` -> `image_size`. Support `input_fidelity`.
    - Fix `image_urls`: Chuyển từ Object sang List of Strings chuẩn API.
- **ProductsVirtualController**:
    - Update `generate`: Valid & map `num_images` (Max 4). Force `num_images=1` for regular Users.
    - Update `generate` & `analyze`: Map `size_ratio` (1:1...) sang `image_size` (1024x1024...).
    - Standardize Debug Info JSON.
- **Frontend (Blade/Alpine)**:
    - Add **Number of Images Slider** (1-4).
    - Logic: Chỉ hiện Slider cho Admin/Manager. User mặc định 1.

### Nhật ký
- [2026-01-15 11:45] **Add Number of Images Slider**:
    - Thêm Slider chọn số lượng ảnh (1-4) vào giao diện.
    - Role-based: Admin/Manager mới thấy Slider, User ẩn.
    - Backend: Force `num_images=1` nếu không phải Admin/Manager. Pass `num_images` sang FalAI.
- [2026-01-15 11:30] **Upgrade to GPT-Image 1.5**:
    - Thực hiện theo request user, đối chiếu với `GPT-Image 1.md`.
    - Fix format `image_urls` bị sai object trong bản cũ.
    - Verified bằng Browser Subagent: Debug Info hiển thị đúng Endpoint 1.5 và Parameters.
- [2026-01-15 12:30] **Library Access Control**:
    - **Prompt Library**: Chỉ Admin hoặc Report Owner mới có quyền Edit/Delete. User khác chỉ View/Copy/Duplicate.
    - **Image Library**: Áp dụng quy tắc tương tự cho hành động Delete.
    - **UI**: Ẩn nút Edit/Delete đối với user không có quyền.
- [2026-01-15 12:45] **Fix Access Control Logic**:
    - **Duplication**: Fix lỗi nhân bản vẫn giữ Owner cũ. Logic mới: Người nhân bản sẽ là Owner của Prompt mới (Full Rights).
    - **Admin Access**: Verified và đảm bảo logic `Owner OR Admin` hoạt động đúng. Bất kỳ Admin nào cũng có quyền Edit/Delete Prompt của người khác.
- [2026-01-15 12:50] **UI Enhancement**:
    - **Prompt Card**: Thêm hiển thị tên người tạo (Creator Name) trên Card, cạnh badge Method. Giúp nhận diện chủ sở hữu Prompt dễ dàng hơn.
- [2026-01-15 13:00] **Bug Fix**:
    - **Image Library**: Fix lỗi hiển thị ảnh "gãy" (Broken Image) do record mồ côi (Orphan Record).
    - **Cause**: Khi xóa Prompt, file ảnh bị xóa nhưng entry trong Image Library vẫn còn.
    - **Solution**: Update logic `destroy` trong `PromptController` để xóa đồng bộ entry Image Library tương ứng. Đã xóa record lỗi ID 5 cho User.
- [2026-01-15 13:10] **UI Enhancement**:
    - **Image Library**: Thêm hiển thị tên người tạo (Creator Name) trên thẻ ảnh (góc dưới bên trái), tương tự như Prompt Card. Sử dụng badge màu xanh dương nhạt để đồng bộ giao diện.
- [2026-01-15 13:45] **Bug Fix**:
    - **Image Library Access**: Fix lỗi 403/404 khi truy cập `storage/images`.
    - **Cause**: Xung đột giữa Route `/storage/images` và thư mục vật lý `public/storage/images`.
    - **Solution**: Đổi URL route thành `/storage/gallery` (vẫn giữ nguyên tên route `storage.images.*` để không ảnh hưởng code cũ). Update Sidebar link tự động nhận diện URL mới.
- [2026-01-15 14:05] **[VERIFICATION] Fal.ai Integration Ready**
  - Verified `products_virtual_dev_mode` is set to `false`.
  - Confirmed `fal_api_key` is configured.
  - Review `FalAiClient` and `ProductsVirtualController` logic.
  - Identified upload error "Failed to upload product image to Fal.ai".
- **[FIX] Fal.ai Storage Upload**
  - Updated `FalAiClient::uploadToStorage` to use `https://fal.media/files/upload`.
  - Removed outdated endpoints that were returning 404/405.
  - Removed Base64 fallback mechanism to prevent MySQL "server gone away" errors with large payloads.
  - Verified fix with `test_fal_upload.php`.
```
