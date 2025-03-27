<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/create', [CampaignController::class, 'create']);
Route::post('/campaigns', [CampaignController::class, 'store']);
Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
Route::resource('campaigns', CampaignController::class);
Route::post('/campaigns/{campaign}/sendmail', [CampaignController::class, 'sendmail'])->name('campaigns.sendmail');

Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboards.index') ;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');

// Route::get('/login', function () {
//     return view('auth.login');
// })->name('login');

// require __DIR__.'/auth.php';
Auth::routes([
    'register' => true,
]);

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/home', function () {
    return redirect('/dashboards');
});

Route::get('/contact', function () {
    return view('contact');
});

// Route::post('/contact', [TestMailController::class, 'store']);


Route::get('/send-test-mail', function () {
    $data = [
        'name' => 'テスト太郎',
        'email' => 'test@example.com',
        'subject' => 'これはテスト件名です',
        'message' => "これはテストの本文です。\n改行もOK!"
    ];
    Mail::to('test@example.com')->send(new TestMail($data));
    return 'メール送信完了！';
});


