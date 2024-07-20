<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileSettings;

class ProfileSettingsController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $profileSettings = $user->profileSettings;

        $settings = $request->all();

        $allowedSettings = ['show_username', 'show_email', 'show_sex', 'show_sexual_orientation', 'show_gender', 'show_race', 'show_disability'];

        foreach ($settings as $setting => $value) {
            if (in_array($setting, $allowedSettings)) {
                $profileSettings->$setting = $value;
            }
        }

        $profileSettings->save();

        return response()->json(['success' => true]);
    }
}
