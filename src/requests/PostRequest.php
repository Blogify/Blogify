<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;
use Input;
use jorenvanhocht\Blogify\Models\Post;

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
        $hash   = Input::get('hash');
        $id     = ( ! empty( $hash ) ) ? Post::byHash( $hash )->id : 0;

        return [
            'title'             => 'required|min:2|max:100',
            'slug'              => "required|unique:posts,slug,$id|min:2|max:120",
            'short_description' => 'required|min:2|max:400',
            'reviewer'          => 'exists:users,hash',
            'post'              => 'required',
            'category'          => 'required|exists:categories,hash',
            'publishdate'       => 'required|date_format: d-m-Y H:i',
        ];
    }

}
