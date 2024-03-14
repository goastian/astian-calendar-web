<?php

namespace App\Transformers;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class CalendarTransformer extends TransformerAbstract
{

    use Asset;

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($calendar)
    {
        return [
            'id' => $calendar->id,
            'subject' => $calendar->title,
            'body' => $calendar->body,
            'start' => $calendar->start,
            'end' => $calendar->end,
            'link' => $calendar->resource,
            'meeting' => $calendar->meeting,
            'public' => $calendar->public,
            'creado' => $this->format_date($calendar->created_at),
            'actualizado' => $this->format_date($calendar->created_at),
            'links' => [
                'parent' => route('calendars.index'),
                'store' => route('calendars.store'),
                'show' => route('calendars.show', ['calendar' => $calendar->id]),
                'update' => route('calendars.update', ['calendar' => $calendar->id]),
                'destroy' => route('calendars.destroy', ['calendar' => $calendar->id]),
                'users' => route('calendars.users.index', ['calendar' => $calendar->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'subject' => 'title',
            'body' => 'body',
            'start' => 'start',
            'end' => 'end',
            'link' => 'resource',
            'meeting' => 'meeting',
            'public' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'title' => 'subject',
            'body' => 'body',
            'start' => 'start',
            'end' => 'end',
            'resource' => 'link',
            'meeting' => 'meeting',
            'public' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'subject' => 'title',
            'body' => 'body',
            'start' => 'start',
            'end' => 'end',
            'link' => 'resource',
            'meeting' => 'meeting',
            'public' => 'public',
            'created_at' => 'created',
            'updated_at' => 'updated',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
