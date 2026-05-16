<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\CraftType;
use Illuminate\View\View;

class CraftTypeController extends Controller
{
    public function show(CraftType $craftType): View
    {
        $masterClasses = $craftType->masterClasses()
            ->with('instructor', 'bookings.user')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();

        $craftTypes = CraftType::all();

        return view('craft-type.show', compact('craftType', 'masterClasses', 'craftTypes'));
    }
}
