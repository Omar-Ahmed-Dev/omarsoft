<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserService
{
    public function saveTheme(string $theme): void
    {
        User::first()->update([
            'tree_theme' => $theme
        ]);
    }
    
    public function saveVantaTheme(string $theme): void
{
    User::first()->update([
        'vanta_theme' => $theme
    ]);
}
}
