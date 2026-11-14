<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;

class AdminMessageController extends Controller
{
    public function index()
    {
        return view('admin.messages', [
            'messages' => ContactMessage::query()
                ->orderByRaw('case when read_at is null then 0 else 1 end')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function read(ContactMessage $message)
    {
        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return redirect()->route('admin.messages')->with('status', 'Pesan ditandai sudah dibaca.');
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('admin.messages')->with('status', 'Pesan sudah dihapus.');
    }
}
