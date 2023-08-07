<?php

namespace App\Http\Controllers;

use App\Models\PolydockAppType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolydockAppTypeController extends Controller
{
    public function index() 
    {
        $appTypes = PolydockAppType::all();

        return Inertia::render('PolydockAppTypes', [
            'polydockAppTypes' => $appTypes
        ]); 
    }
}
