<?php

namespace App\Listeners;

use App\Libs\CouponLib;
use App\Libs\Lib;
use App\Libs\LoadDynamicRouter;
use App\Libs\OlalaMail;
use App\Mail\OrderSuccessAdmin;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\OrderRefund;
use App\Models\Storage;
use Illuminate\Http\Request;
use DB;

class OrderEventListener
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

    public function onSendMailSuccess($id = 0, $order = []) {
        if(empty($order) && $id > 0) {
            $order = Order::find($id);
        }
//        dd($order);
        if(!empty($order)) {
            $title = '['.env('APP_NAME').'] Thông tin đặt hàng thành công - #'.$order->code;
            //send email active
            if(\Lib::is_valid_email($order->email)) {
                $r = OlalaMail::send('', $order->email, $title, ['emails.order.success', ['order' => $order,'payment_types' => Order::paymentType()]]);

                //thong bao admin
                $this->onOrderAdminNotice($order);

                //Save log order
                OrderLog::add($order->id, 'email_buy', !empty($r) ? 'Thành công' : 'Thất bại');
            }
        }
    }

    public function onOrderAdminNotice($order)
    {
        LoadDynamicRouter::loadRoutesFrom('BackEnd');
        $config = Lib::getSiteConfig();
        if (isset($config['email_order'])){
            $mails = explode(",",$config['email_order']);
            $mail = new OrderSuccessAdmin($order,Order::paymentType());
            $mail->to($mails);
            OlalaMail::sendMailable($mail);
        }
    }

    public function onPayment($id, $order = [])
    {
        if(empty($order)) {
            $order = Order::with('items')->where('id',$id)->first();
        }
        if($order && $order->isPaid()) {
            if($order->coupon_code != '') {
                CouponLib::tickVoucherUsed($order->coupon_code);
            }
            foreach($order->items as $item){
                $wery = DB::table('storage');
                $wery->join('product_prices','storage.prd_price_id','=','product_prices.id');
                $wery->select('storage.*');
                $wery->where('product_prices.product_id',$item->product_id);
                $wery->where('product_prices.filter_ids',$item->filter_ids);
                $wery->where('storage.amount','>',0);
                $storages = $wery->get();
                if(!empty($storages)) {
                    $arr_not_enough = [];
                    foreach($storages as $storage) {
                        if($storage->amount >= $item->quantity){
                            Storage::where('id',$storage->id)->update(['amount' => DB::raw('amount - '.$item->quantity)]);
                            $arr_not_enough = [];
                            break;
                        }else{
                            $arr_not_enough[] = ['id' => $storage->id,'amount' => $storage->amount];
                        }
                    }

                    if(!empty($arr_not_enough)) {
                        $final_amount_update = $item->quantity;
                        foreach($arr_not_enough as $notE){
                            if($final_amount_update > 0) {
                                $update = $final_amount_update > $notE['amount'] ? $notE['amount'] : $final_amount_update;
                                $final_amount_update -= $update;
                                Storage::where('id', $notE['id'])->update(['amount' => DB::raw('amount - ' . $update)]);
                            }else {
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    public function onRefund($id, $order,$request,$status){
        if(empty($order)) {
            $order = Order::find($id);
        }
        if($order) {
            switch ($status){
                case 5:
                    $order->update(['updated' => time(),'status' => 5,'user_id'=>\Auth::id()]);
                    /*add to refund table*/
                    $refund = new OrderRefund();
                    $refund->created = time();
                    $refund->refund_fee = $request[''];
                    $refund->user_id = \Auth::id();
                    $refund->reason = $request->reason;
                    $order->order_refund()->save($refund);

                    \MyLog::do()->add(Order::KEY . '-request-refund', $order->id);
                    OrderLog::add($order->id,'request_refund',$request->reason);
                    break;
                case 6:
                    $order->update(['updated' => time(),'status' => 6]);
                    /*Accept refund*/
                    $order->order_refund()->update([
                        'process_user'=>\Auth::id(),
                        'process_time'=>time(),
                        'status'=>1,
                        'note'=>$request->note
                    ]);

                    \MyLog::do()->add(Order::KEY . '-confirm-refund', $order->id);
                    OrderLog::add($order->id,'confirm_refund',$request->note);
                    break;
                case 7:
                    $order->update(['updated' => time(),'status' => 7]);
                    /*Accept refund*/
                    $order->order_refund()->update([
                        'process_user'=>\Auth::id(),
                        'process_time'=>time(),
                        'status'=>2,
                    ]);
                    \MyLog::do()->add(Order::KEY . '-done-refund', $order->id);
                    OrderLog::add($order->id,'done_refund');
                    break;
            }
        }
    }

    public function onDelete($id, $order,$request){
        if(empty($order)) {
            $order = Order::find($id);
        }
        if($order) {
            $order->update(['updated' => time(), 'status' => 6]);

            $order->order_refund()->update([
                'process_user' => \Auth::id(),
                'process_time' => time(),
                'status' => -1,
                'note' => $request->note
            ]);
//            \Olala::send('', $order->email, $title, ['email.order.success', ['order' => $order]]);
            //Save log order
            \MyLog::do()->add(Order::KEY . '-remove', $order->id);
            OrderLog::add($order->id, 'delete', $request->reason);
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
            'order.success',
            'App\Listeners\OrderEventListener@onSendMailSuccess'
        );
        $events->listen(
            'order.refund',
            'App\Listeners\OrderEventListener@onRefund'
        );
        $events->listen(
            'order.delete',
            'App\Listeners\OrderEventListener@onDelete'
        );
        $events->listen(
            'order.payment',
            'App\Listeners\OrderEventListener@onPayment'
        );
    }

}