<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Http\Controllers\KadoController;

class LoginController extends Controller
{
    public function redirectToGoogle()
    {
        // Google へのリダイレクト
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $gUser = Socialite::driver('google')->user();
        // email が合致するユーザーを取得
        $user = User::where('email', $gUser->email)->first();
        // 見つからなければ新しくユーザーを作成
        if ($user == null) {
            $user = $this->createUserByGoogle($gUser);
        }
        // ログイン処理
        Auth::login($user, true);
        $kadoController = new KadoController;
        $current_year_month = date('Y/m');
        $current_term_name = $kadoController->convertYearMonthIntoTeam($current_year_month);
        $current_term_id = $kadoController->getTermId($current_term_name);
        return redirect('/kado/'.$user->id.'/'.$current_term_id);
    }

    public function createUserByGoogle($gUser)
    {
        $user = User::create([
            'name'     => $gUser->name,
            'email'    => $gUser->email,
            'password' => Hash::make(uniqid()),
        ]);
        return $user;
    }

    public function home() {
        $user = Auth::user();

        return view('layouts.app');
    }
}
