<?php

namespace App\Http\Controllers\Traits;

use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;

trait ValidatesAndPreparesData
{
    public function validateAndPrepareData(UserUpdateRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("/storage/profile/$user->slug/");
            $image->move($destinationPath, $name);
            $validated['profile_img'] = $name;
        }

        return $validated;
    }
}
