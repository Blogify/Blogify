<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request {

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
			'name'		=> 'required|min:3|max:30',
			'firstname'	=> 'required|min:2|max:30',
			'email'		=> 'required|email|unique:users,email',
			'role'		=> 'required|exists:roles,hash',
		];
	}

}
