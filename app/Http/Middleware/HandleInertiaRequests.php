<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    protected $actionMaps = [
        'create' => 'Create',
        'show' => 'Details',
        'edit' => 'Edit',
    ];

    /**
     * Check if route name is an excluded route.
     *
     * @param  string  $route  - The name of the route
     */
    private function isExcludedRoute(string $route): bool
    {
        return in_array($route, [
            'login',
            'register',
            'password.request',
            'password.reset',
            'password.email',
            'password.update',
            'verification.notice',
            'verification.verify',
            'verification.resend',

            // Ajax routes
            'people.storeAjax',
            'people.updateAjax',
            'people.destroyAjax',
            'people.notes.store',
            'people.notes.update',
            'people.notes.destroy',
            'addresses.store',
            'addresses.destroy',
            'addresses.update',
        ]);
    }

    /**
     * Build page breadcrumbs.
     *
     * @param  string|null  $route  - The name of the route
     */
    private function getBreadcrumbs(?string $route)
    {
        if (! $route || $this->isExcludedRoute($route) || $route === 'logout') {
            return [];
        }

        // set the breadcrumbs
        $breadcrumbs = [
            [
                'label' => 'Home',
                'key' => 'dashboard',
                'route' => route('dashboard'),
            ],
        ];

        if ($route !== 'dashboard') {
            // if the route is profile, add an unclickable profile breadcrumb
            if (preg_match('/profile/', $route)) {
                $breadcrumbs[] = [
                    'label' => 'Profile',
                    'key' => 'profile',
                    'route' => '',
                ];
            }

            // People index
            if (preg_match('/people/', $route)) {
                array_push($breadcrumbs, [
                    'label' => 'People',
                    'key' => 'people.index',
                    'route' => route('people.index'),
                ]);
            }

            // if the route is not a resource index, add the current page to the breadcrumbs
            if (! preg_match('/\.index$/', $route)) {
                $breadcrumbs[] = [
                    'label' => $this->actionMaps[explode('.', $route)[1]],
                    'key' => $route,
                    'route' => '',
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
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
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'breadcrumbs' => $this->getBreadcrumbs($request->route()->getName()),
        ];
    }
}
