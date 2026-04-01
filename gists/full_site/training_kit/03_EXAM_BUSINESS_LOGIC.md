# ULTIMATE EXAM 2: Business Logic & Controllers

*Instructions: This exam tests how your application physically moves data around when a user clicks a button. These questions verify your mastery over Controllers, Authentication, and the Javascript Auto-Polling logic.*

---

## Section A: The Marketplace Engine
1. In `ProduitController@store`, exactly what PHP array function is used to combine the safe `$validated` data with the generated `$paths` images array and the user ID before inserting it into the database?
   *Answer:* `array_________`

2. When a user navigates to the homepage (`HomeController@index`), your system filters products so that *only* items that have been reviewed by a human are shown. Write the exact string value for `etat_moderation` that it checks for.
   *Answer:* `'________'`

3. If someone tries to submit a product, and the category ID they chose doesn't actually exist in the DB, Laravel's string validation stops it. What is the exact string rule?
   *Answer:* `'categorie' => 'required|________:categories,id'`

4. How do you ensure users can't artificially inflate the "Favorites" count on their own products?
   *Answer:* In `toggleFavorite()`, you wrote code: `if ($produit->vendeur_id === auth()->id()) { return ________(); }`

5. When an artisanal seller clicks "Demander une mise en avant" (Sponsor), the `demanderSponsor(Produit $produit)` controller immediately updates `sponsor_status` to `'________'`.

## Section B: Auto-Polling & Javascript (Message.blade.php)
6. Because WebSockets might fail on shared hosting, the system Auto-Polls messages using Alpine.js. What is the built-in JavaScript function you used to trigger a server check every 3 seconds?
   *Answer:* `________Interval`

7. True or False: If the server takes a long time to respond, the `await axios.get` request in `message.blade.php` will pause the entire browser tab, freezing the whole UI.
   *Answer:* False. It runs asynchronously because the function is marked as `________ () => { ... }`

8. You wrote an optimistic UI update directly into Alpine JS `sendMessage`. When the user clicks send, the javascript creates a `tempMessage` and assigns what function to dynamically fetch the system time?
   *Answer:* `new Date().toLocale________String(...)`

9. In the left-hand sidebar displaying conversations, what happens when `<template x-if="conv.unread_count > 0">` is triggered?
   *Answer:* A tiny orange notification ________ pops up displaying exactly how many unread messages exist for that specific chat thread.

## Section C: Auth & Dashboards
10. In `AuthController@store`, when a brand new user registers, what is the exact method signature chained onto the Auth facade to immediately sign them in without asking them to type their password again?
    *Answer:* `Auth::________($user)`

11. Why do we hash passwords using `Hash::make()` instead of leaving them as plain text?
    *Answer:* So if the database is ever stolen by a hacker, they cannot read the original ________.

12. In the `DashController@updateProfil`, you check if a new password string was submitted before changing it using this specific request method:
    *Answer:* `if ($request->________('password')) { ... }`

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. merge
2. approuve
3. exists
4. back
5. en_attente
6. set
7. async
8. Time
9. badge
10. login
11. passwords (or string)
12. filled
