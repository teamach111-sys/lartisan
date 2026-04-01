# ULTIMATE EXAM 3: Infrastructure, Admin, & Storage Logic 

*Instructions: This is the hardest exam yet. It tests your knowledge of how the Laravel Cloud server communicates with Amazon S3 / Cloudflare R2, and how the TALL-Stack Filament administration panel allows you to govern the marketplace.*

---

## Section A: Advanced Storage Architecture (AWS / S3 / R2)
1. The `config/filesystems.php` file defines a custom disk named `'lartisan'`. What specific `'driver'` does it use to communicate universally with Amazon S3 or Cloudflare R2?
   *Answer:* `________`

2. When using Filament's image uploader, uploading directly to the cloud might time out if the internet is slow. To prevent massive headaches, your `AppServiceProvider` overrides the `livewire.temporary_file_upload.disk` environment variable, telling Filament to stash files on the `________` disk before throwing them to the cloud.

3. Complete this line of code. If your `ImageHelper` fails to load a user's local profile picture via standard PHP `try {}`, the `catch (\Exception $e)` block automatically creates an AWS signed url. 
   What limits how long this link is valid for?
   *Answer:* `return $disk->temporaryUrl($path, ________()->addHours(24));`

4. When Filament's javascript queries an image preview, the browser will throw a fatal Cross-Origin Resource Sharing error (CORS) because your domain name doesn't match `aws.s3.com`. 
   To hack around this, you built the `________Controller`, which takes the AWS image and streams it safely through your own domain.

5. In that Storage proxy controller, if the `Storage::disk(..)->exists($path)` method returns `false`, what exact Laravel helper function instantly kills the script and shows a user-friendly 404 page?
   *Answer:* `________(404)`

## Section B: Filament Admin Governance
6. You customized `ProduitResource.php` to include an action called "Sponsoriser". Besides setting `is_sponsored = true`, what specific timestamp logic does it inject into the Database so the sponsorship expires automatically next week?
   *Answer:* `now()->________(7)`

7. To track bad behavior, users can click "Signaler". The Filament Admin queue for inspecting these warnings is built inside the `________ProduitResource.php` file. 

8. Inside `SiteSettingResource.php`, you built an interactive form to quickly throw the website into Maintenance Mode. What specific Filament `Forms\Components\________` did you use? (Hint: It acts like an On/Off switch).
   *Answer:* `________`

9. In `UserResource.php`, an admin changes a DropDown from "actif" to "suspendu". Since this governs whether they can log in, what is the exact string name of the database column being updated?
   *Answer:* `________`

10. The `StatsOverview.php` runs a real-time widget on your admin dashboard showing products awaiting approval. It counts them by querying `Produit::where('etat_moderation', 'en_attente')->________()`.


---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. s3
2. local
3. now
4. StorageProxy
5. abort
6. addDays
7. Signalement
8. Toggle
9. statut_compte
10. count
