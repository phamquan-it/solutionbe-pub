<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return redirect('/api/documentation');
});
Route::get('send-mail', [MailController::class, 'index']);
