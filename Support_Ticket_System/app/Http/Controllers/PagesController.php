<?php

namespace App\Http\Controllers;

use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PagesController extends Controller
{
    public function about(){
        $settings = settings::first();
        return view('pages.about',compact('settings'));

    }

    public function contact(){
        $settings = settings::first();
        return view('pages.contact',compact('settings'));
    }

    public function contactMail(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'description' => 'required|min:15|max:10000'
        ]);
        $settings = settings::all()->first();
        Mail::send('mails.contact',['request'=>$request], function ($message) use ($settings){
            $message->from('no-reply@gmail.com', 'Ticket Plus New Contact');
            $message->subject('New Contact');
            $message->to($settings->admin_email);
        });
        return redirect()->back()->withMessage('Contact form submitted. We will get back to you soon.');
    }
}
