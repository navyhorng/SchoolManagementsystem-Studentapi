<?php

use App\Mail\PasswordOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', function () {
    Mail::to('nitakong1122@gmail.com')
        ->send(new PasswordOtpMail('123456'));

    return 'Mail sent';
});
