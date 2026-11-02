<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /** Petugas memakai dashboard staf yang sama dengan admin. */
    public function index()
    {
        return app(AdminDashboardController::class)->index();
    }
}
