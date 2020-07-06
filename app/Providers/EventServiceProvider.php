<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\UserEventListener@onUserLogin',
            'App\Listeners\CustomerEventListener@onCustomerLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\UserEventListener@onUserLogout',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        //frontend
        'App\Listeners\CustomerEventListener',

        'App\Listeners\OrderEventListener',

        //backend
        'App\Listeners\UserEventListener',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
