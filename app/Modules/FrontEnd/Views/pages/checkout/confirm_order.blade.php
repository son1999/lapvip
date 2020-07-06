@extends('FrontEnd::layouts.page_inside')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('content')
    <div class="wrap-check-out">
        <div class="checkout-content">
            <div class="wrap-checkout-content">
                <section class="order">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order__heading">
                                <h3 class="order__title">@if($ad_confirm == 1) {{ __('site.bandadatiepnhandon') }} @else {{__('site.bandahuydon')}} @endif</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order__fist d-flex justify-content-between">
                                <div class="order__item">
                                    <p class="order__name">
                                        {{ __('site.donhang') }}
                                        <span class="order--red">#{{$booking->code}}</span>
                                    </p>
                                    <p class="order__date">{{ __('site.datngay') }} {{\Lib::dateFormat($booking->created,'d/m/Y - H:i')}}</p>
                                </div>
                                <div class="order__total">
                                    <p class="order__text-price">{{ __('site.tongcong') }} <span class="order__price">{{\Lib::price_format($booking->price + $booking->shipping_fee)}}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order__wrapper">
                                <div class="order__body">
                                    <div class="order__body-info d-flex">
                                        <p>{{ __('site.thongtinmonan') }}</p>
                                        <p>{{ __('site.dongia') }}</p>
                                        <p>{{ __('site.soluong') }}</p>
                                        <p>{{ __('site.thanhtien') }}</p>
                                    </div>
                                </div>
                                <div class="order__list_items">
                                @foreach($booking->items as $itm)
                                <div class="order__list{{ $loop->last ? ' border-0 pb-0' : '' }}">
                                    <div class="order__list-img">
                                        <a href="#"><img src="{{$itm->food->getImageUrl('small')}}" alt=""></a>
                                    </div>
                                    <div class="order__content">
                                        <p>{{$itm->name}}</p>
                                        <span>{{$itm->food->sapo}}</span>
                                    </div>
                                    <p class="order__list-price">{{\Lib::price_format($itm->price)}}</p>
                                    <p class="order__list-quantity">{{$itm->quantity}}</p>
                                    <p class="order__list-user">{{Lib::price_format($itm->price*$itm->quantity)}}</p>
                                </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="order__add">
                                <h3>{{ __('site.diachigiaohang') }}</h3>
                                <ul>
                                    <li>{{$booking->customer_name}} - {{$booking->phone}}</li>
                                    <li>
                                        {{$type_address[$booking->type_address]}}
                                    </li>
                                    <li>
                                        {{$booking->address}}
                                    </li>
                                    <li>  {{$booking->province->Name_VI}} - {{$booking->district->getType().' '.$booking->district->Name_VI}} - {{$booking->ward->getType().' '.$booking->ward->Name_VI}}</li>
                                    <li>
                                        Note: {{$booking->note}}
                                    </li>
                                    <li>
                                        {{__('site.luuygiaohang')}}: {{@$shipping_notices[$booking->shipping_notice]}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="order__add-total">
                                <h3>{{ __('site.tongcong') }}</h3>
                                <div class="order__box-content d-flex justify-content-between">
                                    <div class="order__box-ship">
                                        <p>{{ __('site.tongtien') }}</p>
                                        <span>{{ __('site.phigiaohang') }}</span>
                                    </div>
                                    <div class="order__box-price">
                                        <p>{{\Lib::price_format($booking->price)}}</p>
                                        <span>{{ $booking->shipping_fee > 0 ? $booking->shipping_fee : __('site.mienphi') }}</span>
                                    </div>
                                </div>
                                <div class="order__box d-flex justify-content-between">
                                    <div class="order__box-ship">
                                        <p>{{ __('site.tongcong') }}</p>
                                        <span class="font-italic">{{ __('site.thanhtoan') }}: {{$payment_types[$booking->payment_type]}}</span>
                                    </div>
                                    <div class="order__box-price">
                                        <p>{{\Lib::price_format($booking->price + $booking->shipping_fee)}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection