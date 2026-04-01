# 🗺️ L'Artisan Feature Map & Documentation

Welcome to the central directory for the L'Artisan Marketplace. This map helps you quickly find the code for specific features, organized by functional modules rather than chronological logs.

---

## 🏗️ Core & UI
**Gist: [01_CORE_UI.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/01_CORE_UI.md)**
- **Global Layout**: `layout.blade.php` (Navigation, Search, Footers)
- **Dashboard Layout**: `layoutdash.blade.php` (Sidebar-driven UI)
- **Asset Config**: `vite.config.js`, `app.css`, `app.js`
- **Image Compression (Client)**: `image-compressor.js`

## 🗄️ Database & Schema
**Gist: [02_DATABASE_SCHEMA.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/02_DATABASE_SCHEMA.md)**
- **Users**: Core user table with artisanal extensions.
- **Products**: JSON image storage, moderation flags, and sponsoring logic.
- **Messaging**: Conversations and Messages relationship schema.
- **Seeders**: Initial Admin account and City seeds.

## 🧠 Models & Business Logic
**Gist: [03_MODELS_LOGIC.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/03_MODELS_LOGIC.md)**
- **User Activity**: "Last Seen" and profile picture URL resolution.
- **Product Lifecycle**: Slug generation, price casting, and vendor relations.
- **Relationship Management**: Favorites, Blocking, and Moderation logic.

## 🔐 Auth & User Management
**Gist: [04_AUTH_USER_MANAGEMENT.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/04_AUTH_USER_MANAGEMENT.md)**
- **Registration & Login**: Secure auth flow with automatic login.
- **Profile Customization**: Password changes, PFP updates, and city selection.
- **Privacy**: Global toggle for phone number visibility on listings.
- **Safety**: `UpdateLastSeen` middleware for real-time online status.

## 🛍️ Marketplace Engine
**Gist: [05_MARKETPLACE_ENGINE.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/05_MARKETPLACE_ENGINE.md)**
- **Discover**: Category filtering and keyword search with indexed queries.
- **Listing Flow**: 5-image upload system with backend compression.
- **Interaction**: Sponsoring requests, user reporting (Signalements), and Favorites.

## 💬 Real-time Messaging
**Gist: [06_REALTIME_MESSAGING.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/06_REALTIME_MESSAGING.md)**
- **Broadcasting**: Laravel Echo & Pusher integration for instant delivery.
- **Chat Center**: Unified messaging interface with Alpine.js.
- **History**: Selective fetching of buyer/seller conversation threads.
- **Blocking**: Mutual blocking system to prevent unwanted contact.

## 🛠️ Admin & Moderation
**Gist: [07_ADMIN_MODERATION.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/07_ADMIN_MODERATION.md)**
- **Moderation Console**: Filament-based product approval queue.
- **Signalements**: User report management and resolution.
- **Site Settings**: App-wide configuration (Maintenance, Banners).
- **Widgets**: Dashboard overview of site activity and pending tasks.

## ☁️ Infrastructure & Storage
**Gist: [08_INFRA_AND_STORAGE.md](file:///d:/marcheartisanalfinalpages/lartisan/gists/full_site/08_INFRA_AND_STORAGE.md)**
- **Cloud Storage**: Cloudflare R2 / S3 integration with signed URL support.
- **Image Helper**: Centralized service for compression and URL generation.
- **Proxying**: `StorageProxyController` for secure production asset serving (CORS bypass).
- **Hardening**: Production environment readiness checklist and filesystem fixes.

---

### 📂 Archive
The original 18 sequential logs have been moved to the `archive/` folder for historical reference.
