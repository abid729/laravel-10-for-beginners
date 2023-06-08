<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use OpenAI\Laravel\Facades\OpenAI;


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

//Socialite Routes 
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callbackFromGoogle'])->name('google.callback');
Route::get('/auth/github/redirect', [SocialiteController::class, 'redirectToGithub'])->name('github.redirect');
Route::get('/auth/github/callback', [SocialiteController::class, 'callbackFromGithub'])->name('github.callback');
Route::get('/auth/twitter/redirect', [SocialiteController::class, 'redirectToTwitter'])->name('twitter.redirect');
Route::get('/auth/twitter/callback', [SocialiteController::class, 'callbackFromTwitter'])->name('twitter.callback');
Route::get('/auth/linkedin/redirect', [SocialiteController::class, 'redirectToLinkedin'])->name('linkedin.redirect');
Route::get('/auth/linkedin/callback', [SocialiteController::class, 'callbackFromLinkedin'])->name('linkedin.callback');


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
