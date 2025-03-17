<?php

use Illuminate\Support\Facades\Route;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApprovalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth','verified'])->group(function () {
    //勤怠登録画面
    Route::get('/attendance', [AttendanceController::class, 'attendance']);
    //打刻機能
    Route::post('/work', [AttendanceController::class, 'work'])->name('work');
    //勤怠一覧画面
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    //勤怠詳細画面
    Route::get('/attendance/{work}', [GeneralController::class, 'detail'])->name('attendance.detail');
    //勤怠修正申請
    Route::post('/attendance/correct', [GeneralController::class, 'correct'])->name('attendance.correct');
    //申請一覧画面
    Route::get('/stamp_correction_request/list', [GeneralController::class, 'request'])->name('request.list');
});

//管理ログイン画面
Route::get('/admin/login', [AdminLoginController::class, 'create']);

//管理ログイン
Route::post('/admin/login', [AdminLoginController::class, 'store']);

//管理者ログアウト
Route::post('/admin/logout', [AdminLoginController::class, 'logout']);

//管理ログイン後のみ表示
Route::middleware('auth:admin')->group(function () {
    //勤怠一覧画面
    Route::get('/admin/attendance/list', [AdminController::class, 'getSummary']);
    //スタッフ一覧画面
    Route::get('/admin/staff/list', [StaffController::class, 'staff']);
    //スタッフ別月次勤怠一覧画面
    Route::get('/admin/attendance/staff/{user}', [StaffController::class, 'show'])->name('staff.show');
    //スタッフ別月次勤怠エクスポート
    Route::post('/admin/attendance/staff/{user}/export', [StaffController::class, 'export'])->name('staff.export');
    //勤怠詳細画面
    Route::get('/admin/attendance/{work}', [AdminController::class, 'specific'])->name('work.specific');
    //勤怠詳細修正データ送信
    Route::post('/admin/attendance/{work}/update', [AdminController::class, 'update'])->name('work.update');
    //申請一覧
    Route::get('/admin/stamp_correction_request/list', [ApprovalController::class, 'apply'])->name('apply.list');
    //修正申請承認画面
    Route::get('/admin/stamp_correction_request/approve/{correction}', [ApprovalController::class, 'getApproval']);
    //修正申請承認
    Route::post('/admin/stamp_correction_request/approve/{correction}', [ApprovalController::class, 'postApproval'])->name('approval.post');;
});

//一般ユーザーのログインデータ送信
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('email');

//一般ユーザーの会員データ送信
Route::post('/register', [RegisteredUserController::class, 'store']);

//メールアドレス確認ページの表示
Route::get('/email/verify', function () {
    return view('auth.email');
})->name('verification.notice');

//メール確認通知の再送信
Route::post('/email/verification-notification', function (Request $request) {
    session()->get('unauthenticated_user')->sendEmailVerificationNotification();
    session()->put('resent', true);
    return back()->with('message', 'Verification link sent!');
})->name('verification.send');

//ユーザーのメールアドレスを確認処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    session()->forget('unauthenticated_user');
    return redirect('/attendance');
})->name('verification.verify');