<?php

namespace App\Rules;

use Closure;
use Elyerr\ApiResponse\Assets\Asset;
use Illuminate\Contracts\Validation\ValidationRule;

class AfterTimeRule implements ValidationRule
{

    use Asset;
    /**
     * time
     * @var Date
     */
    protected $date;

    public function __construct($date)
    {
        $this->date = $this->format_date($date);
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value < $this->date) {
            $fail("The $attribute field must be a date after $this->date .");
        }
    }
}
