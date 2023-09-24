<?php

namespace App\Http\Controllers\Admin;

use App\Models\tickets;
use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function search(Request $request){
        $settings = settings::first();

        $tickets = Tickets::where('subject', 'like', '%' .$request->q . '%')->paginate(15);
        $search_term = $request->q;

        $open = tickets::where('status', 'open')->count();
        $replied = tickets::where('status', 'replied')->count();
        $closed = tickets::where('status', 'closed')->count();
        $pending = tickets::where('status', 'pending')->count();

        $departments = departments::all();
        $tickets_depart = tickets::all();

         return view('admin.tickets.index', compact('settings','tickets', 'search_term', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));
    }


    public function status($status){
        $settings = settings::first();

        $tickets = tickets::where('status', $status)->paginate(15);
        $open = tickets::where('status', 'open')->count();
        $replied = tickets::where('status', 'replied')->count();
        $closed = tickets::where('status', 'closed')->count();
        $pending = tickets::where('status', 'pending')->count();

        $departments = departments::all();
        $tickets_depart = Tickets::all();

        return view('admin.tickets.index', compact('settings','tickets', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));

    }


    public function department($id){
        $settings = settings::first();


        $tickets = tickets::where('department_id', $id)->paginate(15);

        $open = tickets::where('status', 'open')->count();
        $replied = tickets::where('status', 'replied')->count();
        $closed = tickets::where('status', 'closed')->count();
        $pending = tickets::where('status', 'pending')->count();

        $departments = departments::all();
        $tickets_depart = tickets::all();

        return view('admin.tickets.index', compact('settings','tickets', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));

    }
}
