<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\MasterClass;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showConfirmForm(MasterClass $masterClass): RedirectResponse|View
    {
        $user = Auth::user();
        
        if ($user->isInstructor()) {
            return redirect()->route('home')->with('error', 'Ведущие не могут записываться на мастер-классы.');
        }
        
        if ($masterClass->isPast()) {
            return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                ->with('error', 'Нельзя записаться на мастер-класс, который уже прошел.');
        }
        
        $alreadyBooked = Booking::where('user_id', $user->id)
            ->where('master_class_id', $masterClass->id)
            ->exists();
        
        if ($alreadyBooked) {
            return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс.');
        }
        
        if (!$masterClass->isAvailable()) {
            return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                ->with('error', 'На этот мастер-класс нет свободных мест.');
        }
        
        return view('booking.confirm', [
            'user' => $user,
            'masterClass' => $masterClass,
        ]);
    }


    public function confirm(Request $request, MasterClass $masterClass): RedirectResponse
    {
        $user = Auth::user();
        
        if ($user->isInstructor()) {
            return redirect()->route('home')->with('error', 'Ведущие не могут записываться на мастер-классы.');
        }
        
        // rкнопка "Отмена"
        if ($request->input('action') === 'cancel') {
            return redirect()->route('craft-type.show', $masterClass->craft_type_id)->with('info', 'Запись на мастер-класс отменена');
        }
        
        // кнопка "Подтвердить"
        if ($request->input('action') === 'confirm') {
            if (!$masterClass->isAvailable()) {
                return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                    ->with('error', 'Места на этот мастер-класс закончились.');
            }

            $alreadyBooked = Booking::where('user_id', $user->id)
                ->where('master_class_id', $masterClass->id)
                ->exists();
            
            if ($alreadyBooked) {
                return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                    ->with('error', 'Вы уже записаны на этот мастер-класс.');
            }
            
            // Создаем запись
            DB::transaction(function () use ($user, $masterClass) {
                Booking::create([
                    'user_id' => $user->id,
                    'master_class_id' => $masterClass->id,
                ]);
            });
            
            return redirect()->route('craft-type.show', $masterClass->craft_type_id)
                ->with('success', 'Вы успешно записались на мастер-класс!');
        }
        
        return redirect()->route('craft-type.show', $masterClass->craft_type_id);
    }
}