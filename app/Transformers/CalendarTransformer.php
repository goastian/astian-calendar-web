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
            'titulo' => $calendar->title,
            'cuerpo' => $calendar->body,
            'link' => $calendar->resource,
           // 'imagen' => $calendar->banner ? config('app.url') . "/storage/banners/" . $calendar->banner : null,
            'reunion' => $calendar->meeting,
            'publico' => $calendar->public,
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
            'titulo' => 'title',
            'cuerpo' => 'body',
            'link' => 'resource',
            'imagen' => 'banner',
            'reunion' => 'meeting',
            'publico' => 'public',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'title' => 'titulo',
            'body' => 'cuerpo',
            'resource' => 'link',
            'banner' => 'imagen',
            'meeting' => 'reunion',
            'public' => 'publico',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'titulo' => 'title',
            'cuerpo' => 'body',
            'link' => 'resource',
            'imagen' => 'banner',
            'reunion' => 'meeting',
            'publico' => 'public',
            'created_at' => 'creado',
            'updated_at' => 'actualizado',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
