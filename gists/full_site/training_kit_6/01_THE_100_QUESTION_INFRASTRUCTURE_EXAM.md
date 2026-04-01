# TRAINING KIT 6: THE 100-QUESTION INFRASTRUCTURE GAUNTLET

You conquered the Data Flow! Now, let's test your knowledge of the physical servers, environment configurations, and network architecture that keeps Laravel alive. 

*Answers are at the bottom.*

---

## Phase 1: Environment & Configuration
1. The master configuration file where you store passwords and secrets for Laravel is completely invisible to git because it is named `.________`.
2. When deploying to production, your `APP_ENV` variable should be changed from `local` to `________`.
3. If users complain about a massive red error screen showing your physical code on the live site, you forgot to set `APP_DEBUG` to `________`.
4. Your `APP_URL` variable is critical. If your site is accessed over HTTPS, it must literally include `https://________` otherwise signed AWS URLs will generate broken HTTP links.
5. In your custom S3 setup, the variable `FILESYSTEM_DISK` is read by Laravel using the `________('FILESYSTEM_DISK')` helper in the config file.
6. The `config/` directory contains files that return PHP arrays. These arrays are cached in memory for speed. After editing `filesystems.php`, you must run `php artisan config:________` to rebuild it.
7. To talk to your MySQL database on Laravel Cloud, you configure the connection type in the `.env` using `DB_________=mysql`.
8. The database host address is stored in `DB_________`.
9. The exact string name of your database goes into `DB_________`.
10. To encrypt sessions securely, Laravel generates a random 32-character base64 string and stores it in `APP_________`.
11. If you need to send welcome emails securely from the marketplace, the `MAIL_ENCRYPTION` variable must be set to `________`.
12. The `config/filesystems.php` file defines a custom disk specifically for your marketplace named `'________'`.
13. That same custom disk is routed through the generic `'________' => 's3'` cloud driver under the hood.
14. Cloudflare R2 requires a custom entry-point URL beyond basic AWS S3. This goes into the `.env` variable `AWS_________`.
15. Your real-time chat variables start with `VITE_`. This tells Laravel's asset compiler to automatically inject them into your compiled `________` code.
16. The `VITE_PUSHER_APP_KEY` allows your frontend to securely communicate with the ________ server.
17. The cluster location of your pusher server, like 'eu' or 'mt1', goes in `VITE_PUSHER_APP_________`.
18. Laravel caches routes into a single massive file for speed. You build this cache using `php artisan ________:cache`.
19. If you edit a Blade view in production and it doesn't immediately reflect on the website, you should run `php artisan ________:clear`.
20. In the `filesystems.php` file, the fallback default disk if `FILESYSTEM_DISK` is missing is set via `config('filesystems.default', '________')`.

---

## Phase 2: File Systems, Storage, & S3 Proxies
21. Local images in Laravel are saved in `storage/app/public`. To make them visible to the internet, you run `php artisan storage:________`.
22. The command above creates a `________` link from the `/public` folder directly into the `/storage` folder.
23. If an S3 image needs to bypass Amazon's domain blocks, it's because modern web browsers enforce strict `________` security policies.
24. FilePond uploads directly to S3 crash due to CORS. To repair this, you built the `________Controller`.
25. The proxy works because it physically fetches the file into your server's RAM via `$disk->________($path)`.
26. It then outputs the raw bytecode back to the browser via `response()->________(...)`.
27. When streaming data out, the HTTP status code representing success is `________`.
28. To ensure the browser knows the proxy string is a picture and not a PDF, you pass an array defining the `________-Type` header.
29. To determine the file's type automatically without guessing, you ask the cloud disk directly using `$disk->________($path)`.
30. To safely protect the proxy from hackers crashing the server by guessing bad image names, line 1 of the controller checks if the image exists using `$disk->________($path)`.
31. If the hacker guessed wrong, the proxy immediately terminates HTTP logic and sends a `________` (Not Found) error using the `abort()` helper.
32. The `AppServiceProvider` overrides the Filament upload disk using the core system array mapping helper `config(['livewire.temporary_file_upload.disk' => '________']);`.
33. If an image is stored in `storage/app/public/produits`, you can generate an absolute web URL for it instantly using the `________()` helper in Blade.
34. The `ImageHelper` is a static class. When catching an Amazon S3 failure, it falls back to creating an expiring link via `$disk->________Url(...)`.
35. The expiration date you passed specifies that the temporary URL dies exactly 24 hours from `________()`.
36. Laravel defaults to uploading files to the local hard drive array key named `'________'`.
37. If you decide to move your `$paths` image logic out of the controller to process it in the background via jobs, you must ensure you have a running `________` worker on your server.
38. Your database column stores image strings in a standard text format. When using `$casts = ['images' => 'array']`, Laravel intercepts standard `________` encoding and decoding.
39. The Laravel `Storage` facade abstracts away all differences between local hard drives and cloud drives using a pattern called `________`.
40. Cloudflare R2 is preferred for your image proxy over raw AWS S3 because R2 has zero bandwidth ________ fees.

---

## Phase 3: Servers, Nginx, & PHP Configs
41. When deploying Laravel to a Linux server, the raw entry point for every single web request is the `/public/________.php` file.
42. A typical server runs `nginx` or `apache` to catch incoming web traffic on port `________` (for HTTP) or 443 (for HTTPS).
43. If a user uploads a 10MB image and Laravel instantly crashes, you must edit your server's `________.ini` configuration file.
44. The specific raw configuration directive that limits how big a POST request can be is called `post_max_________`.
45. The specific directive limiting single file uploads is `_________max_filesize`.
46. If your image compression logic (`ImageHelper::compressAndStore`) takes 40 seconds to shrink five 4K images, the server will crash if `max_execution_________` is set to default (30s).
47. If the Image compression crashes with "Allowed memory size exhausted", you must increase the server's `_________limit`.
48. Laravel uses a hidden file in your public folder to route all web traffic flawlessly into the `index.php` file without showing `.php` extensions in the URL. It is named `.________`.
49. The `.htaccess` file achieves clean URLs by passing requests through the Apache module called `mod_________`.
50. PHP code is compiled and stored in server memory for ultra-fast execution if your server has the `Zend ________` module enabled.
51. If you run `php artisan serve` locally, you are utilizing PHP's built-in, single-threaded development server running on port `________`.
52. Laravel Cloud hosts your site on a serverless or highly optimized Linux environment, meaning it automatically restarts the `PHP-________` service if configurations change.
53. When a MySQL database grows massive, text searches (like the `like '%Miel%'` query) become slow. To scale, you must add a DB `________` to the `titre` column.
54. The folder `/storage/logs/` is the only folder Laravel regularly writes to. During deployment, the Linux server user (like `www-data`) MUST have `write` ________ on this folder.
55. If permissions are broken on `/storage`, Laravel will show an error starting with "The stream or file could not be ________".
56. Your `composer.json` tracks PHP packages. When setting up a new server, you download them instantly using `composer ________`.
57. In production, you add `--no-________` and `--optimize-autoloader` to Composer to ensure debugging code isn't deployed and files load instantly.
58. When running your MySQL database migrations on a fresh live server, you type `php artisan ________`.
59. Sometimes you need to forcefully drop and rebuild every database table on your dev machine. You do this with `php artisan migrate:________`.
60. The `.gitignore` file stops sensitive data from being uploaded to GitHub, automatically ignoring the `________/` folder so you don't upload 40,000 package dependencies.

---

## Phase 4: Middleware, Architecture, & Filament
61. Your custom `UpdateLastSeen` code acts as a layer that intercepts incoming HTTP requests before they hit controllers. This layer is called `________`.
62. Middleware must always end by passing the request deeper into the application via `return $________($request);`.
63. To check if the user is physically logged in within your middleware, you call `auth()->________()`.
64. Laravel provides security middleware by default. It automatically verifies that any POST form submission contains a secret `________` token to prevent Cross-Site Forgery.
65. In Blade templates, you inject that secret security token into `<form>` tags easily by typing `@________`.
66. Filament operates heavily on `Livewire`. Livewire allows you to write interactive code purely in PHP, and automatically transpiles it into `________` XHR requests.
67. In `StatsOverview.php`, you fetch data. Widget statistics in Filament act dynamically to refresh automatically on an interval by default. This interval is managed via `________` requests behind the scenes.
68. If a user is caught hacking the site, an admin clicks "Suspendu" on the Dropdown in Filament's `UserResource.php`. This saves instantly to `statut_compte` without clicking "Submit" because the Livewire component is universally `________`.
69. Filament is able to parse your database schemas so beautifully because you run `php artisan filament:________` which reads the database and auto-templates the UI.
70. Global utility variables shared via `AppServiceProvider.php` are intercepted specifically in the `________()` method where all services are registered.
71. The Laravel architecture uses the `Illuminte\Support\Facades\________` class in `AppServiceProvider` to push variables directly to Blade templates using the `composer('*', ...)` method.
72. To restrict areas to users vs. admins, Laravel relies heavily on Authorization `________` or Policies.
73. You implemented `FilamentUser` on the User model. This forces you to add a specific method named `canAccess________()` so Filament knows if they can log in to `/admin`.
74. The `App` folder falls under the `App\` namespace, automatically autoloaded using the `________` standard defined in your composer file.
75. If you change a filename without running `composer dump-autoload`, the server will throw a massive `Class not ________` error.
76. In `SiteSettingResource.php`, you used a `Toggle` component for Maintenance Mode. If true, Laravel can be manually thrown into maintenance via terminal using `php artisan ________`.
77. And to bring the server back online manually from the terminal, you type `php artisan ________`.
78. Behind the scenes, maintenance mode puts a `503 Service ________` file into the storage folder to block web requests universally.
79. The `UserResource` uses badges to color code the "role". If the role is "admin", Filament uses an array or `match()` statement to output a `________` HTML/CSS styling pill.
80. Filament requires Tailwind CSS v3 internally (historically), but your main app runs Tailwind v4. They don't conflict because Filament's CSS is completely compiled and isolated in the `________/filament` directory.

---

## Phase 5: Vite, Alpine, and WebSockets (The Frontend Stack)
81. `vite.config.js` is the brains of your asset compilation. Vite is drastically faster than Webpack because it utilizes native ES `________` in the browser during dev mode.
82. When you run `npm run ________`, Vite creates a hot-reloading server that injects CSS modifications directly into the browser without refreshing the page.
83. When deploying to production, you run `npm run ________` to package all CSS/JS into highly compressed, minified files with version hashes attached.
84. Look at `message.blade.php`. Because WebSockets might fail, you have an alternative Javascript method to contact the server repeatedly using `setInterval`. This fallback architecture is called Auto-`________`.
85. The Javascript library utilized to make the HTTP requests to Laravel securely is `________`.
86. When Axios makes a request to a Laravel API route, it automatically attaches the `X-XSRF-________` cookie to guarantee the request originated from your website safely.
87. If you *do* get WebSockets working on production, Laravel `________` acts as the Javascript listener wrapping Pusher.
88. Websockets allow immediate state sync. When `MessageController` saves to the DB, it uses the `________()` helper to fling the event object to the websocket server instantly.
89. The exact event generated is an object of the `\App\Events\________Sent` class.
90. In `message.blade.php`, Alpine.js is booted inside a `<script>` tag. To delay execution until Alpine is completely loaded in the browser, you attach it to the event `document.addEventListener('alpine:________', ...)`
91. The `messaging` Alpine component receives dynamic PHP data securely using the syntax `authUser: @________($auth_user)`.
92. The syntax above converts a PHP Eloquent Model object safely into a functional JSON `________` readable by javascript.
93. In Alpine, you use `x-________="msg in messages"` to loop through array arrays in pure HTML.
94. Alpine handles the `x-teleport` of the mobile chat box by injecting it into a completely different node of the `________` (Document Object Model).
95. When setting up Laravel Reverb (the native websocket system), you start the web socket server physically on the command line using `php artisan ________:start`.
96. WebSockets operate over an upgraded persistent connection using the `ws://` or secure `________://` protocol.
97. If a user loses internet, Echo goes dark. Auto-polling, however, will patiently continue firing Axios requests every 3000ms until a request completes without throwing a `________` Network Error.
98. When generating your mobile application CSS, `[x-cloak]` is crucial because it takes Vite CSS several `________` (thousandths of a second) to paint the screen black, and without cloak, raw ugly HTML drops visually.
99. The custom font `mabrypro` is defined using `@font-face` in `layout.blade.php`, relying on a file saved physically in the `public/________/` directory.
100. Congratulations! You are now a senior DevOps and Full-Stack Laravel Architect. To confirm you read this far: Your entire ecosystem revolves around one core PHP server language, built natively upon the `________` framework!

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

---
### The Ultimate Master Key (Answers)

1. env
2. production
3. false
4. lartisan.ma
5. env
6. cache
7. CONNECTION
8. HOST
9. DATABASE
10. KEY
11. tls
12. lartisan
13. driver
14. ENDPOINT
15. Javascript
16. Websocket (or Pusher)
17. CLUSTER
18. route
19. view
20. public
21. link
22. symbolic (or symlink)
23. CORS
24. StorageProxy
25. get
26. stream
27. 200
28. Content
29. mimeType
30. exists
31. 404
32. local
33. asset
34. temporary
35. now
36. public
37. queue
38. JSON
39. Filesystems (or Factory/Manager)
40. egress (or out)
41. index
42. 80
43. php
44. size
45. upload
46. time
47. memory
48. htaccess
49. rewrite
50. OPcache
51. 8000
52. FPM
53. index
54. permissions
55. opened
56. install
57. dev
58. migrate
59. fresh
60. vendor
61. Middleware
62. next
63. check
64. CSRF
65. csrf
66. AJAX / Javascript
67. Polling
68. reactive
69. resource
70. boot
71. View
72. Gates
73. Panel
74. PSR-4
75. found
76. down
77. up
78. Unavailable
79. Tailwind (or visual)
80. vendor
81. Modules
82. dev
83. build
84. Polling
85. Axios
86. TOKEN
87. Echo
88. broadcast
89. Message
90. init
91. json
92. String (or Object)
93. for
94. DOM
95. reverb
96. wss
97. timeout / 5xx
98. milliseconds
99. fonts
100. Laravel
