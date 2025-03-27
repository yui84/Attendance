<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;

class RegisteredUserController
{
    public function store(
        Request $request,
        CreateNewUser $creator
    ) {
        event(new Registered($user = $creator->create($request->all())));
        session()->put('unauthenticated_user', $user);
        return redirect()->route('verification.notice');
    }

}
