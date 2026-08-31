<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profil — termasuk upload foto avatar.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->safe()->except(['avatar', 'remove_avatar']);

        // ── Hapus avatar (klik tombol hapus foto) ────────────────
        if ($request->boolean('remove_avatar')) {
            $this->deleteStoredAvatar($user->avatar);
            $validated['avatar'] = null;
        }

        // ── Upload avatar baru ────────────────────────────────────
        elseif ($request->hasFile('avatar')) {
            // Hapus file lama (hanya jika tersimpan di disk lokal, bukan URL Google)
            $this->deleteStoredAvatar($user->avatar);

            // Simpan path relatif (misal: avatars/xxxx.jpg)
            // Rendering pakai Storage::url($path) atau asset('storage/'.$path)
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun pengguna beserta semua datanya.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus avatar dari storage sebelum hapus akun
        $this->deleteStoredAvatar($user->avatar);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Hapus file avatar dari disk lokal.
     * - URL Google (lh3.googleusercontent.com, dll.) → skip, jangan hapus
     * - Path relatif (avatars/xxx.jpg) → hapus dari disk public
     * - URL absolut milik storage lokal → ekstrak path dan hapus
     */
    private function deleteStoredAvatar(?string $avatar): void
    {
        if (! $avatar) {
            return;
        }

        // Path relatif langsung (hasil upload baru)
        if (! str_starts_with($avatar, 'http://') && ! str_starts_with($avatar, 'https://')) {
            if (Storage::disk('public')->exists($avatar)) {
                Storage::disk('public')->delete($avatar);
            }
            return;
        }

        // URL absolut — cek apakah milik storage lokal kita
        $storageBase = rtrim(Storage::disk('public')->url(''), '/');
        if (! str_starts_with($avatar, $storageBase)) {
            // URL eksternal (Google, dll.) — jangan hapus
            return;
        }

        // Ekstrak path relatif dari URL lokal lama
        $relativePath = ltrim(str_replace($storageBase, '', $avatar), '/');
        if ($relativePath && Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
