<?php

namespace App\Models;

use App\Models\User;
use App\Transformers\CalendarTransformer;
use DateTime;
use DateTimeZone;
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
        'start',
        'end',
        'public',
        'user_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function setStartAttribute($value)
    {
        $localTime = new DateTime($value, new DateTimeZone(request()->header('X-LOCALTIME')));

        $localTime->setTimezone(new DateTimeZone('UTC'));

        $this->attributes['start'] = $localTime->format('Y-m-d H:i:s');
    }

    public function setEndAttribute($value)
    {
        $localTime = new DateTime($value, new DateTimeZone(request()->header('X-LOCALTIME')));

        $localTime->setTimezone(new DateTimeZone('UTC'));

        $this->attributes['end'] = $localTime->format('Y-m-d H:i:s');

    }
}
