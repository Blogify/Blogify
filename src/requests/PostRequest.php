<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;

class PostRequest extends Request {

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
            'title'             => 'required|min:2|max:100',
            'slug'              => 'required|unique:posts,slug|min:2|max:120',
            //'short_description' => 'required|min:2|max:400',
            'post'              => 'required',
            'category'          => 'required',
            'publishdate'       => 'required', // to do add date format
        ];
    }

}
