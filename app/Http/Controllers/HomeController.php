<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CraftType;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $craftTypes = CraftType::with('masterClasses')->get();
        
        $userBookings = [];
        if (Auth::check() && Auth::user()->isVisitor()) {
            $userBookings = Auth::user()
                ->bookings()
                ->with('masterClass.craftType', 'masterClass.instructor')
                ->get();
        }
        
        return view('home', compact('craftTypes', 'userBookings'));
    }
}