@extends('layouts.app')

@section('title', '勤怠登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('link')
<div class="link">
    @if ($status == 3)
        <a class="list" href="/attendance/list">今月の出勤一覧</a>
        <a class="detail" href="/stamp_correction_request/list">申請一覧</a>
        <form class="logout-form" action="/logout" method="POST">
            @csrf
            <button class="logout-form__button">ログアウト</button>
        </form>
    @else
        <a class="attendance" href="/attendance">勤怠</a>
        <a class="list" href="/attendance/list">勤怠一覧</a>
        <a class="detail" href="/stamp_correction_request/list">申請</a>
        <form class="logout-form" action="/logout" method="POST">
            @csrf
            <button class="logout-form__button">ログアウト</button>
        </form>
    @endif
</div>
@endsection

@section('content')
<div class="attendance-content">
    <div class="status">
        @if ($status == 1)
            勤務中
        @elseif ($status == 2)
            休憩中
        @elseif ($status == 3)
            退勤済
        @else
            勤務外
        @endif
    </div>

    <div class="date">
        <div class="year">
            {{ now()->format('Y年n月j日') }}
        </div>
        <div class="week">
            @php
                $days = ['日','月','火','水','木','金','土'];
                $dayOfWeek = \Carbon\Carbon::now()->dayOfWeek;
            @endphp
            ({{ $days[$dayOfWeek] }})
        </div>
    </div>

    <div class="time">
        {{ now()->format('H:i') }}
    </div>

    <div class="attendance-work">
        <form class="attendance-form" action="{{ route('work') }}" method="POST">
            @csrf
            <div class="form-item">
                @if ($status == 1)
                    <div class="form-item__work">
                        <button class="form__item-button" type="submit" name="end_work">退勤</button>
                        <button class="form__item-button--rest-in" type="submit" name="start_rest">休憩入</button>
                    </div>
                @elseif ($status == 2)
                    <button class="form__item-button--rest-out" type="submit" name="end_rest">休憩戻</button>
                @elseif ($status == 3)
                    <p class="attendance-end">お疲れ様でした。</p>
                @else
                    <button class="form__item-button" type="submit" name="start_work">出勤</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection