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
            'description' => $calendar->body,
            'start' =>  $this->format_date($calendar->start),
            'end' => $this->format_date($calendar->end),
            'link' => $calendar->resource,
            'public' => $calendar->public,
            'created' => $this->format_date($calendar->created_at),
            'updated' => $this->format_date($calendar->created_at),
            'deleted' => $this->format_date($calendar->deleted_at),
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
            'description' => 'body',
            'start' => 'start',
            'end' => 'end',
            'link' => 'resource',
            'public' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'title' => 'subject',
            'body' => 'description',
            'start' => 'start',
            'end' => 'end',
            'resource' => 'link',
            'public' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'subject' => 'title',
            'description' => 'body',
            'start' => 'start',
            'end' => 'end',
            'link' => 'resource',
            'public' => 'public',
            'created' => 'created_at',
            'updated' => 'updated_at',
            'deleted' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
