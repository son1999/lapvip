<?php

namespace App\Listeners;

use App\Mail\UserResetPassword;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;

class UserEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        //
    }

    public function onUserLogin(Login $event) {
        $user = $event->user;
        if(is_a ( $user , 'App\Models\User' )){
            if($user->active > 0 && $user->status == 1) {
                $user->last_logout = 0;
                $user->last_login = time();
                $user->last_login_ip = $this->request->ip();
                $user->save();

                \MyLog::do()->add('info-login');
            }else{
                \Auth::logout();
            }
        }
    }

    public function onUserLogout(Logout $event){
        $user = $event->user;
        if(is_a ( $user , 'App\Models\User' )){
            $user->last_logout = time();
            $user->save();
        }
    }

    public function onResetPassword($data, $user){
        if($data && $user) {
            $user->data = $data;
            \MyLog::do()->add('info-pwd-reset');
            \Mail::to($user->email)->send(new UserResetPassword($user));
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'user.login',
            'App\Listeners\UserEventListener@onUserLogin'
        );

        $events->listen(
            'user.password',
            'App\Listeners\UserEventListener@onResetPassword'
        );

        $events->listen(
            'user.logout',
            'App\Listeners\UserEventListener@onUserLogout'
        );
    }

}