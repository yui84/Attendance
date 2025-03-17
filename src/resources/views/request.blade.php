@extends('layouts.app')

@section('title', '申請')

@section('css')
<link rel="stylesheet" href="{{ asset('css/request.css') }}">
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
<div class="request-content">
    <h2 class="title-request">
        申請一覧
    </h2>

    <div class="border">
        <ul class="border__list">
            <li><a href="{{ route('request.list', ['tab'=>'wait']) }}">承認待ち</a></li>
            <li><a href="{{ route('request.list', ['tab'=>'check']) }}">承認済み</a></li>
        </ul>
    </div>

    <div class="request-table">
        <table class="table">
            <tr class="table-row">
                <th class="table-title">状態</th>
                <th class="table-title">名前</th>
                <th class="table-title">対象日時</th>
                <th class="table-title">申請理由</th>
                <th class="table-title">申請日時</th>
                <th class="table-title">詳細</th>
            </tr>

            @foreach ($corrections as $correction)
            <tr class="table-row">
                <td class="table-data">
                    {{ $correction->status == 'pending' ? '承認待ち' : '承認済み'}}
                </td>

                <td class="table-data">
                    {{ $correction->work->user->name }}
                </td>

                <td class="table-data">
                    {{ \Carbon\Carbon::parse($correction->work->date)->format('Y/m/d') }}
                </td>

                <td class="table-data">
                    {{ $correction->note }}
                </td>

                <td class="table-data">
                    {{ \Carbon\Carbon::parse($correction->created_at)->format('Y/m/d') }}
                </td>

                <td class="table-data">
                    <a class="detail-btn" href="/attendance/{{ $correction->work_id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection