<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Work;
use App\Models\Rest;
use App\Models\Application;
use App\Models\Correction;
use Carbon\Carbon;
use App\Http\Requests\CorrectRequest;

class GeneralController extends Controller
{
    //勤怠詳細画面
    public function detail(Work $work)
    {
        $approvedCorrection = Correction::where('work_id', $work->id)->where('status', 'approved')->first();

        $pendingCorrection = Correction::where('work_id', $work->id)->where('status', 'pending')->first();
        $rests = Rest::where('work_id', $work->id)->get();
        $application = Application::where('rest_id', $rests->first()->id)->first();

        return view('detail', compact('work', 'rests', 'approvedCorrection', 'pendingCorrection', 'application'));
    }

    //勤怠修正データ送信
    public function correct(CorrectRequest $request, Work $work)
    {
        $correction = Correction::create([
            'work_id' => $request->work_id,
            'start' => $request->start_time,
            'end' => $request->end_time,
            'note' => $request->note,
            'status' => $request->status,
        ]);

        foreach ($request->rest_start as $key => $restStart) {
            $restEnd = $request->rest_end[$key];
            $restId = $request->rest_id[$key];

            Application::create([
                'rest_id' => $restId,
                'correction_id' => $correction->id,
                'start' => $restStart,
                'end' => $restEnd
            ]);
        }

        return redirect()->route('attendance.detail',  ['work' => $request->work_id]);
    }

    //申請一覧画面
    public function request(Request $request)
    {
        $tab = $request->input('tab', 'wait');

        if ($tab == 'check') {
            $corrections = Correction::where('status', 'approved')->get();
        }else {
            $corrections = Correction::where('status', 'pending')->get();
        }

        return view('request', compact('corrections', 'tab'));
    }
}
