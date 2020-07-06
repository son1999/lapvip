<?php

namespace App\Listeners;

use App\Mail\CustomerRegister;
use App\Mail\CustomerResetPassword;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use App\Models\Customer;

class CustomerEventListener
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

    public function onCustomerLogin(Login $event) {
        $user = $event->user;
        if(is_a ( $user , 'App\Models\Customer' )) {
            if ($user->active > 0 && $user->status == 1) {
                $user->last_login = time();
                $user->last_login_ip = $this->request->ip();
                $user->save();
            } else {
                \Auth::guard('customer')->logout();
            }
        }
    }


    public function onCustomerRegister($id) {
        $customer = Customer::find($id);
        if($customer) {
            \Mail::to($customer->email)->send(new CustomerRegister($customer));
        }
    }

    public function onCustomerRequestPassword($id) {
        $customer = Customer::find($id);
        if($customer){
            \Mail::to($customer->email)->send(new CustomerResetPassword($customer));
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
            'customer.register',
            'App\Listeners\CustomerEventListener@onCustomerRegister'
        );

        $events->listen(
            'customer.register.resend',
            'App\Listeners\CustomerEventListener@onCustomerRegister'
        );

        $events->listen(
            'customer.password',
            'App\Listeners\CustomerEventListener@onCustomerRequestPassword'
        );
    }

}