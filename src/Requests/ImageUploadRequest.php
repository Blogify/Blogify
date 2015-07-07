<?php

namespace jorenvanhocht\Blogify\Requests;


class ImageUploadRequest extends Request
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
            'upload'		=> 'required|image|max:1000',
        ];
    }

}
