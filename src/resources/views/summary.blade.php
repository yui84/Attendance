@extends('layouts.app')

@section('title', '勤怠一覧(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/summary.css') }}">
@endsection

@section('link')
<div class="link">
    <a class="attendance" href="/admin/attendance/list">勤怠一覧</a>
    <a class="list" href="/admin/staff/list">スタッフ一覧</a>
    <a class="detail" href="/admin/stamp_correction_request/list">申請一覧</a>
    <form class="logout-form" action="/admin/logout" method="POST">
        @csrf
        <button class="logout-form__button">ログアウト</button>
    </form>
</div>
@endsection

@section('content')
<div class="summary-content">
    <div class="date">
        <h2 class="title-date">{{ \Carbon\Carbon::parse($currentDate)->format('Y年n月j日') }}の勤怠</h2>
    </div>

    <div class="day">
        <form action="" method="GET">
            @csrf
            <div class="calender-date">
                <button class="prev" type="submit" name="prevDay" value="prev" id="prevDay">← 前日</button>
                <div class="today">
                    <img class="calender-icon" src="{{ asset('/images/calender-icon.png') }}" alt="">
                    <p class="date" id="currentDate">
                        {{ $currentDate }}
                    </p>
                </div>
                <button class="next" type="submit" name="nextDay" value="next" id="nextDay">翌月 →</button>
                <input type="hidden" name="currentDate" value="{{ $currentDate }}">
            </div>
        </form>
    </div>

    <div class="work">
        <table class="work-table">
            <tr class="table-row">
                <th class="table-title">名前</th>
                <th class="table-title">出勤</th>
                <th class="table-title">退勤</th>
                <th class="table-title">休憩</th>
                <th class="table-title">合計</th>
                <th class="table-title">詳細</th>
            </tr>

            @foreach ($works as $work)
            @php
                $userId = $work->user_id;
                $workData = $userWorkData[$userId];
            @endphp
            <tr class="table-row">
                <td class="table-data">
                    {{ $work->user->name }}
                </td>
                <td class="table-data">
                    {{ \Carbon\Carbon::parse($work->start)->format('H:i') }}
                </td>
                <td class="table-data">
                    @if ($work->end)
                        {{ \Carbon\Carbon::parse($work->end)->format('H:i') }}
                    @endif
                </td>
                <td class="table-data">
                    @if ($work->end)
                        {{ $workData['rest_time'] }}
                    @endif
                </td>
                <td class="table-data">
                    @if ($work->end)
                        {{ $workData['work_time'] }}
                    @endif
                </td>
                <td class="table-data">
                    <a class="detail-link" href="/admin/attendance/{{$work->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection