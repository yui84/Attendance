<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AdminLoginRequest;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('admin.admin');
    }

    public function store(AdminLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect('/admin/attendance/list');
    }

    public function logout(Request $request)
    {
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}