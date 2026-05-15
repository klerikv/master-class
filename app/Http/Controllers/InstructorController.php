<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateMasterClassRequest;
use App\Http\Requests\UpdateMasterClassRequest;
use App\Models\CraftType;
use App\Models\MasterClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(): View
    {
        $instructor = Auth::user();
        
        if (!$instructor->isInstructor()) {
            abort(403, 'Доступ только для ведущих мастер-классов.');
        }
        
        $masterClasses = $instructor->instructorMasterClasses()
            ->with('craftType', 'bookings.user')
            ->orderBy('date')
            ->orderBy('time_slot')
            ->get();
        
        $craftTypes = CraftType::all();
        
        return view('instructor.dashboard', compact('instructor', 'masterClasses', 'craftTypes'));
    }

    public function showCreateForm(): View
    {
        if (!Auth::user()->isInstructor()) {
            abort(403);
        }
        
        $craftTypes = CraftType::all();
        
        return view('instructor.create-master-class', compact('craftTypes'));
    }

    public function createMasterClass(CreateMasterClassRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        MasterClass::create([
            'craft_type_id' => $validated['craft_type_id'],
            'instructor_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time_slot' => $validated['time_slot'],
            'max_participants' => $validated['max_participants'],
            'price' => $validated['price'],
        ]);
        
        return redirect()->route('instructor.dashboard')
            ->with('success', 'Мастер-класс успешно добавлен!');
    }

    public function showEditForm(MasterClass $masterClass): View
    {
        $instructor = Auth::user();
        
        if (!$instructor->isInstructor() || $masterClass->instructor_id !== $instructor->id) {
            abort(403, 'У вас нет прав для редактирования этого мастер-класса.');
        }
        
        $craftTypes = CraftType::all();
        
        return view('instructor.edit-master-class', compact('masterClass', 'craftTypes'));
    }

    public function updateMasterClass(UpdateMasterClassRequest $request, MasterClass $masterClass): RedirectResponse
    {
        if ($masterClass->instructor_id !== Auth::id()) {
            abort(403, 'У вас нет прав для редактирования этого мастер-класса.');
        }
        
        $validated = $request->validated();
        
        $masterClass->update([
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);
        
        return redirect()->route('instructor.dashboard')
            ->with('success', 'Мастер-класс "' . $masterClass->title . '" успешно обновлён!');
    }

    public function checkOccupiedSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);
        
        $instructorId = Auth::id();
        $date = $request->date;
        
        $occupiedSlots = MasterClass::where('instructor_id', $instructorId)
            ->where('date', $date)
            ->pluck('time_slot')
            ->toArray();
        
        return response()->json([
            'success' => true,
            'occupied_slots' => $occupiedSlots,
            'date' => $date
        ]);
    }
}