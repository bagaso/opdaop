<?php

namespace App\Rules\UpdateJsons\EditJson;

use App\UpdateJson;
use Illuminate\Contracts\Validation\Rule;

class NameCheckExistingSlugUrlRule implements Rule
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
        $str_slug = str_slug($value);
        if(!in_array($str_slug, json_decode(UpdateJson::where('id', '<>', $this->id)->pluck('slug_url')))) {
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
        return 'The name slug url is already exist.';
    }
}
