# 🎨 Lartisan - PFE Features & Technical Notes

> Quick reference guide for presentation - One-line explanations for each feature

---

## 🔐 **1. Authentication System**

### Registration with Validation
- **Feature:** User registration with email uniqueness check, password confirmation, profile picture upload, and city selection.
- **Files:** `AuthController::store()` | Route: `POST /register` | View: `auth/register.blade.php`
- **Code:** `$request->validate(['email' => 'required|email|unique:users,email', 'password' => 'required|min:8|confirmed'])`
- **Tech:** Laravel built-in validation middleware + Eloquent unique constraint

### Login with Session
- **Feature:** User login with email/password authentication and session management.
- **Files:** `AuthController::authenticate()` | Route: `POST /login` | View: `auth/login.blade.php`
- **Code:** `Auth::attempt(['email' => $email, 'password' => $password])`
- **Tech:** Laravel Guard middleware + Session driver (database)

### Password Reset Flow
- **Feature:** Email-based password reset with secure token (expires in 60 min) and hashed password update.
- **Files:** `PasswordResetController` | Routes: `/forgot-password`, `/reset-password/{token}` 
- **Code:** Token stored in `password_resets` table with email + timestamp
- **Tech:** Laravel built-in password reset + Mail queue system + `Hash::make()`

### Profile Picture Upload
- **Feature:** Users can upload & update profile pictures with automatic compression (70% quality, max 1200px width).
- **Files:** `ImageHelper::compressAndStore()` | Called in `DashController::updateProfil()`
- **Code:** `$user->pfp = ImageHelper::compressAndStore($file, 'pfps', quality: 70, maxWidth: 1200)`
- **Tech:** GD library for image processing + JPG conversion + Cloudflare R2 storage

---

## 🛍️ **2. Product Management (E-commerce)**

### Product Creation with 5 Images
- **Feature:** Sellers upload exactly 5 product images (validated array), stored in order as JSON array with auto-compression.
- **Files:** `ProduitController::store()` | Route: `POST /produit/store` | View: `produit/create.blade.php`
- **Code:** `'images' => 'required|array|size:5'` → foreach compress & store → `json_encode()` in DB
- **Tech:** Image compression (ImageHelper) + JSON array storage in `produits.images` column + Validation rule `size:5`

### Product Editing with Image Replacement
- **Feature:** Sellers can update product details and replace specific images by index without deleting entire gallery.
- **Files:** `ProduitController::update()` | Route: `PUT /produit/{id}` | View: `produit/edit.blade.php`
- **Code:** `foreach($newFiles as $index => $file) { $paths[$index] = compress($file); }` (replaces by index)
- **Tech:** Array index-based replacement + selective image deletion from R2

### Product Display with Related Items
- **Feature:** Shows product details + seller info + 4 related products from same category.
- **Files:** `ProduitController::show()` | Route: `GET /produit/{slug}` | View: `produit/show.blade.php`
- **Code:** `Produit::where('categorie_id', $cat)->where('id', '!=', $id)->limit(4)->get()`
- **Tech:** Eager loading + Query optimization with slug-based routing (SEO-friendly)

### Product Deletion
- **Feature:** Only seller can delete their own product; removes from database and storage.
- **Files:** `ProduitController::destroy()` | Route: `DELETE /produit/{id}` | Middleware: `auth`
- **Code:** Authorization check `$produit->vendeur_id === auth()->id()` → delete images from R2
- **Tech:** Soft delete prevention (hard delete) + Authorization check

### Product Moderation States
- **Feature:** Products go through moderation: `en_attente` → `valide` (approved) or `rejete` (rejected) by admin.
- **Files:** Filament Admin: `ProduitResource` | Table column: `etat_moderation`
- **Database:** `produits.etat_moderation` enum state + Admin panel filters
- **Tech:** Filament filtering + Status enum management

---

## 🔍 **3. Search & Filtering**

### Multi-Filter Search
- **Feature:** Real-time search by product title/description + filters by category, city, price range (min/max).
- **Files:** `HomeController::index()` | Route: `GET /` | View: `home.blade.php`
- **Code:** `$query->where('titre', 'like', '%' . $q . '%')->where('prix', '>=', $min)->where('ville_produit', $ville)`
- **Tech:** AJAX pagination + Laravel query builder with multiple `where` clauses + Lazy loading

### AJAX Infinite Scroll
- **Feature:** Products load automatically as user scrolls down without page reload.
- **Files:** JavaScript in `home.blade.php` + `HomeController::index()` returns JSON on AJAX
- **Code:** `if($request->ajax()) { return response()->json(['html' => view('...')->render()]); }`
- **Tech:** jQuery AJAX + Pagination cursor tracking + URL query params for state

### Sponsored Products Display
- **Feature:** Premium products with approval status and expiration date appear first in listings.
- **Files:** `HomeController::index()` → `$sponsoredProducts` | View: `home.blade.php` (featured section)
- **Code:** `Produit::where('sponsor_status', 'approuve')->where('sponsored_until', '>', now())->take(8)`
- **Tech:** DateTime comparison + Status tracking

---

## ❤️ **4. Favorites System**

### Add/Remove Favorites
- **Feature:** Users toggle products as favorite via many-to-many relationship; prevents self-favoriting.
- **Files:** `ProduitController::toggleFavorite()` | Route: `POST /produit/{id}/favorite` | Middleware: `auth`
- **Code:** `$user->favoris()->toggle($produit->id)` (pivot table auto-handles insert/delete)
- **Tech:** Laravel `belongsToMany` with `toggle()` method + Pivot timestamps

### View Favorites List
- **Feature:** Sorted favorites by latest/oldest date with count display.
- **Files:** `DashController::favoris()` | Route: `GET /favoris` | View: `favoris.blade.php`
- **Code:** `auth()->user()->favoris()->orderBy('favorites.created_at', 'desc')->get()`
- **Tech:** Eager loading via relationship + Pivot table ordering

---

## 💬 **5. Real-Time Messaging System**

### Start Conversation
- **Feature:** Buyer clicks "contact seller" → auto-creates or finds existing conversation for that product.
- **Files:** `MessageController::startConversation()` | Route: `POST /message/{produit}` | Middleware: `auth`
- **Code:** `Conversation::firstOrCreate(['produit_id' => $id, 'acheteur_id' => auth()->id()])`
- **Tech:** `firstOrCreate()` prevents duplicate conversations + Redirect with conversation ID

### Send Message (Auto-Poll AJAX)
- **Feature:** Messages stored in DB; frontend polls every 1-2 seconds for new messages via AJAX; broadcasts via Pusher if configured, otherwise AJAX-only.
- **Files:** `MessageController::sendMessage()` | Route: `POST /api/conversations/{id}/messages` | JavaScript in `message.blade.php`
- **Code:** `Message::create([...])` → `broadcast(new MessageSent($message))` wrapped in try-catch → Fallback to AJAX polling
- **Tech:** Auto-polling AJAX + Optional Pusher broadcasting + Try-catch for graceful fallback

### Load Message History (Auto-Poll)
- **Feature:** JavaScript auto-polls `fetchMessages()` endpoint every 1-2 seconds; marks incoming messages as read when fetched.
- **Files:** `MessageController::fetchMessages()` | Route: `GET /api/conversations/{id}/messages` | JavaScript timer
- **Code:** `setInterval(() => { fetch('/api/messages/{id}').then(res => displayMessages(res)) }, 1500)` + Bulk update: `Message::where('est_lu', false)->update(['est_lu' => true])`
- **Tech:** jQuery/JavaScript `setInterval()` + AJAX polling + Bulk DB updates on fetch

### Fetch All Conversations (List)
- **Feature:** Load all buyer & seller conversations with unread counts, latest message preview, partner online status, block status.
- **Files:** `MessageController::index()` | Route: `GET /api/conversations` | Called on page load & refresh
- **Code:** Merges buyer conversations (all) + seller conversations (only if messages exist) → Maps with unread count, latest message, online status → Sorted by recent activity
- **Tech:** Eager loading with `with()` + Collection merging + DateTime comparison for online status (last_seen_at > 5 min ago)

### Block User
- **Feature:** Users can block sellers/buyers to prevent messages; blocking is bi-directional in UI but one-way in DB.
- **Files:** `BlockController::block()` | Route: `POST /api/block/{user}` | AJAX caller
- **Code:** `$user->blockedUsers()->syncWithoutDetaching([$blocked->id])` (many-to-many pivot)
- **Tech:** Pivot table `blocked_users(blocker_id, blocked_id)` + `syncWithoutDetaching()` for relationships

### Delete Conversation
- **Feature:** Removes conversation and all associated messages from both users' views.
- **Files:** `MessageController::destroy()` | Route: `DELETE /api/conversations/{id}`
- **Code:** `$conversation->delete()` with cascade foreign key to delete related messages
- **Tech:** Soft delete or hard delete with FK constraint

---

## 🚨 **6. Report & Moderation**

### Report Product
- **Feature:** Users report problematic products (spam, fake, etc.) with reason & optional details; prevents duplicate reports.
- **Files:** `ProduitController::signaler()` | Route: `POST /produit/{id}/signaler` | Form in `produit/show.blade.php`
- **Code:** Validation checks duplicate: `SignalementProduit::where('produit_id', $id)->where('utilisateur_id', auth()->id())->exists()`
- **Tech:** SignalementProduit model + Unique constraint logic + Form modal

### Admin Moderation Dashboard
- **Feature:** Admins view all reports/signalements and approve/reject products via Filament UI.
- **Files:** Filament `Resources/Produits/ProduitResource` | Admin panel table with filters
- **Code:** Filament query builder filters by `etat_moderation` + bulk actions for approval
- **Tech:** Filament Admin UI + Authorization (role === 'admin')

---

## ⭐ **7. Product Sponsorship (Premium Feature)**

### Request Sponsorship
- **Feature:** Sellers request premium placement for their product; goes to `en_attente` approval state.
- **Files:** `ProduitController::demanderSponsor()` | Route: `POST /produit/{id}/sponsoriser` | Button in `annonces.blade.php`
- **Code:** `$produit->update(['sponsor_status' => 'en_attente'])` if not already sponsored/expired
- **Tech:** Status enum + Logic check for existing sponsorships

### Sponsor Expiration
- **Feature:** Sponsored products auto-expire based on `sponsored_until` timestamp; admin sets duration on approval.
- **Files:** Database: `produits.sponsor_status` + `sponsored_until` columns
- **Code:** Query filter: `where('sponsored_until', '>', now())` to show active sponsors only
- **Tech:** DateTime comparison + Cron job (optional) to clean up expired

---

## 📊 **8. User Dashboard**

### Seller Announcements (Annonces)
- **Feature:** Sellers see all their products with filter by status (approved, pending, sponsored, rejected).
- **Files:** `DashController::annonces()` | Route: `GET /annonces` | View: `annonces.blade.php`
- **Code:** `Produit::where('vendeur_id', auth()->id())->where('etat_moderation', $filter)->get()`
- **Tech:** Dynamic query builder based on filter parameter

### User Profile Update
- **Feature:** Update name, email, phone, city, phone visibility toggle, password, and profile picture.
- **Files:** `DashController::updateProfil()` | Route: `POST /profil` | View: `profil.blade.php`
- **Code:** `$user->update($validated)` with separate image handling + password hashing
- **Tech:** Form validation + Conditional password hashing + Unique check excluding current user

---

## 🎨 **9. UI/UX Features**

### Dynamic Navigation
- **Feature:** Show categories in footer with `@foreach($categories->take(4))` to split into 2 columns.
- **Files:** `layout.blade.php` component | Injected via `AppServiceProvider`
- **Code:** `$categories->take(4)` for first column, `$categories->skip(4)->take(4)` for second
- **Tech:** Blade template helpers + Eloquent collection methods

### Authentication State in Nav
- **Feature:** Navigation shows different buttons for logged-in vs guest (Dashboard/Login).
- **Files:** `layout.blade.php` | `@auth @endauth` blade directive
- **Code:** `@auth <a href="{{ route('dashboard') }}">Dashboard</a> @endauth`
- **Tech:** Blade conditionals + Named routes

### Responsive Design
- **Feature:** Tailwind CSS responsive breakpoints (mobile-first): `w-full lg:w-2/3` adapts layout.
- **Files:** All `.blade.php` files | Tailwind config in `tailwind.config.js`
- **Code:** `class="flex flex-col lg:flex-row gap-4 lg:gap-8"`
- **Tech:** Tailwind CSS + Mobile-first design

### Image Compression on Upload
- **Feature:** All user-uploaded images auto-compress to 70% quality, max 1200px width, stored as JPG.
- **Files:** `ImageHelper::compressAndStore()` method used throughout
- **Code:** `imagecreatefromjpeg()` → `imagescale()` → `imagejpeg(..., quality:70)` → Store to R2
- **Tech:** GD library + PHP image functions

---

## 💾 **10. File Storage & Optimization**

### Cloudflare R2 Integration
- **Feature:** All product images, user avatars stored on Cloudflare R2 (S3 compatible); served via CDN.
- **Files:** `config/filesystems.php` disk `lartisan` | Environment: AWS credentials
- **Code:** `Storage::disk('lartisan')->put($path, $content)` automatically uploads to R2
- **Tech:** Laravel Storage API + AWS S3-compatible endpoint + Hardcoded credentials in config

### Storage Proxy for Admin
- **Feature:** Admin panel accesses images through app domain to bypass CORS restrictions.
- **Files:** `StorageProxyController::__invoke()` | Route: `/storage-proxy/{path}`
- **Code:** Fetches from R2 and proxies response with correct headers
- **Tech:** HTTP client + CORS handling + URL rewriting

### Image Cleanup on Delete
- **Feature:** When user deletes product/avatar, associated files are removed from R2 storage.
- **Files:** `DashController::updateProfil()` | `ProduitController::destroy()`
- **Code:** `Storage::disk('lartisan')->delete($path)` if file exists
- **Tech:** Cleanup on cascade delete + Conditional deletion

---

## 🔧 **11. Admin Panel (Filament)**

### Product Management UI
- **Feature:** Admin table with sortable columns, filters, bulk actions (approve/reject), inline editing.
- **Files:** Filament `Produits/ProduitResource.php` + `ProduitForm.php` + `ProduitsTable.php`
- **Code:** Form builder: `TextInput::make('titre')` | Table builder: `Columns\TextColumn::make('titre')->sortable()`
- **Tech:** Filament Admin UI + Eloquent model resource

### Dashboard Widgets with Stats
- **Feature:** Admin dashboard displays 3 key stats: new users today, total products online, pending reports count.
- **Files:** Filament `Widgets/StatsOverview.php` | Dashboard page
- **Code:** 
  ```php
  $newUsersToday = User::whereDate('created_at', '=', Carbon::today())->count();
  $totalProduits = Produit::count();
  $pendingReports = SignalementProduit::where('est_traite', '=', false)->count();
  return [
    Stat::make('Nouveaux Utilisateurs (Aujourd\'hui)', $newUsersToday)
      ->description('Inscriptions depuis minuit')
      ->descriptionIcon('heroicon-m-user-group')
      ->color('success'),
    // ... similar for other stats
  ];
  ```
- **Tech:** Filament `StatsOverviewWidget` + Carbon date filtering + Color coding (danger for pending reports > 0)

---

## 🌍 **12. Database & Models**

### Product Model Relationships
- **Feature:** Product belongs to User (seller), Categorie, has many SignalementProduit, many-to-many Favoris.
- **Files:** `app/Models/Produit.php`
- **Code:** 
  ```php
  public function vendeur() { return $this->belongsTo(User::class); }
  public function categorie() { return $this->belongsTo(Categorie::class); }
  ```
- **Tech:** Eloquent relationships + Foreign keys

### User Model with Relationships
- **Feature:** User has many Products, many Favorites (pivot), many BlockedUsers (self-referential many-to-many).
- **Files:** `app/Models/User.php`
- **Code:** 
  ```php
  public function favoris() { return $this->belongsToMany(Produit::class, 'favoris', 'utilisateur_id', 'produit_id'); }
  public function blockedUsers() { return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id'); }
  ```
- **Tech:** Self-referential many-to-many + Pivot tables with timestamps

---

## 🔐 **13. Security & Validation**

### Authorization Checks
- **Feature:** Every resource-modifying action checks if user owns/has permission.
- **Files:** Throughout controllers (e.g., `ProduitController::edit()`)
- **Code:** `if($produit->vendeur_id !== auth()->id()) abort(403);`
- **Tech:** Manual authorization + Middleware `auth` on protected routes

### CSRF Protection
- **Feature:** Form submissions protected by CSRF token in `_token` hidden field.
- **Files:** `VerifyCsrfToken` middleware + `@csrf` in forms
- **Code:** All POST/PUT/DELETE forms include `@csrf`
- **Tech:** Laravel built-in CSRF middleware

### Input Validation
- **Feature:** All user inputs validated server-side with type/length/format rules.
- **Files:** Every controller method using `$request->validate()`
- **Code:** `'email' => 'required|email|unique:users,email'` + custom messages
- **Tech:** Laravel validation rules + Request class

---

## 📧 **14. Email System**

### Password Reset Email
- **Feature:** User receives email with secure reset link (expires in 60 min).
- **Files:** `ResetPasswordNotification.php` | `PasswordResetController::sendResetLink()`
- **Code:** Token generated → Email sent via Mail facade → User clicks link → Form submission updates password
- **Tech:** Laravel Mail + Token generation + `Mail::send()`

### Future Features
- **Welcome Email:** Send on registration
- **Order Confirmation:** Send after purchase
- **Can use Resend (100 emails/day free) or Mailtrap (500/month free) or local logging

---

## 🚀 **15. Performance & Optimization**

### Query Optimization (Eager Loading)
- **Feature:** Use `with()` to prevent N+1 queries when loading relationships.
- **Files:** `ProduitController::show()` → `$produit->load('vendeur')`
- **Code:** `Produit::with('vendeur', 'categorie')->get()` instead of looping and calling `$produit->vendeur`
- **Tech:** Eloquent `with()` method + Query batching

### Pagination
- **Feature:** Products paginated (15 per page) with next/prev links to reduce memory/load.
- **Files:** `HomeController::index()`
- **Code:** `Produit::paginate(15)` returns paginator object with links
- **Tech:** Laravel paginator + URL query parameter `page=2`

### Caching Opportunities
- **Feature:** Categories list could be cached (rarely changes).
- **Files:** Could add to `AppServiceProvider` boot method
- **Code:** `Cache::remember('categories', 3600, function() { return Categorie::all(); })`
- **Tech:** Laravel cache (file/Redis) with 1-hour TTL

---
public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->statut_compte === 'banni') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été banni.'
            ]);
        }

        return $next($request);
    }

## 📱 **Presentation Tips**

1. **Show the search/filter live:** Search for a product, then filter by category/price
2. **Demo messaging:** Open 2 browsers, send messages between accounts in real-time
3. **Show admin panel:** Log in as admin, demonstrate product moderation & approval
4. **Highlight security:** Explain authorization checks, CSRF tokens, input validation
5. **Database schema:** Diagram showing relationships (Users → Products → Favoris, etc.)
6. **Storage architecture:** Explain Cloudflare R2 + image compression pipeline
7. **Performance:** Show pagination in action, explain eager loading benefits

---

## 🎯 **Key Technologies Stack**

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12 |
| **Frontend** | Blade templates + Tailwind CSS + Alpine.js |
| **Admin** | Filament |
| **Database** | MySQL (local) / PostgreSQL (production) |
| **Storage** | Cloudflare R2 (S3-compatible) |
| **Real-time** | Pusher / Laravel Reverb |
| **Image Processing** | GD library + PHP |
| **Authentication** | Laravel Guard + Session |
| **Validation** | Laravel validation rules |

---

Generated for PFE Presentation - Last Updated: June 7, 2026

