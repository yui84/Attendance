@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login">
    <h1 class="title">
        ログイン
    </h1>
    <div class="login-inner">
        <form class="login-form" action="/login" method="POST">
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="email">メールアドレス</label>
                <input class="login-form__input" type="email" name="email" id="mail" value="{{ old('email') }}">
                <p class="error-message">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" name="password" id="password">
                <p class="error-message">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="login-form__btn">ログインする</button>
            <a class="register-link" href="/register">会員登録はこちら</a>
        </form>
    </div>
</div>
@endsection