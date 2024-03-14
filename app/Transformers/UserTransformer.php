<?php

namespace App\Transformers;

use Elyerr\ApiResponse\Assets\Asset;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
    public function transform($user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'status' => $user->stado,
            'creado' => $this->format_date($user->created_at),
            'actualizado' => $this->format_date($user->updated_at),
            'links' => [
                'parent' => route('calendars.users.index', ['calendar' => $user->calendar_id]),
                'store' => route('calendars.users.store', ['calendar' => $user->calendar_id]),
                'show' => route('calendars.users.show', ['calendar' => $user->calendar_id, 'user' => $user->id]),
                'update' => route('calendars.users.update', ['calendar' => $user->calendar_id, 'user' => $user->id]),
                'destroy' => route('calendars.users.destroy', ['calendar' => $user->calendar_id, 'user' => $user->id]),
            ],
        ];
    }

    public static function transformRequest($index)
    {
        $attribute = [
            'email' => 'email',
            'name' => 'name',
            'last_name' => 'last_name',
            'status' => 'status',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'email' => 'email',
            'name' => 'name',
            'last_name' => 'last_name',
            'status' => 'status',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'email' => 'email',
            'name' => 'name',
            'last_name' => 'last_name',
            'status' => 'status',
            'creado' => 'created_at',
            'actualizado' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
