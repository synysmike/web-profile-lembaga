<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAccountController extends Controller
{
    public function edit()
    {
        return view('admin.account');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Kata sandi sekarang wajib diisi.',
            'current_password.current_password' => 'Kata sandi sekarang tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru paling pendek 8 karakter.',
            'password.confirmed' => 'Ulangan kata sandi belum sama.',
        ]);

        $request->user()->update([
            'password' => $data['password'],
        ]);

        return redirect()->route('admin.account')->with('status', 'Kata sandi sudah diganti.');
    }
}
