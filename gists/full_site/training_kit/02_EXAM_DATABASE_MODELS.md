# ULTIMATE EXAM 1: Database Schema & Eloquent Models

*Instructions: Fill in the blank or answer the short technical questions based purely on the codebase provided in your gists. No guessing allowed! If you can answer these correctly, you fully understand how your data rests in the system.*

---

## Section A: Precise Column Syntax
1. In `database/migrations/2026_02_11_112818_create_produits_table.php`, what exact database rule is chained onto `categorie_id` to ensure that if an admin deletes a "Woodwork" category, the products inside it aren't violently deleted, but instead just lose their category tag?
   *Answer:* `->constrained('categories')->________OnDelete()`

2. In the `users` table, which default value string is explicitly applied to the `statut_compte` column?
   *Answer:* `default('________')`

3. To prevent a user from blocking the same person twice, what array syntax is used in the `blocked_users` migration?
   *Answer:* `$table->________(['blocker_id', 'blocked_id'])`

4. When saving the profile picture string to the DB, what method does `AuthController@store` call to squeeze the file size down before it enters the `pfp` column?
   *Answer:* `ImageHelper::________(...)`

5. Exactly how many decimal places does the `prix` column store according to this snippet: `decimal('prix', 12, 2)`?
   *Answer:* `________`

## Section B: Eloquent Models & "Casts"
6. In `Produit.php`, you have `$casts = ['images' => 'array']`. Why?
   *Answer:* The images column in MySQL is of type JSON. Eloquent will automatically `________` the JSON string back into a workable PHP array whenever you retrieve a product, saving you from doing `json_decode()` manually.

7. The `User` model declares: `protected $fillable = ['name', 'email', 'password', 'pfp', 'telephone', 'telephone_visible', 'ville_utilisateur', 'last_seen_at'];`
   Why is this array critical when updating a user?
   *Answer:* It prevents `Mass ________ vulnerabilities`, meaning a hacker cannot inject `'role' => 'admin'` into their profile update form and accidentally promote themselves.

8. Complete this Model code from `Conversation.php` connecting to products: 
   ```php 
   public function produit() { return $this->________(Produit::class); } 
   ```

9. You built a powerful Accessor in `User.php` for getting secure profile pictures:
   ```php 
   public function getPfpUrlAttribute() { return \App\Helpers\ImageHelper::getUrl($this->________); } 
   ```

10. Look at the `favoris` relationship in `User.php`. What Eloquent chaining method allows the system to remember *when* a user favorited an item?
    *Answer:* `->with________()`

## Section C: Architecture Level Deep Dive
11. The `Produit` model specifies `protected $casts = ['sponsored_until' => 'datetime']`. Because of this, when you write `if ($produit->sponsored_until->isPast())`, you are harnessing the raw power of the underlying PHP time library named `________` (which Laravel extends).

12. In the `DatabaseSeeder.php`, how do you immediately stop local development from creating an empty database, and correctly load the Moroccan cities list script?
    *Answer:* `$this->________([VilleSeeder::class]);`

13. For user performance checking, where is the `last_seen_at` column updated to ensure `true` live monitoring without heavy DB load?
    *Answer:* It's intercepted via the `Update________` HTTP Middleware file.
    
---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. null
2. actif
3. unique
4. compressAndStore
5. 2
6. cast (or deserialize)
7. Assignment
8. belongsTo
9. pfp
10. Timestamps
11. Carbon
12. call
13. LastSeen
