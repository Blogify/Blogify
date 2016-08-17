<?php

namespace jorenvanhocht\Blogify\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use jorenvanhocht\Blogify\Models\Role;

class BlogifyAdminAuthenticate
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
    private $roles;

    /**
     * @var array
     */
    private $allowed_roles = [];

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \jorenvanhocht\Blogify\Models\Role $role
     */
    public function __construct(Guard $auth, Role $role)
    {
        $this->auth = $auth;
        $this->roles = $role->byAdminRoles()->get();
        $this->fillAllowedRolesArray();
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
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            return redirect('auth/login');
        }

        // Check if the user has permission to visit the admin panel
        if (! in_array($this->auth->user()->role_id, $this->allowed_roles)) {
            return redirect('auth/login');
        }

        return $next($request);
    }

    /**
     * @return void
     */
    private function fillAllowedRolesArray()
    {
        foreach ($this->roles as $role) {
            array_push($this->allowed_roles, $role->id);
        }
    }

}
