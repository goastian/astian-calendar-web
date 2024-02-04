<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller as Controller;
use App\Models\Calendar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MarkCalendarController extends Controller
{

    public function show(Request $request, Calendar $calendar)
    {
        $users = $calendar->users()->get();

        return view('calendar.mark', [
            'calendar' => $calendar,
            'token' => $request->token,
            'email' => $request->email,
            'users' => $users,
        ]);
    }

    /**
     * marca la asistencia del usuario
     *
     * @param Request $request
     * @param Calendar $calendar
     */
    public function assistance(Request $request, Calendar $calendar)
    {
        $this->validate($request, [
            'codigo' => ['required'],
            'token' => ['required'],
            'email' => ['required', 'email'],
            'respuesta' => ['required', Rule::in(['si', 'no', 'tal vez'])],
        ]);

        DB::transaction(function () use ($request, $calendar) {

            $user = User::where('token', $request->token)->first();

            if (Hash::check($request->codigo, $user->code)) {
                $user->status = $request->respuesta;
                $user->push();
            }
        });

        return back()->with('message', __('asistencia marcada'));
    }
}
