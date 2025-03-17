@extends('layouts.app')

@section('title', '勤怠詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
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
<div class="work-detail">
    <h2 class="detail-title">
        勤怠詳細
    </h2>

    <div class="detail-form">
        <form class="form" action="{{ route('attendance.correct') }}" method="POST">
            @csrf
            <input type="hidden" name="work_id" value="{{ $work->id }}">
            <input type="hidden" name="status" value="pending">

            <table class="table">
                <tr class="table-row">
                    <th class="table-title">氏名</th>
                    <td class="table-data">{{ $work->user->name }}</td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">日付</th>
                    <td class="table-data">{{ \Carbon\Carbon::parse($work->date)->format('Y年 n月j日') }}</td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">出勤・退勤</th>
                    <td class="table-data">
                        @if ($pendingCorrection)
                            {{ \Carbon\Carbon::parse($pendingCorrection->start)->format('H:i') }} ~ 
                            {{ \Carbon\Carbon::parse($pendingCorrection->end)->format('H:i') }}
                        @else
                            <input class="input-start" type="text" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($work->start)->format('H:i')) }}"> ~
                            <input class="input-end" type="text" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($work->end)->format('H:i')) }}">
                            <div class="error-message">
                                @error('start_time')
                                    <p class="error-message-start-time">{{ $message }}</p>
                                @enderror
                                @error('end_time')
                                    <p class="error-message-end-time">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </td>
                </tr>

                <tr class="table-row rest">
                    @foreach ($rests as $key => $rest)
                        <th class="table-title">休憩</th>
                        <td class="table-data">
                            @if ($pendingCorrection)
                                {{ \Carbon\Carbon::parse($application->start)->format('H:i') }} ~ 
                                {{ \Carbon\Carbon::parse($application->end)->format('H:i') }}
                            @else
                                <input type="hidden" name="rest_id[{{ $key }}]" value="{{ $rest->id }}">
                                <input class="input-start" type="text" name="rest_start[{{ $key }}]" value="{{ old('rest_start.' . $key, \Carbon\Carbon::parse($rest->start)->format('H:i')) }}"> ~
                                <input class="input-end" type="text" name="rest_end[{{ $key }}]" value="{{ old('rest_end.' . $key, \Carbon\Carbon::parse($rest->end)->format('H:i')) }}">
                                <div class="error-message">
                                    @error('rest_start.' . $key)
                                        <p class="error-message-rest-start">{{ $message }}</p>
                                    @enderror
                                    @error('rest_end.' . $key)
                                        <p class="error-message-rest-end">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>

                <tr class="table-row">
                    <th class="table-title">備考</th>
                    <td class="table-data">
                        @if ($pendingCorrection)
                        <span>{{ $pendingCorrection->note }}</span>
                        @else
                            <textarea class="textarea" name="note" id="note" cols="30" rows="10">{{ old('note') }}</textarea>
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
                @if ($pendingCorrection)
                    <p class="pending">*承認待ちのため修正はできません。</p>
                @else
                    <button class="correct" type="submit">修正</button>
                @endif
            </div>

        </form>
    </div>
</div>
@endsection