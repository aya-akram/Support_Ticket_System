<?php

namespace App\Http\Controllers\Admin;

use App\Models\role;
use App\Models\User;
use App\Models\files;
use App\Models\tickets;
use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class StaffController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = settings::first();
        $users = User::paginate(10);
        return view('admin.staff.index',compact('users','settings'));
    }

    /**
     * Create new resource.
     */
    public function create(){
        $departments = departments::all();
        return view('admin.staff.add',compact('departments'));
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
            'designation' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->designation = $request->designation;
        $user->department_id = $request->department;

        if (!empty($request->file)) {
            $request->file->move('uploads', $request->file->getClientOriginalName());
            $user->avatar = $request->file->getClientOriginalName();
        }

        $user->save();

        // Check if the role exists before attaching it
        $role = Role::where('name', 'staff')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('staff.index')->withMessage('New staff member has been added');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        $departments = Departments::all();
        return view('admin.staff.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:32',
            'username' => 'required|max:32|unique:users,username,'.$id,
            'email' => 'email|max:40|unique:users,email,'.$id,
            'designation' => 'required',
        ]);


        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->designation = $request->designation;
        $user->department_id = $request->department;
        $user->save();
        if(!empty($request->file)){
            $request->file->move('uploads', $request->file->getClientOriginalName());
            $user->avatar = $request->file->getClientOriginalName();
        }

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






        return redirect::to('admin/staff')->withMessage('Record has been updated');
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
