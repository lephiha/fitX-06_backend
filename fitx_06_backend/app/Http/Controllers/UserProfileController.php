<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NutritionLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NutritionController extends Controller
{
    // Lấy log dinh dưỡng hôm nay
    public function today()
    {
        $userId = Auth::id();
        $today = Carbon::today();

        $logs = NutritionLog::where('user_id', $userId)
            ->whereDate('date', $today)
            ->get();

        $summary = [
            'total_calories' => $logs->sum('calories'),
            'protein' => $logs->sum('protein'),
            'carbs' => $logs->sum('carbs'),
            'fat' => $logs->sum('fat'),
        ];

        return response()->json([
            'success' => true,
            'date' => $today->toDateString(),
            'summary' => $summary,
            'meals' => $logs
        ]);
    }

    // Ghi log bữa ăn
    public function addMeal(Request $request)
    {
        $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'food_name' => 'required|string|max:100',
            'calories' => 'required|integer|min:0',
            'protein' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
        ]);

        $log = NutritionLog::create([
            'user_id' => Auth::id(),
            'meal_type' => $request->meal_type,
            'food_name' => $request->food_name,
            'calories' => $request->calories,
            'protein' => $request->protein ?? 0,
            'carbs' => $request->carbs ?? 0,
            'fat' => $request->fat ?? 0,
            'date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm bữa ăn thành công',
            'meal' => $log
        ]);
    }
}
