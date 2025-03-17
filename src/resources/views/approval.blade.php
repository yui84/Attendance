@extends('layouts.app')

@section('title', '修正申請承認')

@section('css')
<link rel="stylesheet" href="{{ asset('css/approval.css') }}">
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
<div class="approval">
    <h2 class="title-approval">
        勤怠詳細
    </h2>

    <div class="approval-content">
        <form action="{{ route('approval.post', $correction) }}" class="approval_form" method="POST">
            @csrf
            <table class="approval_table">
                <tr class="table-row">
                    <th class="table-title">名前</th>
                    <td class="table-data">
                        {{ $user->name }}
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">日付</th>
                    <td class="table-data">
                        {{ \Carbon\Carbon::parse($work->date)->format('Y年 n月j日') }}
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">出勤・退勤</th>
                    <td class="table-data">
                        {{ \Carbon\Carbon::parse($correction->start)->format('H:i') }} ~ {{ \Carbon\Carbon::parse($correction->end)->format('H:i') }}
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">休憩</th>
                    <td class="table-data">
                        <p>{{ \Carbon\Carbon::parse($application->start)->format('H:i') }} ~ {{ \Carbon\Carbon::parse($application->end)->format('H:i') }}</p>
                    </td>
                </tr>

                <tr class="table-row">
                    <th class="table-title">備考</th>
                    <td class="table-data">
                        {{ $correction->note }}
                    </td>
                </tr>
            </table>

            <div class="bottom">
                @if ($correction->status != 'approved')
                    <button class="approval_btn" type="submit">承認</button>
                @else
                    <button class="approved_btn" disabled>承認済み</button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection