# 📂 Technical Map: Messaging System Architecture

This document provides a complete overview of every file and code block that powers the messaging system in this project. Use this as a reference to understand how the "AI-added" parts work together.

---

## 🏗️ 1. Database & Models
These files define how the data is stored in the database.

*   **[User.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Models/User.php)**: 
    - Added `last_seen_at` to track online status.
    - Defines the `produits()` relationship.
*   **[Conversation.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Models/Conversation.php)**: 
    - Links an `acheteur` (buyer) and a `vendeur` (seller) via a `produit`.
    - Has many `messages`.
*   **[Message.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Models/Message.php)**: 
    - Stores the message `contenu`, `expediteur_id`, and `conversation_id`.

---

## ⚙️ 2. Backend Logic (Controllers & Routing)
These files handles the data flow and API endpoints.

*   **[MessageController.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Http/Controllers/MessageController.php)**:
    - `index()`: Maps conversations for the sidebar. Buyers always see their conversations; sellers only see conversations **once a message has been sent** (`whereHas('messages')`).
    - `fetchMessages()`: Retrieves chat history for a specific contact.
    - `sendMessage()`: Saves new messages and **broadcasts** them in real-time.
*   **[web.php](file:///d:/marcheartisanalfinalpages/lartisan/routes/web.php)**:
    - Defines the `/message` view route.
    - Defines `/api/conversations` and `/api/conversations/{id}/messages` for the frontend to call.

---

## 📡 3. Real-Time Broadcasting (Reverb)
These files make messages appear instantly without refreshing.

*   **[MessageSent.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Events/MessageSent.php)**:
    - The "Event" class that transmits data over WebSockets.
    - Uses `ShouldBroadcastNow` for instant delivery.
*   **[channels.php](file:///d:/marcheartisanalfinalpages/lartisan/routes/channels.php)**:
    - Authorization logic. It ensures only the two people in a conversation can listen to their private chat stream.
*   **[UpdateLastSeen.php](file:///d:/marcheartisanalfinalpages/lartisan/app/Http/Middleware/UpdateLastSeen.php)**:
    - Middleware that updates `last_seen_at` on every user action.
    - Registered in **[bootstrap/app.php](file:///d:/marcheartisanalfinalpages/lartisan/bootstrap/app.php)**.

---

## 🎨 4. Frontend & UI (AlpineJS & Tailwind)
This is the "Face" of the messaging system.

*   **[message.blade.php](file:///d:/marcheartisanalfinalpages/lartisan/resources/views/message.blade.php)**:
    - **UI**: Horizontal contact cards, search bar, and dashboard-aligned header bar.
    - **Logic (AlpineJS)**:
        - `conversations`: Stores the list of contacts.
        - `messages`: Stores active chat history.
        - `isMobile`: Reactive boolean for screen-size detection.
        - `x-teleport="body"`: Portals the mobile chat box to the document body for 100% full-screen coverage.
*   **[chat-content.blade.php](file:///d:/marcheartisanalfinalpages/lartisan/resources/views/partials/chat-content.blade.php)**:
    - **Reusable Fragment**: Contains the header, message loop, and input area.
    - Used by both the Desktop layout (static) and Mobile layout (teleported).
    - The textarea wrapper uses `flex items-center` to keep the send button vertically centered on the same line as the input.
*   **[echo.js](file:///d:/marcheartisanalfinalpages/lartisan/resources/js/echo.js)**:
    - Configuration for **Laravel Echo**. Connects to Reverb on port `8080`.

---

## 📱 5. Mobile Optimization (The "Teleport" Tech)
To solve "cramped" layouts on small screens, we use a portal pattern:

1. **Extraction**: We moved the entire chat interface into a `partials/chat-content` file.
2. **Teleportation**: We used `<template x-teleport="body">`. This "lifts" the chat box out of the dashboard slots and places it at the very top of the HTML body.
3. **Fixed Overlay**: Because it's at the body level, `fixed inset-0` perfectly covers the whole phone screen without being blocked by sidebar padding.
4. **Reactive Back Button**: The `currentConversation = null` action instantly closes the overlay and returns to the horizontal list.

---

## 🚀 6. Infrastructure & Deployment
*   **[.env](file:///d:/marcheartisanalfinalpages/lartisan/.env)**:
    - `BROADCAST_CONNECTION=reverb`
    - Reverb app keys and host (`127.0.0.1`) variables.
*   **Command**: `php artisan reverb:start`
    - Must be running for real-time features to work.

---

### Summary of Data Flow
1. User types in `message.blade.php` → Calls `sendMessage()` in `MessageController`.
2. Controller saves message to DB → Fires `MessageSent` event.
3. Reverb receives the event → Instantly pushes it to the recipient's browser.
4. Recipient's AlpineJS hears the event → Updates the message array locally.
5. UI reflects change instantly in both Desktop and Teleported Mobile views.

---

## 🔧 7. Recent Changes

### Send Button Vertical Alignment · `chat-content.blade.php`
The wrapper `<div>` around the `<textarea>` in the input area lacked `flex items-center`, causing the send button SVG to appear misaligned. Fixed by adding `flex items-center` to the div:
```diff
- <div class="flex-1 relative group">
+ <div class="flex-1 relative group flex items-center">
```

### Conversation Visibility: Buyers vs. Sellers · `MessageController.php`
Previously, a conversation appeared in the seller's list the moment the buyer clicked "Contact", even before any message was sent. Now:
- **Buyer** → always sees their conversation (they created it)
- **Seller** → only sees the conversation once `whereHas('messages')` is satisfied

The `index()` method was split into `$buyerConversations` and `$sellerConversations`, then merged.
