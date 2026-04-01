# TRAINING KIT 5: THE 100-QUESTION DATA FLOW GAUNTLET

You asked for a mountain of questions testing the exact flow of data through your application. While 1,000 questions would literally crash your text editor with file sizes, I have engineered **The 100-Question Gauntlet**. 

This is the ultimate test of your brain's ability to trace a piece of data from the moment a user clicks a button, through the HTML, into the route, parsed by the controller, validated, saved in MySQL, and returned back to the screen. 

*Answers are at the extreme bottom.*

---

## Phase 1: The Authentication Flow
*A user clicks "Register" and fills out their profile.*

1. The HTML form sends a `________` HTTP request to the `/register` route.
2. The route points to the `store` method inside the `________Controller.php` file.
3. Before checking passwords, the controller runs `$request->________([...])` to ensure the email is unique and valid.
4. The password string "secret123" is immediately passed through the `________::make()` facade to encrypt it before saving.
5. If the user uploaded a profile picture, the logic hits: `if ($request->________('pfp'))`.
6. The picture file is intercepted by the `________::compressAndStore()` helper method.
7. Inside that helper, the image is physically written to the server's hard drive inside the `________` directory string you passed to it.
8. The database `users` table auto-generates an ID using the `$table->________()` method.
9. Upon successful database insertion, the session is manually authorized using `Auth::________($user)`.
10. The controller executes a `redirect('/')->with('________', 'Bienvenue !')`.
11. The browser reads the redirection headers and sends a `________` HTTP request to the `/` homepage.
12. The `layout.blade.php` file detects the flashed session variable and renders a green `________` component to show the user they logged in.
13. Now logged in, the user clicks to view their profile. The `Update________` middleware silently executes on the server.
14. This middleware updates the `________` column in the database to the current timestamp.
15. In `DashController@updateProfil`, if the user toggled the phone visibility switch, the backend catches it using `$user->display_phone = $request->________('display_phone');`.
16. The user clicks "Log Out". The route destroys the server-side `________` so the browser forgets who they are.
17. The user table schema has a boolean called `________`, which defaults to `true` when they first sign up.
18. Any time the user's `pfp` URL is requested by Blade, the `User` model intercepts it using the `get________Attribute()` accessor.
19. For password resets, the `User` model is capable of sending emails because it imports the `________` trait.
20. Filament knows this user isn't an admin because their `role` column defaults to `________`.

---

## Phase 2: The View Marketplace Flow
*A user visits the homepage to view artisanal products.*

21. The root `/` route mapped in `web.php` directs the user to `HomeController@________`.
22. The controller initializes a database builder query: `$query = Produit::________()`.
23. It filters out unapproved items using `where('etat_moderation', '________')`.
24. The user typed "Miel" into the search bar. The URL looks like `/?q=Miel`. The controller catches this via `if ($request->________('q'))`.
25. To search safely, it uses the SQL wildcard: `$query->where('titre', '________', '%' . $request->q . '%')`.
26. The user also clicked the "Food" category filter. The controller forces their input to be a number: `(________) $request->cat`.
27. The products are fetched from the database ordered by newest first via the `________()` Eloquent method.
28. Only 24 items are loaded so the server doesn't crash, using `->________(24)`.
29. The data is passed to the Blade view `home.blade.php` using `return view('home', ________('produits'))`.
30. During Blade view compilation, `AppServiceProvider` automatically sneaks the `$________` variable into the view so the navbar can render the dropdown list.
31. Inside the loop, a product image URL is generated using `\App\Helpers\ImageHelper::getUrl($produit->________[0])`.
32. Because the DB stored images as a JSON string, the `Produit` model automatically transforms it back into a PHP array via the `$________` property.
33. The user's browser begins downloading the images. Because `[x-cloak]` is in the CSS, any Alpine.js interactive elements remain `________` until the JS engine boots.
34. The user clicks the empty "Heart" icon. This sends a POST request to `ProduitController@________`.
35. The controller ensures the user isn't favoriting their own item by checking `$produit->________ === auth()->id()`.
36. It flips the favorited state in the `favoris` pivot table using `auth()->user()->favoris()->________($produit->id)`.
37. The controller finishes the loop by returning the user directly back to the spot they were looking at using `return ________();`.
38. The `Produit` model is able to join securely to the `Categorie` table because of a `belongsTo(________::class)` relationship.
39. The price displayed to the user is formatted gracefully because the `Produit` model casts the `prix` column to `________:2`.
40. The user clicks a product. The URL relies on the `slug` column string, which was guaranteed to be unique via `$table->slug()->________()` in the migrations.

---

## Phase 3: The Upload Logic
*An artisan submits a 5-image listing for a wooden chair.*

41. The user selects 5 pictures. The Blade view's `<form>` tag MUST have `enctype="multipart/________"` defined, otherwise PHP can't physically read the files.
42. The request hits `ProduitController@store`. The validator immediately checks `'images' => 'required|array|________:5'`.
43. The user maliciously uploaded a PDF. Your server isn't specifically checking MIME types here, but `ImageHelper` uses a PHP image library that would throw an exception when trying to `________` the PDF into a JPEG.
44. The controller prepares an empty array called `$________` to hold the final file destinations.
45. A `________` loop begins, iterating through `$request->file('images')`.
46. `ImageHelper` saves the first image to the hard drive and returns the string path `produits/123.jpg`, which is pushed into the array.
47. The text "Wooden Chair" is converted to "wooden-chair" using the Laravel helper `Str::________(...)`.
48. The `$validated` form data and the `$paths` image array are combined together using `_________merge()`.
49. The `$data` array also attaches the current user's ID to lock ownership by calling `auth()->________()`.
50. The Eloquent command `Produit::________($data)` generates the gigantic `INSERT INTO produits` SQL query.
51. But wait... Laravel provides protection. If `vendeur_id` wasn't explicitly listed inside `protected $________` on the Produit model, Eloquent would silently drop it to prevent hacking.
52. The product is saved. Its `etat_moderation` defaults to `________` so it doesn't show up on the public homepage yet.
53. The controller returns a redirect. The user looks at their dashboard.
54. The artisan wants to "Sponsor" the chair. They click the button, triggering `demanderSponsor(Produit $produit)`.
55. The controller updates the `sponsor_status` column to `________`.
56. An admin logs into Filament. They see the product in `ProduitResource.php`.
57. The admin clicks the "Sponsoriser" action button. Event fires!
58. Livewire updates the product's `is_sponsored` boolean to `________`.
59. Livewire calculates exactly 7 days into the future using `________()->addDays(7)`.
60. That future date is saved into the `________` column of the product row.

---

## Phase 4: The Auto-Polling Chat Stream
*A buyer wants to buy the sponsored wooden chair.*

61. The buyer clicks "Contacter". They land on `/messages?conversation=14`.
62. Alpine.js initializes its state. It detects they are on a laptop because `window.________` is greater than 768 pixels.
63. Alpine's `fetchConversations()` method fires an asynchronous `axios.________` request to `/api/conversations`.
64. The server's `MessageController` queries the DB. It returns all chats where the `acheteur_id` equals the buyer, OR the product's `________` equals the buyer.
65. The Javascript receives an array of JSON objects. It saves it to the Javascript variable `this.________`.
66. Alpine reads the URL parameter `?conversation=14` using `new URLSearchParams(window.location.________)`.
67. Alpine automatically clicks the target conversation by calling `this.________Conversation(targetConv)`.
68. When the chat opens, two things happen: `this.fetchMessages(14)` fetches history, and `this.start________(14)` begins the automatic loop.
69. The automatic loop is built using the Javascript timing mechanism called `________Interval()`.
70. It is programmed to hit the server exactly every `________` milliseconds (3 seconds).
71. The buyer types "Hello". They click send. The `sendMessage()` javascript function fires.
72. It immediately pushes a `tempMessage` payload into the `this.messages` array *before* the server responds. This is known as `________` UI updating.
73. Because a new message was injected, the UI is forced downwards to the latest text using the `this.________()` function.
74. Under the hood, the raw POST request hits the API. The Laravel controller creates the model `$message = $conversation->________()->create([...]);`
75. The `expediteur_id` is automatically set to the buyer via `auth()->id()`.
76. If Laravel Echo *was* connected to a websocket, the controller would fire `________(new \App\Events\MessageSent($message))->toOthers();`.
77. The server responds with `200 OK` and a JSON copy of the saved message.
78. Three seconds later, the seller's browser (sitting completely idle) fires its polling `axios.get`.
79. The seller's Javascript compares array lengths: `if (newMessages.length > this.messages.________)`.
80. It detects a new message. It overwrites `this.messages = newMessages`, and Alpine magically renders the new chat bubble on the screen!

---

## Phase 5: The Infrastructure CORS Bypass
*The admin opens the Filament dashboard to view an image hosted on AWS S3.*

81. Your `.env` file lists `FILESYSTEM_DISK=s3`. Laravel reads this via `config('filesystems.________')`.
82. The admin panel HTML asks the browser to load `https://s3.aws.com/lartisan/test.jpg`.
83. The browser looks at `s3.aws.com`. It realizes your admin panel is running on `lartisan.ma`.
84. Because the domains don't match, the browser panics and throws a strict `________` security error. The image breaks.
85. To fix this, you edited `ImageHelper::getUrl()`.
86. The helper tries `$disk->url($path)`. Because it is an S3 disk, it throws a PHP `________`.
87. The `catch` block intercepts the crash. It calls `$disk->________Url($path, ...)` to generate an authenticated AWS link that expires in 24 hours.
88. Wait, Filament's `FilePond` uploader still breaks on cloud URLs because it uses Javascript to preview images!
89. You built the `StorageProxyController` to trick the browser. 
90. Instead of asking AWS directly, the image HTML tag asks `https://lartisan.ma/storage-proxy/test.jpg`.
91. The Laravel router catches `/storage-proxy/{path}` and launches your custom controller.
92. The controller uses `Storage::disk(...)` and requests the raw bytecode over the server's backend connection by calling `$disk->________($path)`.
93. The server then spits that raw bytecode directly out to the administrator's screen using `response()->________(...)`.
94. The browser feels perfectly safe because it believes the image originated securely from `________`.ma!
95. However, there is a risk. If a hacker guesses image paths that don't exist, they could crash your server.
96. So, the first line of the proxy controller checks: `if (!$disk->________($path))`.
97. If it doesn't exist, the proxy gracefully shuts down the request by calling the Laravel helper `________(404)`.
98. But wait! How does the browser know the raw bytecode is an image, and not a PDF or an MP3?
99. When calling the proxy response, you explicitly pass an array of HTTP headers, specifically defining the `________-Type`.
100. This value is calculated dynamically by asking the cloud disk what it thinks the file is: `$disk->________($path)`.

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

---
### The Ultimate Master Key (Answers)
*If you got 90% of these right, you are the indisputable master of your codebase.*

1. POST
2. Auth
3. validate
4. Hash
5. hasFile
6. ImageHelper
7. profiles
8. id
9. login
10. success
11. GET
12. toast / alert / success
13. LastSeen
14. last_seen_at
15. has
16. session
17. telephone_visible
18. PfpUrl
19. Notifiable
20. utilisateur
21. index
22. query
23. approuve
24. has
25. like
26. int
27. latest
28. paginate
29. compact
30. categories (or villes / unreadCount)
31. images
32. casts
33. hidden (or display: none)
34. toggleFavorite
35. vendeur_id
36. toggle
37. back
38. Categorie
39. decimal
40. unique
41. form-data
42. size
43. compress
44. paths
45. foreach
46. foreach
47. slug
48. array
49. id
50. create
51. fillable
52. en_attente
53. dashboard / back
54. demanderSponsor
55. en_attente
56. Produit
57. Sponsoriser
58. true
59. now
60. sponsored_until
61. messages
62. innerWidth
63. get
64. vendeur_id
65. conversations
66. search
67. select
68. Polling
69. set
70. 3000
71. sendMessage
72. Optimistic
73. scrollToBottom
74. messages
75. expediteur_id
76. broadcast
77. JSON
78. get
79. length
80. messages
81. default
82. AWS / S3
83. lartisan.ma
84. CORS
85. ImageHelper
86. Exception
87. temporary
88. CORS
89. StorageProxy
90. admin
91. proxy
92. get
93. stream
94. lartisan
95. server
96. exists
97. abort
98. Content
99. Content
100. mimeType
