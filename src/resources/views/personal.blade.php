@extends('layouts.app')

@section('title', 'スタッフ月次別勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/personal.css') }}">
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
<div class="personal">
    <h2 class="title-personal">
        {{ $user->name }}さんの勤怠
    </h2>

    <div class="month">
        <form action="{{ route('staff.show', ['user' => $user]) }}" method="GET">
            @csrf
            <div class="calender-date">
                <button class="prev" type="submit" name="prevMonth" value="prev" id="prevMonth">← 前月</button>
                <div class="today">
                    <img class="calender-icon" src="{{ asset('/images/calender-icon.png') }}" alt="">
                    <p class="date" id="currentDate">
                        {{ $currentDate }}
                    </p>
                </div>
                <button class="next" type="submit" name="nextMonth" value="next" id="nextMonth">翌月 →</button>
                <input type="hidden" name="currentDate" value="{{ $currentDate }}">
            </div>
        </form>
    </div>

    <div class="work">
        <table class="work-table">
            <tr class="table-row">
                <th class="table-title">日付</th>
                <th class="table-title">出勤</th>
                <th class="table-title">退勤</th>
                <th class="table-title">休憩</th>
                <th class="table-title">合計</th>
                <th class="table-title">詳細</th>
            </tr>

            @foreach ($works as $work)
            @php
                $date = \Carbon\Carbon::parse($work->date)->format('Y-m-d');
            @endphp
            <tr class="table-row">
                <td class="table-data">
                    {{ \Carbon\Carbon::parse($work->date)->format('m/d') }}
                </td>

                <td class="table-data">
                    {{ \Carbon\Carbon::parse($work->start)->format('H:i') }}
                </td>

                <td class="table-data">
                    @if ($work->end)
                        {{ \Carbon\Carbon::parse($work->end)->format('H:i') }}
                    @else
                        <span></span>
                    @endif
                </td>

                <td class="table-data">
                    @if (array_key_exists($date, $restHours) && $work->end)
                        {{ $restHours[$date] }}:{{ str_pad($restMinutes[$date], 2, '0',STR_PAD_LEFT) }}
                    @else
                        <span></span>
                    @endif
                </td>

                <td class="table-data">
                    @if (array_key_exists($date, $workHours) && $work->end)
                        {{ $workHours[$date] }}:{{ str_pad($workMinutes[$date], 2, '0',STR_PAD_LEFT) }}
                    @else
                        <span></span>
                    @endif
                </td>

                <td class="table-data"><a class="link-detail" href="/admin/attendance/{{$work->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>

    <div class="export-form">
        <form class="form" action="{{ route('staff.export', ['user' => $user->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="currentDate" value="{{ $currentDate }}">
            <input class="export__btn" type="submit" value="CSV出力">
        </form>
    </div>
</div>
@endsection