<?php

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Tampilkan halaman form kontak.
     */
    public function index(): View
    {
        return view('contact.index');
    }

    /**
     * Simpan pesan kontak ke database dan kirim notifikasi email ke admin.
     * Mengembalikan JSON agar bisa diproses oleh frontend fetch().
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Simpan ke database
        $contact = Contact::create($validated);

        // Kirim email notifikasi ke admin — tangkap error agar form tidak gagal
        // jika konfigurasi mail belum diset di environment
        try {
            $adminEmail = config('mail.from.address', 'admin@creplann.test');
            Mail::to($adminEmail)->send(new ContactNotification($contact));
        } catch (\Throwable $e) {
            // Log error tapi jangan gagalkan respons ke user
            Log::error('ContactNotification mail failed: '.$e->getMessage());
        }

        return response()->json([
            'message' => 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.',
        ], 201);
    }
}
