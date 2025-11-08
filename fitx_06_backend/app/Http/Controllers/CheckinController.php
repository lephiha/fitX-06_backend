<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\WorkoutSchedule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckinController extends Controller
{
    //check-in bằng mã QR
    public function checkin(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $userId = Auth::id();

        // Tìm buổi tập tương ứng
        $schedule = WorkoutSchedule::where('id', $request->qr_code)
            ->orWhere('qr_code', $request->qr_code)
            ->first();

        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Mã QR không hợp lệ'], 404);
        }

        // check-in hôm nay chưa
        $todayCheck = Checkin::where('user_id', $userId)
            ->whereDate('checkin_time', Carbon::today())
            ->exists();

        if ($todayCheck) {
            return response()->json(['success' => false, 'message' => 'Bạn đã check-in hôm nay rồi!'], 400);
        }

        // Ghi check-in
        $checkin = Checkin::create([
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'qr_code' => $request->qr_code,
            'checkin_time' => now(),
        ]);

        //update
        $schedule->update(['check_in' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in thành công!',
            'data' => [
                'schedule' => $schedule->title,
                'time' => $checkin->checkin_time
            ]
        ]);
    }
}
