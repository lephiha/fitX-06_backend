<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Gửi mã xác minh qua email
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        $token = rand(100000, 999999); 
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Gửi mail
        Mail::raw("Mã khôi phục mật khẩu của bạn là: $token", function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Mã xác minh khôi phục mật khẩu');
        });

        return response()->json(['success' => true, 'message' => 'Đã gửi mã xác minh qua email']);
    }

    // Xác thực mã OTP
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'token' => 'required'
        ]);

        $check = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'Mã xác minh không hợp lệ'], 400);
        }

        return response()->json(['success' => true, 'message' => 'Mã xác minh hợp lệ']);
    }

    // Đặt lại mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $check = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$check) {
            return response()->json(['success' => false, 'message' => 'Mã xác minh không hợp lệ'], 400);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Đặt lại mật khẩu thành công']);
    }
}
