<?php

namespace App\Rules\Pages\EditPage;

use App\Page;
use Illuminate\Contracts\Validation\Rule;

class PageNameRule implements Rule
{
    private $page_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($page_id)
    {
        $this->page_id = $page_id;
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
        if(!in_array($str_slug, json_decode(Page::where('id', '<>', $this->page_id)->pluck('slug_url')))) {
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
        return 'The :attribute has already been taken.';
    }
}
