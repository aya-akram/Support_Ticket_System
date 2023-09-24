<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\files;
use App\Models\replies;
use App\Models\tickets;
use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use App\Notifications\NewTicket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\TicketReplyNotification;

class TicketsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $user = Auth::user(); // Get the authenticated user
        $settings = settings::first();

        if ($user->hasRole('admin')) {
            // Admin view logic goes here if needed
            return redirect()->to('admin/tickets');
        } else {
            // Retrieve user-specific ticket data
            $tickets = tickets::where('user_id', $user->id)
                ->orWhere('assigned_to', $user->id)
                ->paginate(15);

            $open = tickets::where(['assigned_to' => $user->id, 'status' => 'open'])
                ->orWhere(['user_id' => $user->id, 'status' => 'open'])
                ->count();

            $replied = tickets::where(['assigned_to' => $user->id, 'status' => 'replied'])
                ->orWhere(['user_id' => $user->id, 'status' => 'replied'])
                ->count();

            $closed = tickets::where(['assigned_to' => $user->id, 'status' => 'closed'])
                ->orWhere(['user_id' => $user->id, 'status' => 'closed'])
                ->count();

            $pending = tickets::where(['assigned_to' => $user->id, 'status' => 'pending'])
                ->orWhere(['user_id' => $user->id, 'status' => 'pending'])
                ->count();

            $departments = departments::all();

            $tickets_depart = tickets::where('assigned_to', $user->id)
                ->orWhere('user_id', $user->id)
                ->get();

            return View::make('tickets.index', compact('settings','tickets', 'open', 'replied', 'closed', 'pending', 'departments', 'tickets_depart'));
        }
    }


    public function create(){
        $settings = settings::first();
        $departments = departments::all();
        return view('tickets.new_ticket', compact('departments','settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'subject' => 'required|min:10|max:300',
            'description' => 'required|min:15|max:10000',
        ]);

        $ticket = new tickets();
        $ticket->department_id = $request->department_id;
        $ticket->user_id = Auth::id(); // Set the user_id to the authenticated user's ID
        $ticket->token_no = rand(1000, 10000);
        $ticket->subject = $request->subject;
        $ticket->description = $request->description;
        $ticket->status = 'open';
        $ticket->save();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();

            $fileModel = new Files();
            $fileModel->name = $fileName;
            $fileModel->user_id = Auth::id();
            $fileModel->ticket_id = $ticket->id;
            $fileModel->reply_id = 0;
            $fileModel->save();

            $file->storeAs('', $fileName);
        }

        $title = $request->subject;
        $ticket_id = $ticket->id;
        if (Auth::check()) {
            $user_name = Auth::user()->name;
        } else {
            // User is not authenticated, handle this case accordingly
            // For example, you can redirect them to the login page.
            return redirect()->route('login');
        }

        $users = User::all();
        // foreach ($users as $user) {
        //     if ($user->hasRole('admin')) {
        //         $user->notify(new NewTicket($title, $user_name, $ticket_id));
        //     }
        // }

        $settings = Settings::first();
        if ($settings->ticket_email == 'yes') {
            $department = Departments::find($request->department_id);
            Mail::send('mails.thanks', ['ticket' => $request, 'department' => $department], function ($message) use ($settings) {
                $message->from('no-reply@gmail.com', 'Ticket Plus');
                $message->subject('New Ticket Created');
                $message->to($settings->admin_email);
            });
        }

        return redirect()->route('tickets'); // Use 'redirect' instead of 'Redirect'
    }


    public function adminTickets(){
        $settings = settings::first();

        $tickets = tickets::paginate(15);
        $open = tickets::where('status', 'open')->count();
        $replied = tickets::where('status', 'replied')->count();
        $closed = tickets::where('status', 'closed')->count();
        $pending = tickets::where('status', 'pending')->count();
        $departments = departments::all();
        $tickets_depart = tickets::all();
        return view('admin.tickets.index', compact('settings','tickets', 'open','replied', 'closed', 'pending', 'departments', 'tickets_depart'));
    }
    public function editTickets($id)
    {
        $settings = settings::first();

        $user = User::find(Auth::id());
        $departments = departments::all();
        $ticket = tickets::find($id);

        if ($user->hasRole('admin')) {
            if (!$ticket) {
                return redirect()->route('tickets'); // Redirect to the appropriate route
            }
        } else {
            // Check if the user is not authorized to edit the ticket
            if (Gate::denies('edit-ticket', $ticket)) {
                return abort(403, 'Unauthorized'); // Or redirect to an error page
            }
        }

        // Continue with the rest of your code
        $files = files::where('ticket_id', $ticket->id)->get();
        return view('tickets.edit_ticket', compact('settings','ticket', 'files', 'departments'));
    }

    public function ticketDetail($id)
{
    $settings = settings::first();

    $user = User::find(Auth::id());
    $staff = [];
    $users_list = User::all();

    foreach ($users_list as $item) {
        if ($item->hasRole('staff')) {
            $staff[] = $item;
        }
    }

    if ($user->hasRole('admin')) {
        $ticket = tickets::find($id);

        if ($ticket) {
            $replies = replies::where('ticket_id', $ticket->id)->get();
            $files = files::where('ticket_id', $ticket->id)->get();

            return view('tickets.details', compact('settings','ticket', 'replies', 'files', 'staff'));
        } else {
            return redirect()->to('tickets');
        }
    } else {
        $ticket = tickets::where(function ($query) use ($id) {
            $query->where(['assigned_to' => Auth::id(), 'id' => $id])
                ->orWhere(['user_id' => Auth::id(), 'id' => $id]);
        })->first();

        if ($ticket) {
            $replies = replies::where('ticket_id', $ticket->id)->get();
            $files = Files::where('ticket_id', $ticket->id)->get();

            return view('tickets.details', compact('settings','ticket', 'replies', 'files', 'staff'));
        } else {
            return redirect()->to('tickets');
        }
    }
}


    public function updateTickets(Request $request, $id){

        $this->validate($request, [
            'department' => 'required',
            'subject' => 'required|max:300',
            'description' => 'required|max:10000'
        ]);

        $tickets = Tickets::find($id);
        $tickets->subject = $request->subject;
        $tickets->department_id = $request->department;
        $tickets->description = $request->description;
        $tickets->save();
        return redirect()->back()->withMessage('ticket has been updated successfully');
    }

    public function deleteTickets($id){
        $ticket = Tickets::find($id);
        $files = Files::where('ticket_id', $ticket->id)->get();
        foreach ($files as $file){
            Storage::delete($file->name);
        }
        Files::where('ticket_id', $ticket->id)->delete();
        Replies::where('ticket_id', $ticket->id)->delete();
        Tickets::find($id)->delete();
        return 'success';
    }
    public function updateStatus(Request $request, $id){
        $tickets = tickets::find($id);
        $tickets->status = $request->status ;
        $tickets->save();

        $title = $tickets->subject;
        $ticket_id = $id;
        $status = $request->status;
        $user = User::find($tickets->user_id);
        // $user->notify(new TicketStatus($title, $status, $ticket_id));
        return redirect()->back();
    }

    public function assignTicket(Request $request, $id){
        $ticket = tickets::find($id);
        $ticket->assigned_to = $request->assign;
        $ticket->save();
        return redirect()->back();

    }

    public function download($file_name){

        $path = storage_path('app/').$file_name;
        return response()->download($path);
    }

    public function addReply(Request $request)
{
    $reply = new Replies();
    $reply->reply = $request->reply;
    $reply->ticket_id = $request->ticket_id;
    $reply->user_id = Auth::id();
    $reply->save();

    $ticket = Tickets::find($request->ticket_id);
    $title = $ticket->subject;
    $ticket_id = $request->ticket_id;
    $reply_user = Auth::user()->name;

    // Notify the ticket owner and assigned staff if applicable
    if (Auth::user()->hasRole('admin')) {
        $user = User::find($ticket->user_id);
        $user->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));

        if (!empty($ticket->assigned_to)) {
            $user2 = User::find($ticket->assigned_to);
            $user2->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));
        }
    } elseif (Auth::user()->hasRole('client')) {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                $user->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));
            }
        }

        if (!empty($ticket->assigned_to)) {
            $user2 = User::find($ticket->assigned_to);
            $user2->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));
        }
    } elseif (Auth::user()->hasRole('staff')) {
        $users = User::all();
        foreach ($users as $user) {
            if ($user->hasRole('admin')) {
                $user->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));
            }
        }

        $user2 = User::find($ticket->user_id);
        $user2->notify(new TicketReplyNotification($title, $reply_user, $ticket_id));
    }

    $user_image = '';

        if(Auth::user()->avatar ==  null){
            $user_image = '<img src="'.asset('uploads/avatar.png').'" alt="avatar" class="img-circle">';
        }else{
            $user_image = '<img src="'.asset('uploads').'/'.Auth::user()->avatar.'" alt="avatar" class="img-circle">';
        }

        $output = '';

        if ($request->hasFile('file')) {
            $file = new Files();
            $file->name = $request->file('file')->getClientOriginalName();
            $file->user_id = Auth::id();
            $file->ticket_id = $request->ticket_id;
            $file->reply_id = $reply->id;
            $file->save();
            Storage::putFileAs('', $request->file('file'), $request->file('file')->getClientOriginalName());
            $output = '
                        <div class="ticket-detail-box">
                            <div class="outer-white"  id="'.$reply->id.'">
                                <h3 class="title">
                                '.$user_image.'
                                '.Auth::user()->name.' |
                                <span class="text-muted date">Date: '. $reply->created_at->format('d-m-Y').'</span>
                                 <span class="text-muted time">
                                 @ '.$reply->created_at->format('H:i').'
                                </span>

                                <a href="javascript:;"  class="basic-button delete-btn red pull-right" data-id="'.$reply->id.'">
                                 <i class="fa fa-trash"></i>
                                  </a>


                                </h3>
                                <p class="details">
                                    '.$request->reply.'
                                </p>
                                <p class="file_link">
                                    Attached File:
                                        <a href="'.url('download').'/'.$file->name.'">
                                            <i class="fa fa-paperclip"></i> '.$file->name.'
                                        </a>
                                </p>
                            </div>
                        </div>';
            return $output;
        }else{

            $output = '
                        <div class="ticket-detail-box">
                            <div class="outer-white" id="'.$reply->id.'">
                                <h3 class="title">
                                 '.$user_image.'
                                '.Auth::user()->name.' |
                                <span class="text-muted date">Date: '. $reply->created_at->format('d-m-Y').'</span>
                                 <span class="text-muted time">
                                 @ '.$reply->created_at->format('H:i').'
                                </span>
                                 <a href="javascript:;"  class="basic-button delete-btn red pull-right" data-id="'.$reply->id.'">
                                 <i class="fa fa-trash"></i>
                                  </a>
                                </h3>
                                <p class="details">
                                 '.$request->reply.'
                                  </p>
                            </div>
                        </div>';
            return $output;
        }
    }

    public function deleteReplies($id) {
        $files = Files::where(['reply_id' => $id, 'user_id' => Auth::id()])->get();
        foreach ($files as $file){
            Storage::delete($file->name);
        }
        Files::where(['reply_id' => $id, 'user_id' => Auth::id()])->delete();
        Replies::where(['id' => $id, 'user_id' => Auth::id()])->delete();
        return 'success';
    }




}
