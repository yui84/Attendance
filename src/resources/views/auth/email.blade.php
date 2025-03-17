@extends('layouts.app')

@section('title', 'メール認証')

@section('css')
<link rel="stylesheet" href="{{ asset('css/email.css') }}">
@endsection

@section('content')
<div class="mail-notice">
    <div class="mail__header">
        <p class="notice__header">
            登録していただいたメールアドレスに認証メールを送付しました。<br>メール認証を完了してください。
        </p>
    </div>

    <div class="mail-btn">
        <a class="mail-link" href="">認証はこちらから</a>
    </div>

    <div class="mail__content">
        @if (session('resent'))
            <p class="notice__resend" role="alert">
                新規認証メールを再送信しました。
            </p>
        @endif
        <div class="alert_resend">
            <form class="mail_form" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="mail_resend--button" type="submit">認証メールを再送する</button>
            </form>
        </div>
    </div>
</div>
@endsection