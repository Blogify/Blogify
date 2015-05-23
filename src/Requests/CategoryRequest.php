<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;
use jorenvanhocht\Blogify\Models\Category;

class CategoryRequest extends Request
{

    /**
     * @var Category
     */
    protected $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

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
        $segment = $this->segment(3);
        $id = isset($segment) ? $this->category->byHash($this->segment(3))->id : 0;

        return [
            'name'		=> "required|unique:categories,name,$id|min:3|max:45",
        ];
    }

}
