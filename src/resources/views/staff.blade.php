@extends('layouts.app')

@section('title', 'スタッフ一覧')

@section('css')
<link rel="stylesheet" href="{{ asset('css/staff.css') }}">
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
<div class="staff">
    <h2 class="title-staff">
        スタッフ一覧
    </h2>


    <div class="staff_list">
        <table class="staff_table">
            <tr class="table-row">
                <th class="table-title">名前</th>
                <th class="table-title">メールアドレス</th>
                <th class="table-title">月次勤務</th>
            </tr>

            @foreach ($users as $user)
                <tr class="table-row">
                    <td class="table-data">{{ $user->name }}</td>
                    <td class="table-data">{{ $user->email }}</td>
                    <td class="table-data"><a class="link-detail" href="/admin/attendance/staff/{{ $user->id }}">詳細</a></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection