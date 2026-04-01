---
contents:
  - id: 1
    label: database/migrations/2026_03_29_121735_create_blocked_users_table.php
    language: php
  - id: 2
    label: app/Models/User.php
    language: php
  - id: 3
    label: app/Http/Controllers/BlockController.php
    language: php
  - id: 4
    label: app/Http/Controllers/MessageController.php
    language: php
  - id: 5
    label: routes/web.php
    language: php
  - id: 6
    label: resources/views/message.blade.php
    language: blade
  - id: 7
    label: resources/views/partials/chat-content.blade.php
    language: blade
createdAt: 1774835000000
description: Implementation of a comprehensive user blocking system to prevent unwanted communication and enhance user safety.
folderId: null
id: 1774835000000
isDeleted: 0
isFavorites: 0
name: 09_USER_BLOCKING_SYSTEM
tags: []
updatedAt: 1774835000000
---

## Fragment: database/migrations/2026_03_29_121735_create_blocked_users_table.php
# This migration creates the junction table for blocked relationships, ensuring users can block each other with strict foreign keys.
```php
public function up(): void
{
    Schema::create('blocked_users', function (Blueprint $table) {
        $table->id();
        $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('blocked_id')->constrained('users')->onDelete('cascade');
        $table->unique(['blocker_id', 'blocked_id']);
        $table->timestamps();
    });
}
```

## Fragment: app/Models/User.php
# Add these relationships and helper methods to the User model to manage blocking state efficiently.
```php
public function blockedUsers() {
    return $this->belongsToMany(User::class, 'blocked_users', 'blocker_id', 'blocked_id')->withTimestamps();
}

public function blockedByUsers() {
    return $this->belongsToMany(User::class, 'blocked_users', 'blocked_id', 'blocker_id')->withTimestamps();
}

public function hasBlocked($userId) {
    return $this->blockedUsers()->where('blocked_id', $userId)->exists();
}

public function isBlockedBy($userId) {
    return $this->blockedByUsers()->where('blocker_id', $userId)->exists();
}
```

## Fragment: app/Http/Controllers/BlockController.php
# This new controller handles the API logic for blocking and unblocking users.
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function block(User $user)
    {
        $blocker = auth()->user();
        if ($blocker->id === $user->id) return response()->json(['message' => 'Cannot block self'], 400);
        
        $blocker->blockedUsers()->syncWithoutDetaching([$user->id]);
        return response()->json(['message' => 'Blocked', 'is_blocked' => true]);
    }

    public function unblock(User $user)
    {
        auth()->user()->blockedUsers()->detach($user->id);
        return response()->json(['message' => 'Unblocked', 'is_blocked' => false]);
    }
}
```

## Fragment: app/Http/Controllers/MessageController.php
# Modified version of sendMessage and fetchMessages to respect blocking status.
```php
// In sendMessage()
$partner = auth()->id() === $conversation->acheteur_id ? $conversation->produit->vendeur : $conversation->acheteur;
if (auth()->user()->isBlockedBy($partner->id) || auth()->user()->hasBlocked($partner->id)) {
    return response()->json(['message' => 'Communication blocked'], 403);
}

// In fetchMessages() / index()
return [
    'messages' => $messages,
    'is_blocked' => auth()->user()->hasBlocked($partner->id ?? 0),
    'blocked_by' => auth()->user()->isBlockedBy($partner->id ?? 0)
];
```

## Fragment: routes/web.php
# Register the block/unblock API endpoints within the authenticated group.
```php
Route::post('/api/block/{user}', [BlockController::class, 'block']);
Route::post('/api/unblock/{user}', [BlockController::class, 'unblock']);
```

## Fragment: resources/views/message.blade.php
# Updated Alpine.js state and method for handling blocking.
```javascript
// Alpine State additions
is_blocked: false,
blocked_by: false,

// New method
async toggleBlock() {
    const endpoint = this.is_blocked ? `/api/unblock/${this.currentConversation.partner_id}` : `/api/block/${this.currentConversation.partner_id}`;
    const res = await axios.post(endpoint);
    this.is_blocked = res.data.is_blocked;
}
```

## Fragment: resources/views/partials/chat-content.blade.php
# New Dropdown UI and blocked notifications.
```blade
<div class="relative" x-data="{ dropdown: false }" @click.away="dropdown = false">
    <button @click="dropdown = !dropdown" class="...">Three Dots</button>
    <div x-show="dropdown" class="...">
        <button @click="toggleBlock()">
            <span x-text="is_blocked ? 'Débloquer' : 'Bloquer'"></span>
        </button>
    </div>
</div>

<!-- Input area templates -->
<template x-if="is_blocked">
    <div class="bg-gray-50 ...">Vous avez bloqué cet utilisateur.</div>
</template>
```
