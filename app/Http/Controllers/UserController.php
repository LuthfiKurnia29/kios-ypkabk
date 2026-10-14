<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display the user landing page.
     */
    public function index(): View
    {
        return view('user.pages.home');
    }

    /**
     * Display the catalog page.
     */
    public function katalog(): View
    {
        return view('user.pages.katalog');
    }
}
