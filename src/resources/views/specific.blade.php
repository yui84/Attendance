@extends('layouts.app')

@section('title', '勤怠詳細(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/specific.css') }}">
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
<div class="work_specific">
    <h2 class="title-specific">
        勤怠詳細
    </h2>

    <div class="specific">
        <form action="{{ route('work.update', $work->id) }}" method="POST">
            @csrf
            <input type="hidden" name="work_id" value="{{ $work->id }}">

            <table class="table">
                <tr class="table-row">
                    <th class="table-title">
                        名前
                    </th>
                    <td class="table-data">
                        {{ $work->user->name }}
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">
                        日付
                    </th>
                    <td class="table-data">
                        {{ \Carbon\Carbon::parse($work->date)->format('Y年 n月j日') }}
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">
                        出勤・退勤
                    </th>
                    <td class="table-data">
                        @if ($correction && $correction->status == 'pending')
                            <span>{{ \Carbon\Carbon::parse($correction->start)->format('H:i') }} ~ {{ \Carbon\Carbon::parse($correction->end)->format('H:i') }}</span>
                        @else
                            <input class="input-start" type="text" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($work->start)->format('H:i')) }}"> ~ 
                            <input class="input-end" type="text" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($work->end)->format('H:i')) }}">
                        @endif
                        <div class="error-message">
                            @error('start_time')
                                <p class="error-message-start-time">{{ $message }}</p>
                            @enderror
                            @error('end_time')
                                <p class="error-message-end-time">{{ $message }}</p>
                            @enderror
                        </div>
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">
                        休憩
                    </th>
                    <td class="table-data">
                        @if ($correction && $correction->status == 'pending')
                            <span>{{ \Carbon\Carbon::parse($correction->application->start)->format('H:i') }} ~ {{ \Carbon\Carbon::parse($correction->application->end)->format('H:i') }}</span><br>
                        @else
                            @foreach ($rests as $rest)
                                <input class="input-start" type="text" name="start_rest" value="{{ old('start_rest', \Carbon\Carbon::parse($rest->start)->format('H:i')) }}"> ~
                                <input class="input-end" type="text" name="end_rest" value="{{ old('end_rest', \Carbon\Carbon::parse($rest->end)->format('H:i')) }}">
                                <div class="error-message">
                                    @error('start_rest')
                                        <p class="error-message-rest-start">{{ $message }}</p>
                                    @enderror
                                    @error('end_rest')
                                        <p class="error-message-rest-end">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">
                        備考
                    </th>
                    <td class="table-data">
                        @if ($correction && $correction->status == 'pending')
                            <span>{{ $correction->note }}</span>
                        @else
                            <textarea class="note" name="note" id="note" cols="30" rows="10">{{ old('note') }}</textarea>
                            <p class="error-message">
                                @error('note')
                                    {{ $message }}
                                @enderror
                            </p>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="bottom">
                @if ($correction && $correction->status == 'pending')
                    <p class="pending-message">*修正申請中です。</p>
                @else
                    <button class="correct" type="submit">修正</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection