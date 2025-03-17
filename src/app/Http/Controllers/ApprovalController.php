<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CorrectRequest;
use App\Models\Correction;

class ApprovalController extends Controller
{
    //申請一覧画面
    public function apply(Request $request)
    {
        $tab = $request->input('tab', 'wait');

        if ($tab == 'check') {
            $corrections = Correction::where('status', 'approved')->get();
        }else {
            $corrections = Correction::where('status', 'pending')->get();
        }

        return view('apply', compact('corrections', 'tab'));
    }

    //修正承認画面
    public function getApproval(Correction $correction)
    {
        $work = $correction->work;
        $user = $work->user;
        $application = $correction->application;

        return view('approval', compact('correction', 'work', 'user', 'application'));
    }

    //修正承認送信
    public function postApproval(Correction $correction)
    {
        $work = $correction->work;
        $rests = $work->rests;

        $workDate = \Carbon\Carbon::parse($work->date)->format('Y-m-d');

        $work->start = \Carbon\Carbon::parse($workDate . ' ' . $correction->start)->format('Y-m-d H:i:s');
        $work->end = \Carbon\Carbon::parse($workDate . ' ' . $correction->end)->format('Y-m-d H:i:s');
        $work->date = \Carbon\Carbon::parse($work->date)->format('Y-m-d');

        foreach ($rests as $rest) {
            foreach ($rest->applications as $application) {
                $rest->start = \Carbon\Carbon::parse($workDate . ' ' . $application->start)->format('Y-m-d H:i:s');
                $rest->end = \Carbon\Carbon::parse($workDate . ' ' . $application->end)->format('Y-m-d H:i:s');
                $rest->save();
            }
        }

        $work->save();

        $correction->status = 'approved';
        $correction->save();

        return redirect()->back();
    }
}
