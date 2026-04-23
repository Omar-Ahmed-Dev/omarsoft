<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function saveTheme(Request $request)
    {
        $this->userService->saveTheme($request->tree_theme);
        return redirect()->back();
    }
}