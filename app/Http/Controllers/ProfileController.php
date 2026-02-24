<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        // eager load social accounts
        $user->load('socialAccounts');
        return view('profile.show', compact('user'));
    }

    public function unlink(Request $request, $provider)
    {
        $user = Auth::user();
        $allowed = ['google','facebook'];
        if (!in_array($provider, $allowed)) {
            return redirect()->route('profile.show')->with('error','Provider tidak valid.');
        }

        // only unlink if matched
        $removed = false;
        // delete social account records
        foreach ($user->socialAccounts as $sa) {
            if ($sa->provider === $provider) {
                $sa->delete();
                $removed = true;
            }
        }

        // clear provider fields on user if they match
        if ($user->provider === $provider) {
            $user->provider = null;
            $user->provider_id = null;
            $user->save();
            $removed = true;
        }

        if ($removed) {
            return redirect()->route('profile.show')->with('success','Akun ' . ucfirst($provider) . ' telah di-unlink.');
        }

        return redirect()->route('profile.show')->with('error','Tidak ada akun ' . ucfirst($provider) . ' yang tertaut.');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($user->foto && file_exists(public_path('storage/' . $user->foto))) {
                unlink(public_path('storage/' . $user->foto));
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('profile', 'public');
            $user->foto = $fotoPath;
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile berhasil diperbarui!');
    }
}
