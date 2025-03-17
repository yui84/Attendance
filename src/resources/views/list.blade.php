@extends('layouts.app')

@section('title', '勤怠一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/list.css') }}">
@endsection

@section('link')
<div class="link">
    <a class="attendance" href="/attendance">勤怠</a>
    <a class="list" href="/attendance/list">勤怠一覧</a>
    <a class="detail" href="/stamp_correction_request/list">申請</a>
    <form class="logout-form" action="/logout" method="POST">
        @csrf
        <button class="logout-form__button">ログアウト</button>
    </form>
</div>
@endsection

@section('content')
<div class="list-content">
    <h2 class="title-list">
        勤怠一覧
    </h2>

    <div class="month">
        <form method="GET" action="{{ route('attendance.list') }}">
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
                $days = ['日','月','火','水','木','金','土'];
                $date = \Carbon\Carbon::parse($work->date)->format('Y-m-d');
            @endphp
                <tr class="table-row">
                    <td class="table-data">
                        {{ \Carbon\Carbon::parse($work->date)->format('m/d') }}({{ $days[\Carbon\Carbon::parse($work->date)->dayOfWeek] }})
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

                    <td class="table-data"><a class="detail-link" href="/attendance/{{$work->id}}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection