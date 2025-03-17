<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    //勤怠登録
    public function attendance()
    {
        $now_date = Carbon::now()->format('Y-m-d');
        $user_id = Auth::user()->id;
        $confirm_date = Work::where('user_id', $user_id)
            ->where('date', $now_date)
            ->first();

        if (!$confirm_date) {
            $status = 0;
        } else {
            $status = Auth::user()->status;
        }

        return view('attendance', compact('status'));
    }

    //打刻
    public function work(Request $request)
    {
        $now_date = Carbon::now()->format('Y-m-d');
        $now_datetime = Carbon::now()->format('Y-m-d H:i:s');
        $user_id = Auth::user()->id;
        if ($request->has('start_rest') || $request->has('end_rest')) {
            $work_id = Work::where('user_id', $user_id)
                ->where('date', $now_date)
                ->first()
                ->id;
        }

        if ($request->has('start_work')) {
            $attendance = new Work();
            $attendance->date = $now_datetime;
            $attendance->start = $now_datetime;
            $attendance->user_id = $user_id;
            $status = 1;
        }

        if ($request->has('start_rest')) {
            $attendance = new Rest();
            $attendance->start = $now_datetime;
            $attendance->work_id = $work_id;
            $status = 2;
        }

        if ($request->has('end_rest')) {
            $attendance = Rest::where('work_id', $work_id)
                ->whereNotNull('start')
                ->whereNull('end')
                ->first();
            $attendance->end = $now_datetime;
            $status = 1;
        }

        if ($request->has('end_work')) {
            $attendance = Work::where('user_id', $user_id)
                ->where('date', $now_date)
                ->first();
            $attendance->end = $now_datetime;
            $status = 3;
        }

        $user = User::find($user_id);
        $user->status = $status;
        $user->save();

        $attendance->save();

        return redirect('/attendance')->with(compact('status'));
    }

    //勤怠一覧
    public function list(Request $request)
    {
        $user = Auth::id();

        $currentDate = $request->has('currentDate')
        ? Carbon::createFromFormat('Y/m', $request->input('currentDate'))
        : now();

        if ($request->input('prevMonth')) {
            $currentDate->subMonth();
        }
        if ($request->input('nextMonth')) {
            $currentDate->addMonth();
        }

        $currentDateString = $currentDate->format('Y/m');

        $works = Work::where('user_id', $user)
                ->whereBetween('date', [
                    $currentDate->startOfMonth()->format('Y-m-d'),
                    $currentDate->endOfMonth()->format('Y-m-d'),
                ])
                ->get();

        $workTimePerDay = [];
        $breakTimePerDay = [];

        foreach ($works as $work) {
            $date = Carbon::parse($work->date)->format('Y-m-d');

            if ($work->end) {
                $start = Carbon::parse($work->start);
                $end = Carbon::parse($work->end);
                $workTime = $end->diffInMinutes($start);

                $totalWorkTime = $workTime;
                $totalRestTime = 0;

                $rests = Rest::where('work_id', $work->id)->get();
                foreach ($rests as $rest){
                    if ($rest->end){
                        $restStart = Carbon::parse($rest->start);
                        $restEnd = Carbon::parse($rest->end);
                        $restTime = $restEnd->diffInMinutes($restStart);
                        $totalRestTime += $restTime;
                    }
                }
                $totalWorkTime -= $totalRestTime;
                $breakTimePerDay[$date] = $totalRestTime;
                $workTimePerDay[$date] = $totalWorkTime;
            }
        }

        $workHours = [];
        $workMinutes = [];
        $restHours = [];
        $restMinutes = [];

        foreach ($workTimePerDay as $date => $workTime) {
            $workHours[$date] = floor($workTime / 60);
            $workMinutes[$date] = $workTime % 60;
        }

        foreach ($breakTimePerDay as $date => $restTime) {
            $restHours[$date] = floor($restTime / 60);
            $restMinutes[$date] = $restTime % 60;
        }

        return view('list',  ['currentDate' => $currentDateString], compact('works', 'workHours', 'workMinutes', 'restHours', 'restMinutes'));
    }
}