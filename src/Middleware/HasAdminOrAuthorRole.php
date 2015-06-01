<?php namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Role;

class HasAdminOrAuthorRole
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Roles
     *
     * @var
     */
    private $role;

    /**
     * Holds the allowed roles
     *
     * @var array
     */
    private $allowed_roles = [];

    /**
     * Create a new filter instance.
     *
     * @param Role $role
     * @param Guard $auth
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
     * Get the allowed roles and push
     * them in the allowed roles array
     *
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
