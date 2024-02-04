<?php

namespace App\Models;

use App\Models\User;
use App\Transformers\CalendarTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Master
{
    use HasFactory, SoftDeletes;

    public $table = 'calendars';

    public $transformer = CalendarTransformer::class;

    protected $fillable = [
        'title',
        'body',
        'resource',
        'meeting',
        'public',
        'user_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
