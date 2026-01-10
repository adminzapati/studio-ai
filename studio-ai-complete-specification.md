# STUDIO AI - Complete Technical Specification v1.3
## AI-Ready Implementation Guide for Fashion E-Commerce Image Processing

> [!NOTE]
> **Source of Truth**: Tài liệu này là phiên bản chính thức duy nhất, tích hợp mọi yêu cầu tính năng, workflow và cấu trúc hệ thống.

---

```yaml
project:
  name: Studio AI
  version: 1.3.0
  type: Enterprise Internal Tool
  roles: [Admin, Manager, User]
  
stack:
  backend: Laravel 10+
  frontend: Laravel Blade + Tailwind CSS + Alpine.js
  database: MySQL/MariaDB
  queue: Database Driver
  auth: Laravel Breeze/Fortify + Spatie Permissions
  
ai_services:
  prompt_analysis: Google Gemini API (gemini-2.5-flash)
  image_generation: fal.ai (GPT-Image-1.5, FLUX)
```

---

## TABLE OF CONTENTS

1. [Project Overview](#1-project-overview)
2. [Role-Based Access Control](#2-role-based-access-control)
3. [System Architecture](#3-system-architecture)
4. [Database Design](#4-database-design)
5. [Storage Hub](#5-storage-hub)
6. [Feature Specifications & Workflows](#6-feature-specifications--workflows)
   - [Auto Prompt Wizard](#61-auto-prompt-wizard-p1)
   - [E-Commerce Batch Processor](#62-e-commerce-batch-processor-p0)
   - [Product Staging](#63-product-staging-p2)
   - [Virtual Model](#64-virtual-model-p2)
7. [UI/UX Design System](#7-uiux-design-system)
8. [Implementation Phases](#8-implementation-phases)
9. [API Structure](#9-api-structure)

---

## 1. PROJECT OVERVIEW

### 1.1 Purpose
Studio AI là nền tảng tất cả-trong-một để xử lý ảnh sản phẩm thời trang. Hệ thống giúp team vận hành tạo ra ảnh chất lượng studio từ ảnh thô, quản lý thư viện tài nguyên tập trung, và tự động hóa quy trình viết prompt.

### 1.2 Core Modules

| Module | Features | Priority |
|--------|----------|----------|
| **Core** | Authentication, RBAC, System Settings | P0 |
| **Storage Hub** | Image Library, Prompt Library, Model Library | P1 |
| **Generative** | Auto Prompt, Batch Processor, Beautifier, Staging, Virtual Model | P1-P2 |

---

## 2. ROLE-BASED ACCESS CONTROL

Sử dụng `spatie/laravel-permission` để quản lý 3 cấp độ người dùng.

### 2.1 Roles & Capabilities

| Feature / Action | **Admin** (Super) | **Manager** (Vận hành) | **User** (Nhân viên) |
|------------------|:---:|:---:|:---:|
| **System Settings** | ✅ | ❌ | ❌ |
| **Manage Users** | ✅ | ❌ | ❌ |
| **Wizard Config** | ✅ | ❌ | ❌ |
| **System Libraries** (Models/Presets) | ✅ Edit | ✅ Edit | 👁️ View |
| **User Data** (Products/Images) | ✅ Manage All | ✅ View All | ✅ Own Only |
| **Generative Features** | ✅ | ✅ | ✅ |
| **View History** | ✅ All | ✅ All | ✅ Own |

---

## 3. SYSTEM ARCHITECTURE

### 3.1 High-Level Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                           STUDIO AI                                  │
│                   Role-Based Access Control                          │
└─────────────────────────────────────────────────────────────────────┘
                                 │
    ┌─────────────────────────Auth Layer────────────────────────────┐
    │                 (Spatie Permissions)                          │
    │  [Admin] ◄─────► [Manager] ◄─────► [User]                     │
    └────────────────────────────┬──────────────────────────────────┘
                                 │
    ┌────────────────────────────┼────────────────────────────┐
    │                            │                            │
    ▼                            ▼                            ▼
┌─────────────┐          ┌─────────────┐          ┌─────────────┐
│  FRONTEND   │          │   BACKEND   │          │  DATABASE   │
│             │          │             │          │             │
│ Blade Views │◄────────►│  Laravel    │◄────────►│   MySQL     │
│ Tailwind    │          │  Controllers│          │             │
│ Alpine.js   │          │  Services   │          │             │
└─────────────┘          └─────────────┘          └─────────────┘
                                 │
                    ┌────────────┴────────────┐
                    ▼                         ▼
            ┌─────────────┐             ┌─────────────┐
            │ STORAGE HUB │             │ EXT SERVICES│
            │             │             │             │
            │  Prompts    │             │  Gemini API │
            │  Images     │             │  Fal.ai API │
            │  Models     │             │             │
            └─────────────┘             └─────────────┘
```

---

## 4. DATABASE DESIGN

### 4.1 Combined Entity Relationship

```
Creating Tables for: Auth, Core, Storage, Features

1. AUTH & USERS
   - users (id, name, email, avatar, role_id...)
   - roles / permissions (spatie tables)

2. STORAGE HUB (NEW)
   - saved_prompts (user_id, name, prompt, category, is_favorite, wizard_data)
   - image_library (user_id, path, type[original/gen/ref], tags)
   - model_presets (admin_created, gender, ethnicity, image_path)
   - prompt_options (step, category, value, icon - for Wizard Config)

3. CORE DATA
   - products (user_id, category, specs...)
   - style_presets (user_id, settings...)

4. PROCESSING
   - batches (product_id, status, input_params)
   - generations (batch_id, image_id, result_path, status, cost)
```

---

## 5. STORAGE HUB

Trung tâm tài nguyên - nơi User quản lý "tài sản" số của mình.

### 5.1 Libraries Structure

```
STORAGE HUB
├── 📝 Prompt Library
│   ├── Actions: Create, Edit, Copy, Favorite
│   ├── Filter: Category (Fashion, Ecom...), Tags
│   └── Source: Saved from Wizard OR Manual Entry
│
├── 🖼️ Image Library
│   ├── Types:
│   │   ├── Original Uploads
│   │   ├── Generated Results
│   │   └── Reference/Backgrounds
│   └── Actions: Bulk Delete, Download ZIP, Reuse in features
│
└── 👤 Virtual Model Library
    ├── System Models (Admin managed - Read Only for Users)
    └── (Future) Custom User Models
```

---

## 6. FEATURE SPECIFICATIONS & WORKFLOWS

### 6.1 AUTO PROMPT WIZARD (P1)

Tính năng giúp người dùng tạo prompt chuyên nghiệp thông qua giao diện Wizard 5 bước tối ưu.

#### Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                  AUTO PROMPT WIZARD - 5 STEPS                        │
└─────────────────────────────────────────────────────────────────────┘
                                  │
    ┌───────────────┐     ┌───────────────┐     ┌───────────────┐
    │ 1. CORE INFO  │────►│ 2. PRESENTING │────►│ 3. TECHNICAL  │
    │               │     │               │     │               │
    │ • Type: Ecom/ │     │ • Style: Flat/│     │ • Lighting:   │
    │   Fashion     │     │   Model/Ghost │     │   Soft/Studio │
    │ • Product:    │     │ • Floor: Wood/│     │ • Angle: Front│
    │   Shirt/Silk  │     │   Marble      │     │   /Top/45°    │
    │ • Color: Hex  │     │ • BG: Simple  │     │ • Shot: Full/ │
    └───────────────┘     └───────────────┘     │   Detail      │
                                                └───────┬───────┘
                                                        │
                                                        ▼
    ┌───────────────┐                           ┌───────────────┐
    │ 5. FINISH     │◄──────────────────────────│ 4. POLISH     │
    │               │                           │               │
    │ • Preview Text│                           │ • Quality: 8K │
    │ • Edit Manual │                           │ • Mood: Bright│
    │ • Actions:    │                           │ • Negative:   │
    │   [Save Lib]  │                           │   Blurry, Bad │
    │   [Use Now]   │                           └───────────────┘
    └───────────────┘
```

### 6.2 E-COMMERCE BATCH PROCESSOR (P0)

Xử lý hàng loạt ảnh (Front, Back, Side...) để đồng bộ hóa phong cách.

#### Detailed Logic
1.  **Analyze**: Gửi toàn bộ ảnh thô lên Gemini Vision để trích xuất đặc tính sản phẩm (Màu, vải, pattern).
2.  **Prompting**: Gemini tạo một "Master Prompt" đảm bảo tính nhất quán + sub-prompts cho từng góc chụp.
3.  **Generate**: Fal.ai (GPT-Image-1.5) xử lý từng ảnh giữ nguyên chi tiết sản phẩm.
4.  **Validate**: Gemini Vision chấm điểm (Color Match, Detail Preservation).
5.  **Result**: Trả về bộ ảnh hoàn chỉnh.

### 6.3 PRODUCT STAGING (P2)

Đặt sản phẩm vào bối cảnh thực tế. Workflow được cập nhật linh hoạt hơn.

#### Flexible Workflow Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                    PRODUCT STAGING WORKFLOW                          │
└─────────────────────────────────────────────────────────────────────┘
            │
      [Select Product] (from Upload or Image Library)
            │
            ▼
┌───────────────────────────────────────┐
│     CHOOSE BACKGROUND SOURCE          │
├───────────────────┬───────────────────┤
│    OPTION A:      │    OPTION B:      │
│  EXISTING IMAGE   │   PROMPT GEN      │
│                   │                   │
│ [Upload Bg Image] │ [Write Prompt]    │
│ [Select from Lib] │ [Load Saved Prompt]◄───┐
└─────────┬─────────┴─────────┬─────────┘    │
          │                   │          (From Prompt Lib)
          ▼                   ▼
    [ AI COMPOSITING (Fal.ai FLUX/Pro) ]
          │
          ▼
    [ Result Image in Context ]
```

### 6.4 VIRTUAL MODEL (P2)

Đặt quần áo (trải sàn/mannequin) lên người mẫu AI thật.

#### Workflow
1.  Upload ảnh quần áo (hoặc chọn từ Lib).
2.  Chọn **System Model** từ Model Library (Admin đã cấu hình sẵn giới tính, dáng người, màu da).
3.  AI thực hiện "Virtual Try-on" (kết hợp Product Preservation + Human Generation).

---

## 7. UI/UX DESIGN SYSTEM

### 7.1 Sidebar Navigation Structure (Redesigned)

Cấu trúc menu thay đổi tùy theo Role đăng nhập.

```
┌──────────────────────────────────────┐
│  STUDIO AI              [User ▼]     │
├──────────────────────────────────────┤
│  📊 Dashboard                        │
│                                      │
│  [ GROUP: FEATURES ]                 │
│  🖼️ Batch Processor                 │
│  ✨ Beautifier                       │
│  👗 Virtual Model                   │
│  🏞️ Product Staging                 │
│  🤖 Auto Prompt (Wizard)            │
│                                      │
│  [ GROUP: STORAGE ]                 │
│  📦 My Products                      │
│  📝 My Prompts                      │
│  🖼️ My Images                       │
│  👤 Model Library (View Only)       │
│                                      │
│  ──────────────────────────────────  │
│  📜 History                          │
│                                      │
│  [ GROUP: ADMIN ] *Admin Only        │
│  👥 Users                            │
│  ⚙️ Settings                         │
│  🪄 Wizard Options                  │
│  💃 Model Presets (Manage)          │
└──────────────────────────────────────┘
```

---

## 8. IMPLEMENTATION PHASES

### Phase 1: Foundation (Week 1-2)
- **Setup**: Laravel, Database, Spatie Permissions (Roles/Perms).
- **Core**: Auth System (Login/Register), Base Layouts.
- **Backend**: Models & Migrations cho đầy đủ các modules (Storage, Features).

### Phase 2: User Tools (Week 3-4)
- **Library**: Xây dựng Storage Hub (UI + Logic cho Prompts/Images).
- **Wizard**: Thực hiện Auto Prompt Wizard 5 bước & Logic ghép prompt.
- **Backend Admin**: API quản lý Wizard Options.

### Phase 3: AI Engines (Week 5-6)
- **Engine**: Batch Processor (Gemini Analysis + Fal Gen).
- **Engine**: Product Beautifier & Staging (Fal Flux/Pro integrations).
- **Engine**: Virtual Model.

### Phase 4: Polish & Launch (Week 7-8)
- **Dashboard**: Stats & Charts.
- **Admin**: User management UI, System Settings.
- **Quality**: Testing, Security Audit, Documentation.

---

## 9. API STRUCTURE

### 9.1 Storage Hub Endpoints

```php
// Prompts
GET  /api/prompts              // List (Owner/Admin)
POST /api/prompts              // Create
PUT  /api/prompts/{id}         // Update
POST /api/prompts/{id}/copy    // Duplicate prompt

// Library
GET  /api/library/images       // Gallery
POST /api/library/upload       // Upload
POST /api/library/bulk-delete  // Clean up
```

### 9.2 Feature Endpoints

```php
// Wizard
GET  /api/wizard/options       // Get config for steps

// Operations
POST /api/features/batch       // Submit batch job
POST /api/features/staging     // Submit staging job
GET  /api/jobs/{id}/status     // Poll status
```

---

**Document Version:** 1.3 (Source of Truth)
**Date:** 2026-01-10
