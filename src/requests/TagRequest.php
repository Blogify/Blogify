<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;
use Input;

class TagRequest extends Request {

    protected $tags = [];
    protected $rules = [];

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
        $this->fillTagsArray();

        foreach ( $this->tags as $key => $tag )
        {

            $this->rules[$key] = 'required|unique:categories,name|min:3|max:45';
        }

        return $this->rules;
    }

    private function fillTagsArray()
    {
        $tags = Input::get('tags');

        $tags = explode(',',$tags);

        foreach ( $tags as $tag )
        {
            array_push($this->tags, $tag);
        }
    }
}
