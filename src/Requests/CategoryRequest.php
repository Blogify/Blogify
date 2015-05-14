<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;
use Input;
use jorenvanhocht\Blogify\Models\Category;

class CategoryRequest extends Request {

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
        $id = Category::byHash($this->segment(3))->id;
        return [
            'name'		=> "required|unique:categories,name,$id|min:3|max:45",
        ];
    }

}
