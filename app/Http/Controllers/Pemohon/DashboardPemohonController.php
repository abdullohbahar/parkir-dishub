<?php

namespace App\Http\Controllers\Pemohon;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailNotification;
use Illuminate\Http\Request;

class DashboardPemohonController extends Controller
{
    public function index()
    {
        return view('pemohon.dashboard.index');
    }

    public function tesEmail()
    {
        $id = auth()->user()->id;
        $user = User::findorfail($id);

        $user->notify(new EmailNotification());
        dd($user);
    }
}
