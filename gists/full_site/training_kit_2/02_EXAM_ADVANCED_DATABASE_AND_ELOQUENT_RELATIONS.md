# TRAINING KIT 2 - EXAM 2: Ultra-Deep Database Schema & Eloquent 

*Instructions: This exam tests the darkest depths of your MySQL tables and Laravel Models. No repeated questions from Kit 1. Let's see if you can trace relationships like a Database Administrator.*

---

## Section A: Advanced Migrations & Syntax
1. When you create the `users` table, Laravel gives it an automatically incrementing primary key using `$table->id()`. However, the authentication system needs a way to bypass login pages if the user clicked "Remember Me". What specific blueprint method creates the invisible column for this?
   *Answer:* `$table->________Token()`

2. In the `produits` table, what is the *exact* default status string assigned to `etat_produit` when a seller posts a new item without specifying its condition?
   *Answer:* `'________'`

3. If an artisan deletes their account completely from the `users` table, all of their products vanish with them because of the `cascadeOnDelete()` rule. However, what happens to the foreign key `categorie_id` on those products if a specific category is deleted by an admin instead? Look closely at your `produits` schema.
   *Answer:* It uses `________OnDelete()`.

4. The platform includes read receipts! In the `conversations` table, a `timestamp` column triggers whenever someone sends a new text message. This speeds up the sorting of your inbox. What is it named?
   *Answer:* `________`

5. Every time you create a custom category in the `categories` schema, you must also provide a FontAwesome (or similar) string for its visual appearance, which is saved in the `________` column.

## Section B: Obscure Eloquent Secrets
6. You named your core models `User`, `Produit`, and `Message`. But `User` isn't just an `extends Model` class. It specifically extends `________` to gain native Laravel login capabilities.

7. The `User` model also utilizes traits. Which trait specifically allows the `User` to safely use the `Hash` facade and receive "Forgot Password" emails?
   *Answer:* `use ________;`

8. A standard `belongsToMany` relationship between Users and Produits representing their wishlist uses a Pivot Table. Laravel will assume the table is named `produit_user` by default. But you overwrote this! What is the exact string name of the pivot table you provided?
   *Answer:* `'________'`

9. The `User` model integrates with Filament's security constraints directly. It does this by physically implementing an interface called `________User`.

10. The `Produit` model relies on `protected $fillable` arrays. But a database error will lock up the whole site if it ever tries to save the exact time a product sponsor expires unless you cast it back to a valid php time string using: `'sponsored_until' => '________'`

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. remember
2. neuf
3. null
4. last_message_at
5. icon
6. Authenticatable
7. Notifiable
8. favoris
9. Filament
10. datetime
