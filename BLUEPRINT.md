# STUDIO AI — BLUEPRINT
## AI-Powered Fashion E-Commerce Image Processing Platform

> **Version:** 2.0.0  
> **Last Updated:** 2026-01-14  
> **Status:** Active Development

---

## 1. PROJECT OVERVIEW

### 1.1 Purpose
Studio AI is an internal tool for fashion e-commerce teams to:
- Generate studio-quality product images from raw photos.
- Manage centralized resource libraries (Prompts, Images, Model Presets).
- Automate batch processing workflows with AI.

### 1.2 Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | Laravel 12.x |
| **Frontend** | Blade + Tailwind CSS + Alpine.js |
| **Database** | MySQL |
| **Auth** | Laravel Breeze + Spatie Permissions |
| **AI Services** | Google Gemini (Analysis), Fal.ai (Generation) |

---

## 2. ROLE-BASED ACCESS CONTROL

Three fixed roles managed via `spatie/laravel-permission`:

| Capability | Admin | Manager | User |
|------------|:-----:|:-------:|:----:|
| System Settings | ✅ | ❌ | ❌ |
| User Management | ✅ | ❌ | ❌ |
| Model Presets (Edit) | ✅ | ✅ | 👁️ View |
| Prompt/Image Lib (View) | ✅ All | ✅ All | ✅ All |
| Prompt/Image Lib (Edit/Del)| ✅ All | ✅ Own | ✅ Own |
| Generative Features | ✅ | ✅ | ✅ |
| View History | ✅ All | ✅ All | ✅ Own |

---

## 3. CLEAN ARCHITECTURE

### 3.1 Overview
Studio AI follows **Clean Architecture** principles adapted for Laravel. This ensures testability, maintainability, and clear separation of concerns.

```
┌─────────────────────────────────────────────────────────────────────┐
│                           STUDIO AI                                  │
│                     Clean Architecture                               │
└─────────────────────────────────────────────────────────────────────┘

   ┌─────────────────────────────────────────────────────────────────┐
   │                         UI LAYER                                 │
   │  Blade Views  │  Alpine.js  │  Tailwind CSS  │  Components      │
   │  (resources/views, resources/js, resources/css)                  │
   └──────────────────────────────┬──────────────────────────────────┘
                                  │ Renders ViewModels
                                  ▼
   ┌─────────────────────────────────────────────────────────────────┐
   │                    PRESENTATION LAYER                            │
   │  Controllers  │  ViewModels  │  Requests  │  Policies           │
   │  (app/Http/Controllers, app/ViewModels, app/Http/Requests)       │
   └──────────────────────────────┬──────────────────────────────────┘
                                  │ Calls UseCases
                                  ▼
   ┌─────────────────────────────────────────────────────────────────┐
   │                      DOMAIN LAYER                                │
   │  UseCases  │  Entities  │  Repository Interfaces  │  DTOs       │
   │  (app/Domain/UseCases, app/Domain/Entities, app/Domain/Repos)    │
   └──────────────────────────────┬──────────────────────────────────┘
                                  │ Uses Repositories
                                  ▼
   ┌─────────────────────────────────────────────────────────────────┐
   │                       DATA LAYER                                 │
   │  Repository Impl  │  Eloquent Models  │  API Clients  │ Mappers │
   │  (app/Data/Repositories, app/Models, app/Data/Api)               │
   └──────────────────────────────┬──────────────────────────────────┘
                                  │
              ┌───────────────────┴───────────────────┐
              ▼                                       ▼
     ┌─────────────────┐                     ┌─────────────────┐
     │    DATABASE     │                     │  EXTERNAL APIs  │
     │     MySQL       │                     │ Gemini │ Fal.ai │
     └─────────────────┘                     └─────────────────┘
```

### 3.2 Layer Definitions

| Layer | Responsibility | Laravel Location |
|-------|----------------|------------------|
| **UI** | Blade templates, assets, Alpine components | `resources/views`, `resources/js` |
| **Presentation** | Controllers, Requests, ViewModels, Policies | `app/Http`, `app/ViewModels` |
| **Domain** | Business logic, UseCases, Entities, Repo interfaces | `app/Domain` |
| **Data** | Repository implementations, Models, API clients | `app/Data`, `app/Models` |
| **Core** | Cross-cutting concerns (Logging, Events) | `app/Core` |

### 3.3 Data Flow

```
User Action → Controller → UseCase → Repository (Interface)
                                           ↓
                               Repository Implementation
                                           ↓
                              Eloquent Model / API Client
                                           ↓
                                     Database / API
```

### 3.4 Layer Access Rules

> [!IMPORTANT]
> Each layer can only depend on layers below it. Never depend on layers above.

| Layer | Can Access |
|-------|------------|
| UI | Presentation |
| Presentation | Domain |
| Domain | Nothing (Pure business logic) |
| Data | Domain (implements interfaces) |
| Core | Nothing (utilities only) |

### 3.5 Directory Structure

```
app/
├── Core/                        # Cross-cutting concerns
│   ├── Logging/
│   └── Events/
│
├── Data/                        # Data layer
│   ├── Api/                     # External API clients
│   │   ├── GeminiClient.php
│   │   └── FalAiClient.php
│   ├── Repositories/            # Repository implementations
│   │   ├── EloquentPromptRepository.php
│   │   └── EloquentImageRepository.php
│   └── Mappers/                 # Data ↔ Domain mappers
│
├── Domain/                      # Domain layer (Business logic)
│   ├── Entities/                # Domain entities (not Eloquent)
│   │   ├── Prompt.php
│   │   └── Generation.php
│   ├── Repositories/            # Repository interfaces
│   │   ├── PromptRepositoryInterface.php
│   │   └── ImageRepositoryInterface.php
│   ├── UseCases/                # Business use cases
│   │   ├── Prompts/
│   │   │   ├── CreatePromptUseCase.php
│   │   │   └── ListPromptsUseCase.php
│   │   └── Features/
│   │       ├── ProcessBatchUseCase.php
│   │       └── GenerateImageUseCase.php
│   └── DTOs/                    # Data Transfer Objects
│
├── Http/                        # Presentation layer
│   ├── Controllers/
│   ├── Requests/
│   └── Middleware/
│
├── ViewModels/                  # Presentation models for views
│   ├── DashboardViewModel.php
│   └── PromptListViewModel.php
│
├── Models/                      # Eloquent models (Data layer)
├── Policies/                    # Authorization policies
└── Providers/                   # Service providers (IoC)
```

---

## 4. DATABASE SCHEMA

### 4.1 Core Tables

| Table | Purpose |
|-------|---------|
| `users` | User accounts with roles |
| `roles` / `permissions` | Spatie RBAC |
| `saved_prompts` | User's saved prompts |
| `image_libraries` | User's uploaded/generated images |
| `model_presets` | AI model presets (Admin managed) |
| `prompt_options` | Wizard configuration (Legacy/Admin) |
| `batches` | Processing jobs |
| `generations` | Individual generation results |

---

## 5. ROUTE STRUCTURE

### 5.1 Application Routes

```
/dashboard              -> Dashboard (All Users)

/features/*             -> AI Features
  /batch                  Batch Processor
  /beautifier             Product Beautifier
  /staging                Product Staging
  /products-virtual       Products Virtual (Virtual Try-On)

/storage/*              -> User Resources
  /prompts                Prompts Library
  /images                 Images Library
  /model-presets          Model Presets (Read-Only for Users)

/history                -> Processing History

/admin/*                -> Admin Only (Middleware: role:Admin)
  /users                  User Management
  /settings               System Settings
  /wizard-options         Wizard Step Options (Legacy Config)
```

---

## 6. FEATURE SPECIFICATIONS

### 6.1 Batch Processor (P0)
Process multiple product images with consistent styling.

**Workflow:**
1. Upload raw images (Front, Back, Side).
2. Gemini Vision analyzes product attributes (Color, Fabric, Pattern).
3. AI generates a "Master Prompt" for consistency.
4. Fal.ai generates processed images.
5. Gemini validates results (Color Match, Detail Preservation).

### 6.2 Product Beautifier
Enhance single product images with AI.

### 6.3 Product Staging (P2)
Place products in realistic context backgrounds.

**Workflow:**
1. Select product image (Upload or Library).
2. Choose background (Upload image OR Generate via Prompt).
3. AI composites product into scene.

### 6.4 Products Virtual (P1) ✅ Implemented
Virtual Try-On feature cho phép người dùng "mặc thử" sản phẩm lên model/scene.

**Workflow:**
1. Upload Model Image (người mẫu hoặc scene)
2. Upload Product Images (tối đa 4 ảnh từ các góc độ khác nhau)
3. Click "Upload Model & Product" → Hệ thống upload lên Fal.ai Storage
4. Gemini AI phân tích ảnh và tạo prompt mô tả
5. User review và refine prompt (optional)
6. Chọn parameters (Size/Ratio, Background, Quality, Format)
7. Click "Generate" → Fal.ai GPT Image 1 Edit tạo kết quả
8. Preview result → Download hoặc Save to Library

**Technical Details:**
- **Fal.ai API Domains**: 
    - **Sync**: `https://fal.run/{model_id}` (Short tasks)
    - **Queue**: `https://queue.fal.run/{model_id}` (Long tasks/Generations)
    - **Storage**: `https://fal.media/files/upload` (Generic upload)
    - **Warning**: Do NOT use `api.fal.ai` (Does not exist).
- **Fal.ai Storage API**: Generic upload via `fal.media` to get public URLs. No Base64 fallback (avoids DB size issues).
- **Gemini AI**: `analyzeImageForProductVirtual()` method
- **Fal.ai GPT Image 1 Edit**: `editImage()` method via `queue.fal.run` or `fal.run`
- **Quota System**: Daily/Total limits với admin unlimited
- **Database**: `products_virtual_jobs`, `user_quotas` tables

**Routes:**
```
GET  /features/products-virtual              → index
POST /features/products-virtual/analyze     → analyze
POST /features/products-virtual/generate    → generate
GET  /features/products-virtual/{id}/status → status
POST /features/products-virtual/{id}/save-to-library → saveToLibrary
GET  /features/products-virtual/{id}/download → download
```

---

## 7. STORAGE HUB

Centralized resource management:

```
STORAGE HUB
├── 📝 Prompts Library
│   ├── Create Workflow:
│   │   ├── Methods: From Image, Wizard, Manual (Tracked as `method`)
│   │   └── Process: Input -> Gemini AI -> Review/Edit -> Save
│   ├── Features: Search, Filter by Category, Favorites, Duplicate (New Ownership)
│   └── Configuration: Gemini API Key via System Settings
│
├── 🖼️ Images Library
│   ├── Types: Original, Generated, Reference
│   ├── Features: Advanced Viewer (Zoom/Rotate/Flip)
│   └── Actions: Bulk Delete, Download, Reuse
│
└── 👤 Model Presets
    └── Admin-managed AI model configurations
```

---

## 8. UI/UX GUIDELINES

### 8.1 Design Workflow

Studio AI follows the **UI/UX Pro Max** workflow for all interface development:

#### Research Process
Before implementing UI changes, research design patterns using:
```bash
python .shared/ui-ux-pro-max/scripts/search.py "<keyword>" --domain <domain>
```

**Required Research Domains:**
- `product` - Style recommendations for SaaS dashboards
- `style` - Detailed style guide (Minimalism + Glassmorphism)
- `typography` - Font pairings with Google Fonts
- `color` - Color palette for SaaS applications
- `ux` - Best practices and anti-patterns
- `stack` - HTML + Tailwind specific guidelines

#### Design System Standards

**Typography:**
- Headings: **Poppins** (400, 500, 600, 700)
- Body: **Open Sans** (300, 400, 500, 600, 700)
- Import: `@import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap');`

**Color Palette:**
- Primary: `#2563EB` (Indigo-600) - Trust Blue
- Secondary: `#3B82F6` (Blue-500)
- CTA: `#F97316` (Orange-500)
- Background: `#F8FAFC` (Slate-50)
- Text: `#1E293B` (Slate-800)
- Border: `#E2E8F0` (Slate-200)

**Spacing & Layout:**
- Card structure: `rounded-2xl shadow-sm p-6`
- Internal spacing: `space-y-4` or `space-y-6`
- Consistent max-width: `max-w-7xl`

**Animation:**
- Transitions: `transition-colors duration-200`
- Easing: `ease-out` for entering, `ease-in` for exiting
- Respect `prefers-reduced-motion`

### 8.2 Design Standards

- **Icons**: SVG only (Heroicons/Lucide). No emojis.
- **Interactions**: Smooth transitions (duration-200), cursor-pointer on clickable elements.
- **Light/Dark Mode**: Ensure contrast and visibility.

> **Note:** All UI/UX design decisions should follow the **UI/UX Pro Max** workflow (`/.agent/workflows/ui-ux-pro-max.md`). This workflow provides product, style, typography, color, UX, and stack guidelines to ensure a professional, consistent design system.

### 8.3 Sidebar Navigation

```
┌──────────────────────────────────────┐
│  STUDIO AI              [User ▼]     │
├──────────────────────────────────────┤
│  📊 Dashboard                        │
│                                      │
│  [ FEATURES ]                        │
│  • Batch Processor                   │
│  • Beautifier                        │
│  • Virtual Model                     │
│  • Product Staging                   │
│                                      │
│  [ STORAGE ]                         │
│  • Prompts                           │
│  • Images Library                    │
│  • Model Presets                     │
│                                      │
│  ──────────────────────────────────  │
│  • History                           │
│                                      │
│  [ ADMIN ] *Admin Only               │
│  • Users                             │
│  • Settings                          │
│  • Wizard Options                    │
└──────────────────────────────────────┘
```

---

## 9. API ENDPOINTS

### 9.1 Storage Hub
```
GET  /api/prompts              List user prompts
POST /api/prompts              Create prompt
PUT  /api/prompts/{id}         Update prompt
POST /api/prompts/{id}/copy    Duplicate prompt

GET  /api/library/images       List images
POST /api/library/upload       Upload image
POST /api/library/bulk-delete  Bulk delete
```

### 9.2 Features
```
POST /api/features/batch       Submit batch job
POST /api/features/staging     Submit staging job
GET  /api/jobs/{id}/status     Poll job status
```

---

## 10. CHANGELOG

### v2.0.0 (2026-01-13)
- **Removed**: My Products feature.
- **Removed**: Auto Prompt Wizard feature.
- **Merged**: Model Library + Model Presets → Model Presets.
- **Renamed**: My Prompts → Prompts, My Images → Images Library.
- **Updated**: UI language enforced to English.

### v1.3.0 (2026-01-10)
- Initial complete specification.

---

**Document Type:** Blueprint (Source of Truth)
