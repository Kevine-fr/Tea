<?php
// ═══════════════════════════════════════════════════════
// app/Http/Controllers/Web/HomeController.php
// ═══════════════════════════════════════════════════════
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
}