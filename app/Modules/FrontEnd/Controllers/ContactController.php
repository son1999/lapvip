<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
class ContactController extends Controller
{
    public function index(){
        \Lib::addBreadcrumb('Liên Hệ');

        return view('FrontEnd::pages.contact.index', [
            'site_title' => 'Liên hệ',
        ]);
    }

    public function sendcontact(Request $request){
        $valid = [
            'name' => 'required|string',
            'email' => 'required|email|string',
            'phone' => 'required|numeric|regex:/(0)[0-9]{9}/',
            'code' =>   'required',
            'con' => 'required|max:500',
        ];
        $messages = [
            'name.required' => 'Bạn không được để trống trường này',
            'name.string' => 'Bạn phải nhập một chuỗi',
            'email.required' => 'Bạn không được để trống trường này',
            'email.string' => 'Bạn phải nhập một chuỗi',
            'email.email' => 'Email không đúng định dạng',
            'phone.required' => 'Bạn không được để trống trường này',
            'phone.numeric' => 'Phone không đúng định dạng',
            'phone.regex' => 'Phone không đúng định dạng',
            'code.required' => 'Bạn không được để trống trường này',
            'con.required' => 'Bạn không được để trống trường này',
            'con.max' => 'Quá ký tự cho phép (500 ký tự)'

        ];

        $validator = Validator::make($request->all(), $valid, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            $contact = new Contact();
            if (Auth::guard('customer')->check()){
                $contact->cusid = Auth::guard('customer')->user()->id;
            }
            $contact->fullname = $request->name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->code = $request->code;
            $contact->content = $request->con;
            $contact->status = 1;
            $contact->save();
            return redirect()->route('contact')->with('success', 'Xin được cám ơn ý kiến đóng góp của bạn! Yêu cầu của bạn sẽ được gửi tới Ban Quản Trị trong thời gian sớm nhất');
        }
    }

}