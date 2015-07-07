<?php

namespace jorenvanhocht\Blogify\Requests;

use Illuminate\Support\Facades\Input;

class UserRequest extends Request
{

    /**
     * Holds the request specific validation rules
     *
     * @var array
     */
    protected $specifics = [];

    /**
     * Holds the global validation rules
     *
     * @var array
     */
    protected $rules;

    public function __construct()
    {
        if (! Input::has('_method')) {
            $this->generateSpecificsArray();
        }

        $this->rules = [
            'role'		=> 'required|exists:roles,hash',
        ];
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
        return array_merge($this->rules, $this->specifics);
    }

    /**
     * Fill in the request specific validation rules
     *
     * @return void
     */
    public function generateSpecificsArray()
    {
        $this->specifics['name'] = 'required|min:3|max:30';
        $this->specifics['firstname'] = 'required|min:3|max:30';
        $this->specifics['email'] = 'required|email|unique:users,email';
    }

}
