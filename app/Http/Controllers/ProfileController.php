<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($user)
    {
        $user = User::find($user);

        return response()->json([
            'status' => 'success',
            'user' => $user->load('profile')
        ], 200);
    }

    public function update(Request $request, $user)
    {
        $user = User::find($user);
        $user->fill($request->all());
        $user->save();
        $user->profile->fill($request->profile);
        $user->profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile '.$user->name.' has been updated',
            'user' => $user->load('profile')
        ], 200);
    }
}
