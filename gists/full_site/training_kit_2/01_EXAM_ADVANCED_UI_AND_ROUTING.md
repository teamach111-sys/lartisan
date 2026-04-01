# TRAINING KIT 2 - EXAM 1: Advanced UI, Compile Configs, & Blade Magic

*Instructions: Let's see if you really mastered the frontend logic. These questions dig deeper into the invisible components that build the user interface, no repeats from Kit 1!*

---

## Section A: Vite & Asset Compilation
1. In `vite.config.js`, you pass an array to `laravel-vite-plugin` containing your css and js files. What `boolean` option is set to automatically reload your browser window the second you hit save in a Blade file?
   *Answer:* `________: true`

2. If a user logs in and the font doesn't load correctly, they will see a system default font. What is the custom font family explicitly declared in `layout.blade.php` at the very top using `@font-face`?
   *Answer:* `________`

3. In `resources/css/app.css`, you have a critical CSS rule: `[________] { display: none !important; }`. This prevents Alpine.js interfaces (like chat dropdowns) from terrifyingly flickering on the screen for a split second before the javascript engine loads.

4. In `app.js`, after compiling Alpine, you make the custom image compressor globally accessible to all blade views anywhere on the site using the command `________.ImageCompressor = ImageCompressor;`.

## Section B: Pure Blade Logic & Tailwind
5. In your global wrapper file, `layout.blade.php`, the main site is constrained to a maximum width to prevent ultra-wide 4K monitors from stretching the site awkwardly. What Tailwind CSS class enforces this?
   *Answer:* `max-w-[________px]`

6. In the `layoutdash.blade.php`, you inject an invisible image squasher utility at the very bottom of the `<body>` so users can compress images locally before hitting the server. What is the exact Blade component syntax for this?
   *Answer:* `<x-________ />`

7. In `HomeController@index`, instead of returning `$produits->get()`, you return `$produits->________(24)`, which tells the view to automatically generate "Next/Previous" buttons and only show 24 crafts per page.

8. When an artisan manages their own listings, they are redirected using a flash message: `redirect()->route('annonces')->with('________', 'Annonce publiée !');`. This specific session key is caught in the Blade view to show a green (not red) toast notification.

## Section C: Advanced Alpine.js State
9. In `message.blade.php`, the Alpine script has to detect if the user is texting from a phone so it knows whether to render the chat "Full Screen". What Javascript `window` property determines the initial boolean value of `isMobile`?
   *Answer:* `window.inner________ < 768`

10. When the UI fetches conversations, if the server returns data, Alpine stores the array of user chats safely in the object property named `________`.

11. To prevent Alpine.js from fetching old messaging data, `startPolling()` specifically uses `axios.get(________)` which automatically negotiates headers asynchronously.

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. refresh
2. mabrypro
3. x-cloak
4. window
5. 1800
6. image-compressor
7. paginate
8. success
9. Width
10. conversations
11. url / endpoint (or `/api/conversations/...`)
