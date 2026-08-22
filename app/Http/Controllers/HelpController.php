<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HelpController extends Controller
{
    /**
     * Tampilkan halaman pusat bantuan (static — tidak perlu database).
     */
    public function index(): View
    {
        return view('help.index');
    }
}
