<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($user)
    {
        $user = User::with(['projects' => function ($query)
            {
                $query->whereHas('projectStatus', function ($q)
                {
                    return $q->where('name', '!=', 'TRASH');
                });
            }])->find($user);

        return response()->json([
            'status' => 'success',
            'user' => $user->load('profile', 'clients')
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
