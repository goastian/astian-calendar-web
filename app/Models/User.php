<?php

namespace App\Models;

use App\Models\Calendar;
use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Master
{
    use HasFactory, Notifiable;

    public $table = 'users';

    public $timestamps = false;

    public $transformer = UserTransformer::class;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'last_name',
        'status',
        'calendar_id',
        //'token',
        //'code',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
