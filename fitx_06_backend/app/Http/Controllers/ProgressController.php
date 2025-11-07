<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProgressTracking;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    // Lấy tiến độ hiện tại của user
    public function index()
    {
        $userId = Auth::id();

        $progress = ProgressTracking::where('user_id', $userId)->latest()->first();

        if (!$progress) {
            return response()->json([
                'success' => true,
                'message' => 'Chưa có dữ liệu tiến độ',
                'data' => [
                    'weight' => null,
                    'bmi' => null,
                    'streak_days' => 0,
                    'workout_count' => 0,
                    'weight_diff' => 0,
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $progress
        ]);
    }

    // Cập nhật tiến độ 
    public function update(Request $request)
    {
        $request->validate([
            'weight' => 'nullable|numeric|min:0',
            'bmi' => 'nullable|numeric|min:0',
            'streak_days' => 'nullable|integer|min:0',
            'workout_count' => 'nullable|integer|min:0',
            'weight_diff' => 'nullable|numeric',
        ]);

        $userId = Auth::id();

        $progress = ProgressTracking::updateOrCreate(
            ['user_id' => $userId],
            $request->only(['weight', 'bmi', 'streak_days', 'workout_count', 'weight_diff'])
        );

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tiến độ thành công',
            'data' => $progress
        ]);
    }
}
