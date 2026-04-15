<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NeedController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ExchangeRequestController;
use App\Http\Controllers\LearningSessionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposedTimeController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
    Route::patch('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

    Route::get('/needs', [NeedController::class, 'index'])->name('needs.index');
    Route::post('/needs', [NeedController::class, 'store'])->name('needs.store');
    Route::patch('/needs/{need}', [NeedController::class, 'update'])->name('needs.update');
    Route::patch('/needs/{need}/close', [NeedController::class, 'close'])->name('needs.close');
    Route::delete('/needs/{need}', [NeedController::class, 'destroy'])->name('needs.destroy');
    Route::get('/exchange-requests', [ExchangeRequestController::class, 'index'])->name('exchange-requests.index');
    Route::get('/exchange-requests/create', [ExchangeRequestController::class, 'create'])->name('exchange-requests.create');
    Route::post('/exchange-requests', [ExchangeRequestController::class, 'store'])->name('exchange-requests.store');
    Route::get('/exchange-requests/{exchangeRequest}', [ExchangeRequestController::class, 'show'])->name('exchange-requests.show');
    Route::patch('/exchange-requests/{exchangeRequest}/accept', [ExchangeRequestController::class, 'accept'])->name('exchange-requests.accept');
    Route::patch('/exchange-requests/{exchangeRequest}/refuse', [ExchangeRequestController::class, 'refuse'])->name('exchange-requests.refuse');
    Route::patch('/exchange-requests/{exchangeRequest}/cancel', [ExchangeRequestController::class, 'cancel'])->name('exchange-requests.cancel');

    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/exchange-requests/{exchangeRequest}/proposed-times/create', [ProposedTimeController::class, 'create'])->name('proposed-times.create');
    Route::post('/exchange-requests/{exchangeRequest}/proposed-times', [ProposedTimeController::class, 'store'])->name('proposed-times.store');
    Route::patch('/proposed-times/{proposedTime}/select', [ProposedTimeController::class, 'select'])->name('proposed-times.select');

    Route::get('/learning-sessions', [LearningSessionController::class, 'index'])->name('learning-sessions.index');
    Route::get('/learning-sessions/{learningSession}', [LearningSessionController::class, 'show'])->name('learning-sessions.show');
    Route::patch('/learning-sessions/{learningSession}/confirm-completion', [LearningSessionController::class, 'confirmCompletion'])->name('learning-sessions.confirm-completion');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
