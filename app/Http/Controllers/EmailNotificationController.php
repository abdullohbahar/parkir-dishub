<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\EmailNotification;

class EmailNotificationController extends Controller
{
    public function sendEmail($userID, $message)
    {
        $user = User::where('id', $userID)->first();

        $user->notify(new EmailNotification($message));
    }
}
