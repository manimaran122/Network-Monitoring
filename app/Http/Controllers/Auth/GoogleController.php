<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

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
            $finduser = User::where('google_id', $user->id)->first();

            if($finduser){
                Auth::login($finduser);
                return redirect()->route('dashboard');
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                    'name' => $user->name,
                    'google_id'=> $user->id,
                    'password' => null, // Password can be null for Google users
                    'status' => true,
                    'must_change_password' => false,
                    'password_changed_at' => now(), // Initialized
                ]);

                Auth::login($newUser);
                return redirect()->route('dashboard');
            }

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login.');
        }
    }
}
