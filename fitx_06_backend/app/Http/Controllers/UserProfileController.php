<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    
    public function show()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'weight' => $user->weight,
                'goal' => $user->goal,
                'role' => $user->role,
                'package_id' => $user->package_id,
            ]
        ]);
    }

    
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fullname' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'weight' => 'nullable|numeric',
            'goal' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->fullname = $request->fullname ?? $user->fullname;
        $user->phone = $request->phone ?? $user->phone;
        $user->weight = $request->weight ?? $user->weight;
        $user->goal = $request->goal ?? $user->goal;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ thành công',
            'data' => $user
        ]);
    }
}
