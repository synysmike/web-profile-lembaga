<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama paling panjang 120 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email belum berbentuk alamat surel.',
            'message.required' => 'Pesan wajib diisi.',
            'message.max' => 'Pesan paling panjang 2000 karakter.',
        ]);

        ContactMessage::query()->create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'message' => trim($data['message']),
        ]);

        return back()->with('status', 'Pesan sudah diterima.');
    }
}
