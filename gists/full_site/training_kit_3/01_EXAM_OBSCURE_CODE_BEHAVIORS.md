# TRAINING KIT 3 - EXAM 1: Obscure Code Behaviors

*Instructions: We are officially scraping the absolute bottom of the barrel. These questions test tiny edge-cases and exact variable names that only the original author would know... or someone who studied the codebase like a maniac.*

---

## Section A: Deep Controllers & Routing
1. In `ProduitController@store`, when the system redirects the user back to `/annonces`, what exact text message is flashed to the session in the green notification bubble?
   *Answer:* `'________ !'`

2. When generating the `/` homepage in `HomeController@index`, you check if a user clicked a category filter using `if ($request->has('cat'))`. To prevent SQL hacking, you forcefully cast their input into an integer before querying the DB. Write the exact PHP syntax used to cast it:
   *Answer:* `(________) $request->cat`

3. In `ProduitController@toggleFavorite(Produit $produit)`, if the seller tries to favorite their *own* item, the server simply aborts and returns to the previous page. What Laravel helper does it use to go back?
   *Answer:* `return ________();`

4. When fetching message history in `message.blade.php`, the `fetchMessages` function wraps its `axios.get` call inside a `________` / `catch` block so a bad network drop doesn't throw a red Javascript console error.

## Section B: Tiny Database Rules
5. When creating the `users` table via Migrations, you restricted the `telephone` string column so users couldn't upload a 5,000 character block of text. What is the maximum character length you enforced?
   *Answer:* `________` characters.

6. Same as above, but for the `ville_utilisateur` (City) column. You restricted the maximum string length of the city name to:
   *Answer:* `________` characters.

7. The `produits` table requires an automatic ordering system so newest items hit the front page first. You achieved this via `$table->________()`, which automatically generates the invisible `created_at` and `updated_at` timestamps for every row.

8. In modern Laravel 11, calling `$table->id()` automatically creates what *type* of primary key under the hood in MySQL? 
   *Answer:* An unsigned `Big________`.

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. Annonce publiée
2. int
3. back
4. try
5. 20
6. 100
7. timestamps
8. Integer
