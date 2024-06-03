<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use JKD\SSO\Client\Provider\Keycloak;

class SSOController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new Keycloak([
            'authServerUrl' => config('services.keycloak.authServerUrl'),
            'realm' => config('services.keycloak.realm'),
            'clientId' => config('services.keycloak.clientId'),
            'clientSecret' => config('services.keycloak.clientSecret'),
            'redirectUri' => config('services.keycloak.redirectUri'),
        ]);
    }

    public function redirectToProvider()
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        Session::put('oauth2state', $this->provider->getState());

        return redirect($authUrl);
    }

    public function handleProviderCallback(Request $request)
    {
        // Mengecek state yang disimpan saat ini untuk memitigasi serangan CSRF
        if ($request->get('state') !== Session::get('oauth2state')) {
            Session::forget('oauth2state');
            return redirect('/')->with('error', 'Invalid state');
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);

            // Mendapatkan informasi pengguna
            $user = $this->provider->getResourceOwner($token);

            // Lakukan proses autentikasi ke aplikasi Anda
            $this->loginUser($user);

            // Redirect ke halaman yang diinginkan
            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to get access token: ' . $e->getMessage());
        }
    }

    protected function loginUser($user)
    {
        // Implementasikan logika untuk login pengguna ke aplikasi Anda
        // Misalnya, menggunakan id pengguna dari Keycloak untuk login ke aplikasi Anda

        $userModel = \App\Models\User::updateOrCreate(
            ['email' => $user->getEmail()],
            [
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                // Tambahkan atribut lain yang diperlukan
            ]
        );

        Auth::login($userModel, true);
    }

    public function logout()
    {
        Auth::logout();
        $redirectUrl = config('keycloak.logout_redirect_uri');
        return redirect()->away($redirectUrl);
    }
}
