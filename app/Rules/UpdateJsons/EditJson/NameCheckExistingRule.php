<?php

namespace App\Rules\UpdateJsons\EditJson;

use App\UpdateJson;
use Illuminate\Contracts\Validation\Rule;

class NameCheckExistingRule implements Rule
{
    private $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!in_array(strtolower($value), array_map("strtolower", json_decode(UpdateJson::where('id', '<>', $this->id)->pluck('name'))))) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The json name is already exist.';
    }
}
