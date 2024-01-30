<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
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
            'nombre' => $user->name,
            'apellido' => $user->last_name,
            'estado' => $user->stado,
            'creado' => $user->created_at,
            'actualizado' => $user->updated_at,
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
            'correo' => 'email',
            'nombre' => 'name',
            'apellido' => 'last_name',
            'estado' => 'status',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformResponse($index)
    {
        $attribute = [
            'email' => 'correo',
            'name' => 'nombre',
            'last_name' => 'apellido',
            'status' => 'estado',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function getOriginalAttributes($index)
    {
        $attributes = [
            'id' => 'id',
            'correo' => 'email',
            'nombre' => 'name',
            'apellido' => 'last_name',
            'estado' => 'status',
            'creado' => 'created_at',
            'actualizado' => 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
