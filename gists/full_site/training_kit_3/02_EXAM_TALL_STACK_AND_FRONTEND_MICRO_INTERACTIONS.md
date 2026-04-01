# TRAINING KIT 3 - EXAM 2: TALL Stack & UI Micro-Interactions

*Instructions: We are testing your knowledge of Alpine UI tricks, specific Filament interface rendering options, and Tailwind utility classes.*

---

## Section A: Alpine.js "Magic"
1. In your `message.blade.php`, you have a chat dialog popup that takes over the whole screen on mobile devices. Because it sits inside a flex container, it might get cut off or behave weirdly. To fix this, you inject the entire mobile chat div directly into the `<body>` of the HTML using the Alpine directive:
   *Answer:* `<template x-________="body">`

2. When the mobile chat opens, it slides up smoothly from the bottom of the screen instead of instantly snapping into existence. Which specific animation class makes it start off-screen downward?
   *Answer:* `translate-y-________` (which gives it 100% downward translation).

3. When building the left-hand sidebar for conversations, the user can type in an input box connected to `x-model="searchQuery"`. The HTML `<template>` block then rerenders automatically because it loops over a dynamically generated javascript Array variable called:
   *Answer:* `filtered________`

4. True or False: If you delete a conversation by clicking the trash icon, the `axios.delete` API request waits for the server to reply *before* hiding the bubble from your UI, meaning it lags slightly.
   *Answer:* False. It utilizes ________ UI updates to remove it instantly, and only logs an error if the server rejects the request later.

## Section B: Admin Panel Micro-Elements
5. In your Filament `UserResource.php`, you didn't just render text for the `statut_compte` (which is typically 'actif' or 'suspendu'). You used a Dropdown so admins can click and change it on the fly. Which `SelectColumn` method created this dropdown within the table?
   *Answer:* `________()`

6. Under the `SignalementProduitResource`, you have a column named `details` that might contain massive multi-paragraph complaints from users. To prevent the table from becoming 3 pages tall per row, you cut it off using the method:
   *Answer:* `->________(20)`

7. Your Filament `StatsOverview` widget counts products via `Produit::count()`, but realistically it counts them wrapped inside what Filament metric builder method?
   *Answer:* `________::make('Produits en Attente', ...)`

8. You can't let a normal user accidentally visit your Filament dashboard. Filament enforces this internally because the `User` model implements the `________` Interface.

---
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

### The Master Key (Answers)
1. teleport
2. full
3. Conversations
4. Optimistic
5. options
6. limit
7. Stat
8. FilamentUser
