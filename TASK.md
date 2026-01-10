# Studio AI - V1.3.0 Task List

## Phase 1: Foundation (Week 1-2)
- [x] **Setup & Configuration**
  - [x] Install Laravel Breeze (Authentication)
  - [x] Install Spatie Laravel Permission
  - [x] Configure Database (check .env)
  - [x] Publish Spatie Migrations & Config

- **Database & Models**
  - [x] **Auth Layer**
    - [x] Update `User` model (add avatar, remaining logic)
    - [x] Seed Roles (Admin, Manager, User)
    - [x] Seed Permissions
  - [x] **Storage Hub**
    - [x] Create `SavedPrompt` model & migration
    - [x] Create `ImageLibrary` model & migration
    - [x] Create `ModelPreset` model & migration
    - [x] Create `PromptOption` model & migration (for Wizard)
  - [x] **Core Data**
    - [x] Create `Product` model & migration
    - [x] Create `StylePreset` model & migration
  - [x] **Processing**
    - [x] Create `Batch` model & migration
    - [x] Create `Generation` model & migration

## Phase 2: User Tools (Week 3-4)
- [x] **Storage Hub UI**
  - [x] Prompt Library View (CRUD)
  - [x] Image Library View (Gallery, Filtering)
  - [x] Virtual Model Library View
- [x] **Auto Prompt Wizard**
  - [x] Step 1: Core Info UI
  - [x] Step 2: Presenting UI
  - [x] Step 3: Technical UI
  - [x] Step 4: Polish UI
  - [x] Step 5: Finish & Save UI
  - [x] Backend logic for merging prompts

## Phase 3: AI Engines (Week 5-6)
- [ ] **Integration**
  - [ ] Setup Google Gemini API Service
  - [ ] Setup Fal.ai API Service
- [ ] **Features**
  - [ ] Implement Batch Processor Logic
  - [ ] Implement Product Staging Logic
  - [ ] Implement Virtual Model Logic

## Phase 4: Polish & Launch (Week 7-8)
- [ ] **Dashboard**
  - [x] Create Dashboard UI with Stats
- [/] **Admin Features**
  - [x] User Management (CRUD, Lock, Role Assign)
  - [ ] System Settings pages
- [ ] **Finalization**
  - [ ] Testing & QA
  - [ ] Documentation
