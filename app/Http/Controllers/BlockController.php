<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function block(User $user)
    {
        $blocker = auth()->user();

        if ($blocker->id === $user->id) {
            return response()->json(['message' => 'Vous ne pouvez pas vous bloquer vous-même.'], 400);
        }

        $blocker->blockedUsers()->syncWithoutDetaching([$user->id]);

        return response()->json(['message' => 'Utilisateur bloqué.', 'is_blocked' => true]);
    }

    public function unblock(User $user)
    {
        $blocker = auth()->user();
        $blocker->blockedUsers()->detach($user->id);

        return response()->json(['message' => 'Utilisateur débloqué.', 'is_blocked' => false]);
    }
}
