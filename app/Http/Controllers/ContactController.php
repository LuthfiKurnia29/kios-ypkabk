<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        // Honeypot check
        if ($request->filled('website')) {
            return redirect()->route('kontak');
        }

        ContactMessage::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'status' => 'new',
        ]);

        return redirect()->route('kontak')
            ->with('success', 'Pesan Anda sudah kami terima, kami balas dalam 1x24 jam.');
    }

    public function adminIndex(Request $request): View
    {
        // Otomatis tandai pesan baru sebagai dibaca saat admin membuka halaman pesan masuk,
        // kecuali admin baru saja mengubah statusnya secara manual via toggleRead.
        if (! $request->session()->has('status_toggled')) {
            ContactMessage::where('status', 'new')->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        $messages = ContactMessage::orderByDesc('created_at')->paginate(20);
        $newCount = ContactMessage::where('status', 'new')->count();

        return view('admin.pages.messages', compact('messages', 'newCount'));
    }

    public function adminShow(ContactMessage $message): View
    {
        if ($message->status === 'new') {
            $message->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return view('admin.pages.message-show', compact('message'));
    }

    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        if ($message->status === 'new') {
            $message->update(['status' => 'read', 'read_at' => now()]);
        } else {
            $message->update(['status' => 'new', 'read_at' => null]);
        }

        return redirect()->back()
            ->with('status_toggled', true)
            ->with('success', 'Status pesan diperbarui.');
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
