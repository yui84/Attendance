@extends('layouts.app')

@section('title', '会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register">
    <h1 class="title">
        会員登録
    </h1>
    <div class="register-inner">
        <form class="register-form" action="/register" method="POST">
            @csrf
            <div class="register-form__group">
                <label for="name" class="entry__name">氏名</label>
                <input name="name" id="name" type="text" class="input" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="register-form__group">
                <label for="mail" class="entry__name">メールアドレス</label>
                <input name="email" id="mail" type="email" class="input" value="{{ old('email') }}">
                <div class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="register-form__group">
                <label for="password" class="entry__name">パスワード</label>
                <input name="password" id="password" type="password" class="input">
                <div class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            <div class="register-form__group">
                <label for="password_confirm" class="entry__name">確認用パスワード</label>
                <input name="password_confirmation" id="password_confirm" type="password" class="input">
            </div>

            <button class="register-form__btn">登録する</button>
            <a class="login-link" href="/login">ログインはこちら</a>
        </form>
    </div>
</div>
@endsection