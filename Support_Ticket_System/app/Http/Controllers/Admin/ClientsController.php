<?php

namespace App\Http\Controllers\Admin;

use App\Models\role;
use App\Models\User;
use App\Models\files;
use App\Models\tickets;
use App\Models\settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ClientsController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = settings::first();
        $users = User::Paginate(15);
        return view('admin.clients.index',compact('users','settings'));
    }

    /**
     * Create new resource.
     */
    public function create(){
        $settings = settings::first();

        return view('admin.clients.add',compact('settings'));
    }

    /**
     * Add new resource to database.
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:32',
            'username' => 'required|alpha_dash|max:100|unique:users',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6',
        ]);
        $user  = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if(!empty($request->file)){
            $request->file->move('uploads', $request->file->getClientOriginalName());
            $user->avatar = $request->file->getClientOriginalName();
        }
        $user->save();
        $role = role::where('name', 'client')->first();
        $user->roles()->attach($role->id);
        return Redirect::to('admin/clients')->withMessage('New client has been added');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.clients.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required',
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if(!empty($request->file)){
            $request->file->move('uploads', $request->file->getClientOriginalName());
            $user->avatar = $request->file->getClientOriginalName();
        }

        $user->save();
        if ($request->role == 'admin') {
            $newRole = Role::where('name', 'admin')->first();

            // Detach all current roles
            $user->roles()->detach();

            // Attach the new role
            $user->roles()->attach($newRole->id);
        }
        if ($request->role == 'client') {
            $newRole = Role::where('name', 'client')->first();

            // Detach all current roles
            $user->roles()->detach();

            // Attach the new role
            $user->roles()->attach($newRole->id);
        }
        if ($request->role == 'staff') {
            $newRole = Role::where('name', 'staff')->first();

            // Detach all current roles
            $user->roles()->detach();

            // Attach the new role
            $user->roles()->attach($newRole->id);
        }
        return redirect::to('admin/clients')->withMessage('Record has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tickets = tickets::where('user_id', $id)->get();
        foreach ($tickets as $ticket){
            $files = files::where('ticket_id' , $ticket->id)->get();
            foreach ($files as $file){
                Storage::delete($file->name);
            }
            Replies::where('ticket_id', $ticket->id)->delete();
            Files::where('ticket_id', $ticket->id)->delete();
        }
        Tickets::where('user_id', $id)->delete();
        User::find($id)->delete();
        return 'success';
    }
}
