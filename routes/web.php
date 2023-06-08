<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');

});
Route::get('/test-session', function () {
    Session::put('test', 'Session Test');
    $value = Session::get('test');
    dd($value);
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Tickets 
    Route::resource('ticket', TicketController::class);
});

require __DIR__.'/auth.php';

Route::get('/openai', function () {
    $result = OpenAI::completions()->create([
        'model' => 'text-davinci-003',
        'prompt' => 'PHP is',
    ]);
     
    echo $result['choices'][0]['text']; 
});

//Github
Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
});
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();

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
});


Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function (Request $request) {
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
});

Route::get('/auth/twitter/redirect', function () {
    return Socialite::driver('twitter')->redirect();
});

Route::get('/auth/twitter/callback', function () {
    try {
        $user = Socialite::driver('twitter')->user();
        // dd($user);
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
});


Route::get('/auth/linkdin/redirect', function () {
    return Socialite::driver('linkedin')->redirect();
});

Route::get('/auth/linkdin/callback', function () {
    try {
        $user = Socialite::driver('linkedin')->user();
        // dd($user);
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
});


