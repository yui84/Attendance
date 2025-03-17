@extends('layouts.app')

@section('title', '申請一覧(管理者)')

@section('css')
<link rel="stylesheet" href="{{ asset('css/apply.css') }}">
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
<div class="apply">
    <h2 class="title-apply">
        申請一覧
    </h2>

    <div class="border">
        <ul class="border__list">
            <li><a href="{{ route('apply.list', ['tab'=>'wait']) }}">承認待ち</a></li>
            <li><a href="{{ route('apply.list', ['tab'=>'check']) }}">承認済み</a></li>
        </ul>
    </div>

    <div class="apply-table">
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
                <td class="table-date">
                    {{$correction->status == 'pending' ? '承認待ち' : '承認済み'}}
                </td>

                <td class="table-date">
                    {{ $correction->work->user->name }}
                </td>

                <td class="table-date">
                    {{ \Carbon\Carbon::parse($correction->work->date)->format('Y/m/d') }}
                </td>

                <td class="table-date">
                    {{ $correction->note }}
                </td>

                <td class="table-date">
                    {{ \Carbon\Carbon::parse($correction->created_at)->format('Y/m/d') }}
                </td>

                <td class="table-date">
                    <a class="detail-link" href="/admin/stamp_correction_request/approve/{{ $correction->id }}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection