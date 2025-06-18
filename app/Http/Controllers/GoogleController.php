<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {

            $user = Socialite::driver('google')->user(); 

            $findUserGoogleId = User::where('google_id', $user->getId())->first();

            if ($findUserGoogleId) {
                Auth::login($findUserGoogleId);
                return redirect()->intended('home');
            }

            $findUserEmail = User::where('email', $user->getEmail())->first();
            if ($findUserEmail) {
                Auth::login($findUserEmail);
                $findUserEmail->update([
                    'google_id' => $user->getId()
                ]);
                return redirect()->intended('home');
            }

             return redirect()->route('login')->withErrors('Akun tidak ditemukan.');

        } catch (\Throwable $th) {

             return redirect()->route('login')->withErrors('Akun tidak ditemukan.');

        }
    }
}
