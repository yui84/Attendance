<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\Rest;
use App\Models\Correction;
use App\Models\Application;
use Carbon\Carbon;
use App\Http\Requests\UpdateRequest;

class AdminController extends Controller
{
    //勤怠一覧画面
    public function getSummary(Request $request)
    {
        $currentDate = $request->has('currentDate')
        ? Carbon::createFromFormat('Y/m/d', $request->input('currentDate'))
        : now();

        if ($request->input('prevDay')) {
            $currentDate->subDay();
        }
        if ($request->input('nextDay')) {
            $currentDate->addDay();
        }
        $currentDateString = $currentDate->format('Y/m/d');

        $works = Work::whereDate('date', $currentDate->format('Y-m-d'))->get();

        $userWorkData = [];

        foreach ($works as $work) {
            $userId = $work->user_id;

            $workStart = Carbon::parse($work->start);
            $workEnd = Carbon::parse($work->end);
            $workTime = $workEnd->diffInMinutes($workStart);

            $rests = Rest::where('work_id', $work->id)->get();
            $totalRestDuration = 0;
            foreach ($rests as $rest) {
            $restStart = Carbon::parse($rest->start);
            $restEnd = Carbon::parse($rest->end);
            $restDuration = $restEnd->diffInMinutes($restStart);
            $totalRestDuration += $restDuration;
            }
            if (!isset($userWorkData[$userId])) {
                $userWorkData[$userId] = [
                    'user_id' => $userId,
                    'work_time' => 0,
                    'rest_time' => 0,
                ];
            }
            $userWorkData[$userId]['work_time'] += $workTime - $totalRestDuration;
            $userWorkData[$userId]['rest_time'] += $totalRestDuration;
        }

        foreach ($userWorkData as $userId => $data) {
            $workHours = floor($data['work_time'] / 60);
            $workMinutes = $data['work_time'] % 60;
            $restHours = floor($data['rest_time'] / 60);
            $restMinutes = $data['rest_time'] % 60;

            $userWorkData[$userId] = [
                'work_time' => "{$workHours}:".str_pad($workMinutes, 2, '0', STR_PAD_LEFT),
                'rest_time' => "{$restHours}:".str_pad($restMinutes, 2, '0', STR_PAD_LEFT),
            ];
        }

        return view('summary', ['currentDate' => $currentDateString, 'works' => $works, 'userWorkData' => $userWorkData]);
    }

    //勤怠詳細画面
    public function specific(Work $work)
    {
        $rests = $work->rests;
        $correction = $work->corrections()->first();
        \Log::info('Correction:', ['correction' => $correction]);

        return view('specific', compact('work', 'rests', 'correction'));
    }

    //勤怠詳細データ修正送信
    public function update(UpdateRequest $request, $workId)
    {
        $work = Work::findOrFail($workId);

        $workDate = $work->date;

        $startTime = Carbon::parse($request['start_time'])->setDateFrom($workDate);
        $endTime = Carbon::parse($request['end_time'])->setDateFrom($workDate);
        $work->start = $startTime;
        $work->end = $endTime;
        $work->save();

        $correction = new Correction();
        $correction->work_id = $work->id;
        $correction->start = $request['start_time'];
        $correction->end = $request['end_time'];
        $correction->note = $request['note'];
        $correction->save();

        $rest = Rest::where('work_id', $work->id)->first();
        if ($rest) {
            $restStart = Carbon::parse($request['start_rest'])->setDateFrom($workDate);
            $restEnd = Carbon::parse($request['end_rest'])->setDateFrom($workDate);
            $rest->start = $restStart;
            $rest->end = $restEnd;
            $rest->save();
        } else {
            $newRest = new Rest();
            $newRest->work_id = $work->id;
            $newRest->start = Carbon::parse($request['start_rest'])->setDateFrom($workDate);
            $newRest->end = Carbon::parse($request['end_rest'])->setDateFrom($workDate);
            $newRest->save();
        }

        $application = new Application();
        $application->rest_id = $rest->id;
        $application->correction_id = $correction->id;
        $application->start = Carbon::parse($request['start_rest']);
        $application->end = Carbon::parse($request['end_rest']);
        $application->save();

        return redirect()->route('work.specific', $work->id);
    }
}
