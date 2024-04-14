<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $data = [
            'active' => 'dashboard'
        ];

        return view('admin.dashboard.index', $data);
    }
}
