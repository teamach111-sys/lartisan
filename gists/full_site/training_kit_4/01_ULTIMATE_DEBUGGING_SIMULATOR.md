# TRAINING KIT 4: THE ULTIMATE DEBUGGING SIMULATOR

Welcome to the Debugging Simulator. In the real world, code doesn't just fail with a clean error message; users complain about "white screens," "buttons that do nothing," and "images that don't load." 

This training kit will transform you from a developer into a forensic investigator. 

---

## PART 1: The Debugging Training Manual

Before taking the exam, you need to understand the tools at your disposal.

### 1. The "Network Tab" is Your X-Ray
When a user clicks "Send Message" in `message.blade.php`, the page doesn't reload. It uses `axios.post`. 
If it fails, Laravel will **not** show a red error screen. It will fail silently in the background.
**How to fix:**
1. Open Developer Tools (F12) -> Network Tab.
2. Click "Fetch/XHR" to filter only Javascript background requests.
3. Re-create the bug (Click "Send").
4. A red line will appear (e.g., `500 API/Conversations`). Click it, go to the **Response** tab, and read the raw Laravel error output.

### 2. The `laravel.log` Stack Trace
When you open `storage/logs/laravel.log`, look at the very bottom. You will see a "Stack Trace" that looks like a massive wall of text. 
**How to read it:**
- Ignore the bottom 90% of the trace. It's just Laravel core files.
- The **Top Line** tells you the error: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'is_admin' in 'where clause'`.
- Look for the first file path in the trace that says `app/Http/Controllers/...` or `resources/views/...`. That is **your** file where the bug originated.

### 3. Debugging Filament Uploads (The Cloudflare Trap)
Filament's FilePond component uploads files temporarily before submitting the final form. If this fails, it's almost always one of three reasons:
- **CORS Mismatch:** Your domain `https://lartisan.ma` isn't whitelisted in your Cloudflare R2 / AWS S3 CORS settings.
- **Disk Swap:** You forgot to force `livewire.temporary_file_upload.disk` to `local` in the `AppServiceProvider`.
- **File Size limit:** Your `php.ini` file's `upload_max_filesize` is set strictly to `2M`, but the user uploaded a `5M` photo from their iPhone.

---

## PART 2: The Debugging Exam (Scenario Simulator)

Read the following bug reports. Based on your knowledge of the L'Artisan codebase, determine the root cause or the exact file you need to open to fix it. Answers are at the bottom.

### Scenario 1: The Silent Chat
**Bug Report:** "I am chatting with a seller. I hit send. The message bubble appears on my screen instantly, but the seller replies on WhatsApp saying they never got it and it didn't save."
1. You open `message.blade.php`. You see that when they hit send, `this.messages.push(tempMessage)` executes immediately. What kind of UI update is this called, which explains why the user saw it on their screen even though it failed to save?
2. If it didn't save, you press F12 and look at the Network tab. The `axios.post` request returned a `422` error. Knowing `MessageController@sendMessage` starts with `$request->validate(['contenu' => 'required|string']);`, what did the user likely do to trigger this?

### Scenario 2: The Infinite Spinner
**Bug Report:** "When I try to upload exactly 5 images to create a new product, the website loads for 30 seconds and then gives me a '504 Gateway Timeout' or a raw Nginx white screen."
3. You check your logs. The log says `Allowed memory size of 134217728 bytes exhausted`. In `ProduitController@store`, what specific custom helper method is running inside a `foreach` loop 5 times, likely causing the server's RAM to max out if the images are gigantic 4K resolution?
4. How could you solve this permanently without increasing RAM? (Hint: Does the user need to upload 8MB files, or can you squash them in the browser *before* sending via the `<x-________ />` blade component?)

### Scenario 3: The Ghost Images
**Bug Report:** "Images load perfectly on my local XAMPP computer, but broken image boxes appear on production."
5. You open `ImageHelper.php`. If `Storage::disk('lartisan')` throws an exception, it falls back to `$disk->temporaryUrl(...)` for AWS. What `.env` variable must be perfectly matching your domain for signed AWS URLs to generate correctly?
6. If the image is entirely missing from the database (the seller bypassed validation somehow), the helper is supposed to inject `'default.svg'`. What helper function converts that string into a physical URL path for the `public` folder?

### Scenario 4: The Admin Panel Crash
**Bug Report:** "I go to `/admin` and click 'Utilisateurs'. The page crashes with `Method Illuminate\Database\Eloquent\Collection::badge does not exist`."
7. You open `UserResource.php`. A developer tried to call `->badge()` on the `role` column. What specific Filament visual component class must the column be wrapped in for `->badge()` to work? (Hint: It is text). `Tables\Columns\________Column::make('role')->badge()`.

### Scenario 5: The Sneaky Hacker
**Bug Report:** "An artisan realized they could bypass the '5 images required' limit by intercepting the HTML form and deleting one of the file inputs."
8. Fortunately, your backend blocked them. What specific Laravel validation rule in `ProduitController` triggered the block by checking array length?
9. When blocked, Laravel redirects them back to the form with a `MessageBag` of errors. Under the hood, this throws an `Illuminate\Validation\________Exception`.

### Scenario 6: The Database Lock
**Bug Report:** "I can't delete a specific user! It throws an SQL constraint error."
10. The user posted 5 products. In the DB Migrations, if you forgot to add `________OnDelete()` to the `vendeur_id` foreign key in the `produits` table, MySQL will refuse to delete the user because their products would be left as "orphans".

### Scenario 7: The Maintenance Panic
**Bug Report:** "The site is under cyber-attack. I need to take it offline immediately, but my developer is asleep."
11. You log into the Filament panel and go to the custom settings page defined in `SiteSettingResource.php`. You use the `Toggle` component to turn on site-wide maintenance mode. To make this strictly enforce downtime, this setting is likely checked inside a global `________` (a piece of code that intercepts every single HTTP request before the controllers load).

### Scenario 8: The Polling Avalanche
**Bug Report:** "A user left their laptop open on the chat page overnight. My server crashed because it ran out of database connections."
12. Your Auto-Polling script runs `setInterval` every 3000ms. Over 8 hours, that is 9,600 API calls per open tab. To fix this, you edit `message.blade.php`. You can detect if the browser tab is hidden using the Javascript API `document.________State`. You should pause the interval if it equals `'hidden'`.

<br><br><br><br><br><br><br><br><br><br><br><br><br><br>

---

### The Master Key (Answers)
*Score yourself out of 12. If you score 10+, you are ready for production.*

1. Optimistic UI updating.
2. They tried to send an empty message (just a space) or completely bypassed the HTML input box, failing the `required` rule.
3. `ImageHelper::compressAndStore()`
4. `<x-image-compressor />`
5. `APP_URL`
6. `asset()`
7. `Text` (`TextColumn`)
8. `size:5`
9. `Validation`
10. `cascade`
11. Middleware
12. `visibility` (or `visibilityState`)
