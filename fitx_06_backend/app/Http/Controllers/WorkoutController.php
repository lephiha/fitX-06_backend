<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkoutSchedule;
use App\Models\WorkoutSet;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    // Lấy danh sách buổi tập của user
    public function index()
    {
        $userId = Auth::id();

        $workouts = WorkoutSchedule::where('user_id', $userId)
            ->with('trainer:id,fullname,email')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'workouts' => $workouts
        ]);
    }

    // Chi tiết buổi tập
    public function show($id)
    {
        $workout = WorkoutSchedule::with('sets')->find($id);

        if (!$workout) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy buổi tập'], 404);
        }

        return response()->json([
            'success' => true,
            'workout' => $workout
        ]);
    }

    // Check-in buổi tập
    public function checkIn(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:workout_schedules,id',
        ]);

        $schedule = WorkoutSchedule::find($request->schedule_id);
        $schedule->check_in = true;
        $schedule->save();

        return response()->json([
            'success' => true,
            'message' => 'Check-in thành công!'
        ]);
    }

    // (Tùy chọn) Tạo buổi tập mới — dành cho admin hoặc PT
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'type' => 'in:PT,Group',
        ]);

        $workout = WorkoutSchedule::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tạo buổi tập thành công',
            'workout' => $workout
        ]);
    }
}
