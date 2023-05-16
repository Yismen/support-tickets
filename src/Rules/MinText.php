<?php

namespace Dainsys\Support\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Validate a text to a minimun amount after stripping html tags!
 */
class MinText implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected int $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = strip_tags($value);
        $value = str_replace(['&nbsp;'], ' ', $value);
        $value = trim($value);

        return strlen($value) >= $this->count;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute must be at least {$this->count} characters.";
    }
}
