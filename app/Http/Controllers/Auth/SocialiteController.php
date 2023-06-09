<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AppLogs;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
// use Jenssegers\Agent\Facades\Agent;
use Jenssegers\Agent\Agent;

class SocialiteController extends Controller
{
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }


    public function redirectToGoogle()
    {
        $device = $this->agent->getUserAgent();
        $device = $this->agent->getIp();
        dd($device);
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle(Request $request)
    {
        // dd($request->getClientIp());
        // $data = $request->all();

    
    $data['entry_id'] = '11';
    $data['action'] = '11';
    $data['action'] = '11';
    $data['description'] = '11';
    $data['url'] = '11';
    $data['device_details'] = '11';
    $data['action_by'] = '1';
   
    $newRecord = createNewRecord(AppLogs::class, $data);
    dd($newRecord);
        try {
            $user = Socialite::driver('google')->user();

            Session::put('socialite_token', $user->token);

            $user = User::updateOrCreate(
                [
                    'email' => $user->email
                ],
                [
                    'name' => $user->name,
                    'password' => "password"
                ]
            );

            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callbackFromGithub(Request $request)
    {
        $user = Socialite::driver('github')->user();
        Session::put('socialite_token', $user->token);
        $user = User::updateOrCreate(
            [
                'email' => $user->email
            ],
            [
                'name' => $user->name,
                'password' => "password"
            ]
        );
        return Socialite::driver('github')
            ->scopes(['read:user', 'public_repo'])
            ->redirect();
        Auth::login($user);
        return redirect('/dashboard');
    }

    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function callbackFromTwitter(Request $request)
    {
        try {
            $user = Socialite::driver('twitter')->user();
            Session::put('socialite_token', $user->token);
            $user = User::updateOrCreate(
                [
                    'email' => "abidali31570@gmail.com"
                ],
                [
                    'name' => $user->name,
                    'password' => "password"
                ]
            );
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectToLinkedin()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callbackFromLinkedin(Request $request)
    {
        try {
            $user = Socialite::driver('linkedin')->user();
            Session::put('socialite_token', $user->token);
            $user = User::updateOrCreate(
                [
                    'email' => $user->email
                ],
                [
                    'name' => $user->name,
                    'password' => "password"
                ]
            );
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
