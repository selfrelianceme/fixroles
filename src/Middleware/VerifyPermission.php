<?php

namespace selfreliance\fixroles\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use fixroles\Roles\Exceptions\PermissionDeniedException;

class VerifyPermission
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $permission
     * @return mixed
     * @throws \Lemmas\Roles\Exceptions\PermissionDeniedException
     */
    public function handle($request, Closure $next, $permission)
    {
        if ($this->auth->check() && $this->auth->user()->may($permission)) {
            return $next($request);
        }

        return response('Forbidden: Permission Denied.', 403);
    }

}