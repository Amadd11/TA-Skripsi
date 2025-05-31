<?php

namespace App\Http\Controllers;

use App\Models\PemberianObat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function pemberian_obat()
    {
        $pemberianObat = PemberianObat::get();
        return view('dashboard.pemberian_obat', compact('pemberianObat'));
    }
}
