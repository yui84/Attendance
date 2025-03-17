<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StaffController extends Controller
{
    //スタッフ一覧
    public function staff()
    {
        $users = User::all();
        return view('staff', compact('users'));
    }

    //スタッフ別勤怠一覧
    public function show(Request $request, $user_id)
    {
        $user = User::find($user_id);

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

        $works = Work::where('user_id', $user->id)
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

        return view('personal', ['currentDate' => $currentDateString], compact('user', 'works', 'workHours', 'workMinutes', 'restHours', 'restMinutes'));
    }

    public function export(Request $request, $userId)
    {
        $currentDateString = $request->input('currentDate');
        $currentDate = Carbon::parse($currentDateString . '/01');

        $user = User::findOrFail($userId);

        $works = Work::where('user_id', $user->id)
                        ->whereYear('date', $currentDate->year)
                        ->whereMonth('date', $currentDate->month)
                        ->get();

        $csvHeader = ['日付', '出勤', '退勤', '休憩', '合計'];
        $csvData = [];

        foreach ($works as $work) {
            $date = Carbon::parse($work->date)->format('m/d');
            $start = Carbon::parse($work->start)->format('H:i');
            $end = $work->end ? Carbon::parse($work->end)->format('H:i') : '';

            $totalRestTime = 0;
            $rests = Rest::where('work_id', $work->id)->get();
            foreach ($rests as $rest) {
                if ($rest->end) {
                    $restStart = Carbon::parse($rest->start);
                    $restEnd = Carbon::parse($rest->end);
                    $restTime = $restEnd->diffInMinutes($restStart);  // 休憩時間（分）
                    $totalRestTime += $restTime;
                }
            }

            $restHours = floor($totalRestTime / 60);
            $restMinutes = $totalRestTime % 60;

            if ($work->end) {
                $startCarbon = Carbon::parse($work->start);
                $endCarbon = Carbon::parse($work->end);
                $workTime = $endCarbon->diffInMinutes($startCarbon);  // 勤務時間（分）

                $totalWorkTime = $workTime - $totalRestTime;
                $workHours = floor($totalWorkTime / 60);  // 時間（時）
                $workMinutes = $totalWorkTime % 60;  // 分
            } else {
                $workHours = 0;
                $workMinutes = 0;
            }

            $csvData[] = [
                $date,
                $start,
                $end,
                "{$restHours}:".str_pad($restMinutes, 2, '0', STR_PAD_LEFT),  // 休憩
                "{$workHours}:".str_pad($workMinutes, 2, '0', STR_PAD_LEFT),  // 合計
            ];
        }

        $response = new StreamedResponse(function() use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="staff_report.csv"');
        return $response;
    }
}
