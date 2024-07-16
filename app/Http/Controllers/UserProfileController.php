<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class UserProfileController extends Controller
{
    public function publicProfile($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        $title = "{$user->first_name} {$user->last_name} | Perfil | IaiPassei";
        return view('public.profile.index', compact('title', 'user', 'slug'));
    }
}
