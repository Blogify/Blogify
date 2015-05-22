<?php namespace jorenvanhocht\Blogify\Requests;

use App\Http\Requests\Request;
use App\User;
use Illuminate\Contracts\Auth\Guard;

class ProfileUpdateRequest extends Request
{

    /**
     * Holds an instance of the Guard contract
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Holds the hash of the user
     * that we are trying to edit
     *
     * @var string
     */
    protected $hash;

    /**
     * Holds the id of the user
     * that we are trying to edit
     *
     * @var int|bool
     */
    protected $user_id;

    /**
     * Construct the class
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->hash = $this->route('profile');
        $this->user_id = $this->getUserId();

        if ($this->auth->user()->id != $this->user_id) return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->hash = $this->route('profile');
        $this->user_id = $this->getUserId();

        return [
            'name'                  => 'required|min:3|max:30',
            'firstname'             => 'required|min:3|max:30',
            'username'              => 'required|min:2|max:30',
            'email'                 => "required|email|unique:users,email,$this->user_id",
            'newpasswordconfirm'    => "required_with:newpassword",
            'profilepicture'        => 'image|max:1000',
        ];
    }

    /**
     * Get the user id of the user
     * we are trying to edit
     *
     * @return int|boolean
     */
    private function getUserId()
    {
        return User::byHash($this->hash)->id;
    }
}
