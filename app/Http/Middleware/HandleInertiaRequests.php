<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

use App\Models\User;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {   
        return [
            ...parent::share($request),
            'auth' => ['user' => $request->user()],
            // 'isAdmin' => $request->user() ? $request->user()->isAdmin() : false,
            'auth.user' => fn () => $request->user() ? $request->user()->load('roles.permissions') : null,
            'user.roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
            'user.permissions' => $request->user() ? $request->user()->getAllPermissionsAttribute()->pluck('name') : [],
            'notifications' => $request->user() ? $request->user()->getNotificationCount() : '',
        ];
    }
}
