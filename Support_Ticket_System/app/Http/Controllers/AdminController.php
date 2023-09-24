<?php

namespace App\Http\Controllers;

use App\Models\faqs;
use App\Models\role;
use App\Models\User;
use App\Models\departments;
use App\Models\settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function showw(){
        $departments =  departments::all();
        $faqs = faqs::all();
        $settings = settings::first();
        return view('home',compact('departments','faqs','settings'));
    }
    public function index()
    {
        $settings = settings::first();
        $users = User::paginate(15);
        return view('admin.admins.index',compact('users','settings'));
    }

    /**
     * Create new resource.
     */
    public function create(){
        $settings = settings::first();

        return view('admin.admins.add',compact('settings'));
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

        $role = Role::where('name', 'admin')->first();

        if ($role) {
            $user->roles()->attach($role->id);
            return Redirect::to('admin/admins')->withMessage('New admin has been added');
        } else {
            // Handle the case where the 'admin' role was not found
            return Redirect::to('admin/admins')->withMessage('Error: Role not found');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.admins.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:32',
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

        // if($request->role == 'admin'){
        //     $role = Role::where('name', 'admin')->first();
        //     $user->detachRoles($user->roles);
        //     $user->roles()->attach($role->id);
        // }

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




        return redirect::to('admin/admins')->withMessage('Record has been updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $tickets = Tickets::where('user_id', $id)->get();
        // foreach ($tickets as $ticket){
        //     $files = Files::where('ticket_id' , $ticket->id)->get();
        //     foreach ($files as $file){
        //         Storage::delete($file->name);
        //     }
        //     Replies::where('ticket_id', $ticket->id)->delete();
        //     Files::where('ticket_id', $ticket->id)->delete();
        // }
        // Tickets::where('user_id', $id)->delete();
        User::find($id)->delete();
        return redirect()->route('admins.index');
        // return 'success';
    }
}
