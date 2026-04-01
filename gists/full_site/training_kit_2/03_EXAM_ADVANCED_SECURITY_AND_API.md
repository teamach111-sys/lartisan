# TRAINING KIT 2 - EXAM 3: Advanced APIs, Security & Admin Deep-Cuts

*Instructions: This is the final and trickiest test of your custom marketplace implementation. Can you perfectly trace secure file-streams and obscure Filament settings?*

---

## Section A: Advanced Infrastructure & Security
1. If the `ImageHelper` needs to load the `'default.svg'` placeholder image for a brand new user who didn't upload a profile picture, it completely bypasses S3 logic and instantly routes to the public folder using the Laravel helper `________()`.

2. To verify the true identity of a stream of data hitting your `StorageProxyController`, you echo the raw bytes but properly assign the specific file header using the method: `$disk->________($path)`.

3. Security dictates that your environment file MUST load TLS for emailing customers cleanly from Laravel Cloud. What `.env` variable controls this?
   *Answer:* `MAIL_________`

4. When Cloudflare R2 is configured as the filesystem, it acts mostly like S3, but differs in one major trait: it requires a custom URL entry point. This is managed under the `s3` config block using the key: `'________' => env('AWS_ENDPOINT')`.

## Section B: Chat Interactions & API
5. Your Alpine UI doesn't just ask Laravel for new text strings. It also asks if the user at the other end has physically blocked you! It securely fetches this block-state by storing two backend JSON boolean keys: `is_blocked` and `________`.

6. To immediately stop harassment, a user clicks "Block". This hits the `toggleBlock` method. To send the response directly back to the Javascript UI to lock the text box, Laravel doesn't return a view, but instead returns: `response()->________([...])`.

7. In `chat-content.blade.php`, Alpine instantly detects if a text message was sent by *you* or *them*. If it's your message, it pushes the balloon to the extreme right side of the screen using the Tailwind CSS code: `flex-row-________`.

8. If a user maliciously types `myId = 999` into their browser console to try and hijack a different user's chat thread, the backend blocks it because `produits` automatically ties an insertion to the strict session-based helper: `________()->id()`.

## Section C: Filament Data-Tables  & Analytics
9. The `UserResource.php` lists all platform users with an elegant colored tag based on if they are an "admin", "moderator", or "utilisateur". What specific Filament column method generates this little colored pill?
   *Answer:* `________()`

10. Look at `StatsOverview.php`. When generating the dashboard "Signalements" (Reports) counter, it only wants to display recent issues from the last 24 hours. How do you query this accurately against the timestamp column?
    *Answer:* `where('created_at', '>', now()->________())`

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. asset
2. mimeType
3. ENCRYPTION
4. endpoint
5. blocked_by
6. json
7. reverse
8. auth
9. badge
10. subDay
