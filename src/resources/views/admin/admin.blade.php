@extends('layouts.app')

@section('title', '管理者ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endsection

@section('content')
<div class="admin">
    <h2 class="title">
        管理者ログイン
    </h2>
    <div class="admin-inner">
        <form class="admin-form" action="/admin/login" method="POST">
            @csrf
            <div class="admin-form__group">
                <label class="admin-form__label" for="email">メールアドレス</label>
                <input class="admin-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
                <p class="error-message">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="admin-form__group">
                <label class="admin-form__label" for="password">パスワード</label>
                <input class="admin-form__input" type="password" name="password" id="password">
                <p class="error-message">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
            </div>

            <button class="admin-form__btn">管理者ログインする</button>
        </form>
    </div>
</div>
@endsection