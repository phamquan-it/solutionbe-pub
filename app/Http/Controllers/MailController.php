<?php

namespace App\Http\Controllers;

use App\Mail\SolutionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            'title' => 'Mail from Solution',
            'body' => 'This is for testing email using smtp.'
        ];
        $mailSended = Mail::to('quanqqq11@gmail.com')->send(new SolutionMail($mailData));
        print_r($mailSended);
    }
}
