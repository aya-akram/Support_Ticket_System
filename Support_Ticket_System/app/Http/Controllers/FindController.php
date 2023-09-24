<?php

namespace App\Http\Controllers;

use App\Models\tickets;
use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FindController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request){
        $settings = settings::first();

        $tickets = tickets::where('subject', 'like', '%' .$request->q . '%')->paginate(15);
        $search_term = $request->q;

        $open = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $closed = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = departments::all();
        $tickets_depart = tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('settings','tickets', 'search_term', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));
    }

    public function status($status){
        $settings = settings::first();

        $tickets = tickets::where('status', $status)->paginate(15);
        $open = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $closed = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = departments::all();
        $tickets_depart = tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('settings','tickets', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));

    }


    public function department($id){
        $settings = settings::first();


        $tickets = tickets::where('department_id', $id)->paginate(15);

        $open = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'open'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'open'])->count();
        $replied = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'replied'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'replied'])->count();
        $closed = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'closed'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'closed'])->count();
        $pending = tickets::where(['assigned_to' => Auth::id(), 'status'=> 'pending'])->orWhere(['user_id'=> Auth::id(), 'status'=> 'pending'])->count();
        $departments = departments::all();
        $tickets_depart = tickets::where('assigned_to', Auth::id())->orWhere('user_id', Auth::id())->get();

        return view('tickets.index', compact('settings','tickets', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));

    }
}
