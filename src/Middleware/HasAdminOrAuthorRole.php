<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Role;

class HasAdminOrAuthorRole
{

    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @var \jorenvanhocht\Blogify\Models\Role
     */
    private $role;

    /**
     * @var array
     */
    private $allowed_roles = [];

    /**
     * Create a new filter instance.
     *
     * @param \jorenvanhocht\Blogify\Models\Role $role
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth, Role $role)
    {
        $this->auth = $auth;
        $this->role = $role;

        $this->fillAlowedRolesArray();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! in_array($this->auth->user()->role->id, $this->allowed_roles)) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }

    /**
     * @return void
     */
    private function fillAlowedRolesArray()
    {
        $roles = $this->role
                    ->where('name', '<>', 'Reviewer')
                    ->where('name', '<>', 'Member')
                    ->get();

        foreach ($roles as $role) {
            array_push($this->allowed_roles, $role->id);
        }
    }

}
