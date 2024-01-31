<?php

namespace App\Http\Controllers\Calendar;

use App\Events\DestroyUserEvent;
use App\Events\StoreUserEvent;
use App\Events\UpdateUserEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Calendar;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarUserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . UserTransformer::class)->only('store', 'update');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Calendar $calendar)
    {
        $users = $calendar->users()->get();

        return $this->showAll($users, UserTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Calendar $calendar, User $user)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'name' => ['nullable', 'max:100'],
            'last_name' => ['nullable', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $calendar, $user) {
            $user = $user->fill($request->only('email', 'name', 'last_name'));
            $user->calendar_id = $calendar->id;
            $user->save();
        });

        StoreUserEvent::dispatch();

        return $this->showOne($user, UserTransformer::class, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar, User $user)
    {
        return $this->showOne($user, UserTransformer::class);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar, User $user)
    {
        $this->validate($request, [
            'email' => ['nullable', 'email'],
            'name' => ['nullable', 'max:100'],
            'last_name' => ['nullable', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $calendar, $user) {

            $changed = false;

            if ($this->is_diferent($user->name, $request->name)) {
                $user->name = $request->name;
                $changed = true;
            }

            if ($this->is_diferent($user->last_name, $request->last_name)) {
                $user->last_name = $request->last_name;
                $changed = true;
            }

            if ($this->is_diferent($user->email, $request->email)) {
                $user->email = $request->email;
                $changed = true;
            }

            if ($changed) {
                UpdateUserEvent::dispatch();

                $user->save();
            }

        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar, User $user)
    {
        $user->delete();

        DestroyUserEvent::dispatch();
    
        return $this->showOne($user, UserTransformer::class);
    }
}
