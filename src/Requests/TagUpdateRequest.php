<?php

namespace jorenvanhocht\Blogify\Requests;


class TagUpdateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tags'   => 'required|min:2|max:45',
        ];
    }
}
