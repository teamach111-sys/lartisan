# TRAINING KIT 3 - EXAM 3: Laravel Internals & Security Traps

*Instructions: This verifies your understanding of how Laravel defends itself from attacks and how it structures raw HTTP responses when building custom APIs like the StorageProxy.*

---

## Section A: Advanced Server Communications 
1. In `StorageProxyController`, you successfully intercepted the S3 file to dodge the CORS error. However, when you stream the raw byte code back to the browser using `response()->stream()` you MUST tell the browser *what type* of file it is, otherwise it downloads as a random byte file instead of displaying as a picture. Which HTTP Header array key passes this metadata?
   *Answer:* `['________-Type' => $disk->mimeType($path)]`

2. Same file. A streaming response requires a standard HTTP Status Code to signify "Success/OK". Which 3-digit number do you explicitly pass to the `stream()` function?
   *Answer:* `________`

3. If an artisan uploads an image that is 40 Megabytes, but your PHP configuration only allows 2MB uploads, PHP will strip the file completely from the request. When `ProduitController@store` runs, the `required|array|size:5` rule will catch it, preventing the crash, and Laravel will kill the script throwing what precise type of error?
   *Answer:* `422 ________ Entity`

## Section B: Obscure Models & Factories
4. The `DatabaseSeeder.php` creates the Admin User using Laravel model factories. It invokes: `\App\Models\User::factory()->________([...])` to physically write it to the database table.

5. When a hacker attempts to brute force an empty `POST` request to `MessageController@sendMessage` without typing anything, the server doesn't crash on SQL execution. Why?
   *Answer:* Because the very first line is `$request->________(['contenu' => 'required|string']);`.

6. If an artisan edits their profile via `DashController@updateProfil`, they can change their phone number visibility. What is the exact line of code that intelligently binds a checkbox boolean directly to the User object?
   *Answer:* `$user->display_phone = $request->________('display_phone');`

7. True or False: If you leave `config/filesystems.php` without configuring your `.env` `FILESYSTEM_DISK`, the system will permanently crash when `ImageHelper` runs. 
   *Answer:* False. You gracefully wrote `config('filesystems.default', '________')` to catch it and fall back to local storage.

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. Content
2. 200
3. Unprocessable
4. create
5. validate
6. has
7. public
