<?php

namespace App\Http\Controllers\Calendar;

use App\Events\DestroyCalendarEvent;
use App\Events\StoreCalendarEvent;
use App\Events\UpdateCalendarEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Calendar;
use App\Transformers\CalendarTransformer;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Stevebauman\Purify\Facades\Purify;

class CalendarController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . CalendarTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Calendar $calendar)
    {
        $params = $this->filter_transform($calendar->transformer);

        $data = $this->search($calendar->table, $params, 'user_id', $this->user()->id);

        return $this->showAll($data, $calendar->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Calendar $calendar)
    {
        $this->validate($request, [
            'title' => ['required', 'max:100'],
            'body' => ['required', 'max:1000'],
            'meeting' => ['required', 'date_format:Y-m-d H:i'],
            'resource' => ['nullable', 'url:https'],
            //'banner' => ['nullable', 'file', 'image', 'mimes:jpg,png,svg'],
            'public' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $calendar) {
            $calendar = $calendar->fill($request->except('body'));
            $calendar->body = Purify::clean($request->body);
            //$calendar->banner = $request->banner ? Storage::disk('banners')->put('', $request->banner) : null;
            $calendar->user_id = $this->user()->id;
            $calendar->save();
        });

        StoreCalendarEvent::dispatch();

        return $this->showOne($calendar, $calendar->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        throw_unless($calendar->user_id == $this->user()->id,
            new ReportError(Lang::get('Unauthorize user'), 403));

        return $this->showOne($calendar, $calendar->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        throw_unless($calendar->user_id == $this->user()->id,
            new ReportError(Lang::get('Unauthorize user'), 403));

        $this->validate($request, [
            'title' => ['nullable', 'max:100'],
            'body' => ['nullable', 'max:1000'],
            'resource' => ['nullable', 'url:https'],
            //'banner' => ['nullable', 'file', 'image', 'mimes:jpg,png,svg'],
            'meeting' => ['nullable', 'date_format:Y-m-d H:i'],
            'public' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($calendar, $request) {

            $changed = false;

            if ($this->is_diferent($calendar->subject, $request->subject)) {
                $calendar->subject = $request->subject;
                $changed = true;
            }

            if ($this->is_diferent($calendar->body, $request->body)) {
                $calendar->body = $request->body;
                $changed = true;
            }

            if ($this->is_diferent($calendar->resource, $request->resource)) {
                $calendar->resource = $request->resource;
                $changed = true;
            }

            /* if ($request->banner) {
            Storage::disk('banners')->delete($calendar->banner);
            $calendar->resource = Storage::disk('banners')->put('', $request->banner);
            $changed = true;
            }*/

            if ($this->is_diferent($calendar->meeting, $request->meeting)) {
                $calendar->meeting = $request->meeting;
                $changed = true;
            }

            if ($this->is_diferent($calendar->public, $request->public)) {
                $calendar->public = $request->public;
                $changed = true;
            }

            if ($changed) {

                UpdateCalendarEvent::dispatch();

                $calendar->push();
            }
        });

        return $this->showOne($calendar, $calendar->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        throw_unless($calendar->user_id == $this->user()->id,
            new ReportError(Lang::get('Unauthorize user'), 403));

        Storage::disk('banners')->delete($calendar->banner);

        $calendar->delete();

        DestroyCalendarEvent::dispatch();

        return $this->showOne($calendar, $calendar->transformer);
    }
}
