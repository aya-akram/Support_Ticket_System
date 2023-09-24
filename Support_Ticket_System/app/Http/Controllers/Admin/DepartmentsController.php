<?php

namespace App\Http\Controllers\Admin;

use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentsController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = settings::first();

        $departments = departments::orderBy('id', 'desc')->paginate(15);
        return view('admin.departments.index', compact('departments','settings'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        departments::create($request->all());
        return redirect()->back()->withMessage('Department added successfully');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = departments::find($id);
        $output = '
                    <label class="control-label">Department Name:</label>
                    <input type="text" class="form-control" name="name" value="'.$department->name.'" required/>
                    <input type="hidden" name="id" value="'.$department->id.'"/>';
         return  $output;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the department by ID
        $department = departments::find($id);

        // Check if the department exists
        if (!$department) {
            return redirect()->back()->withErrors(['message' => 'Department not found.']);
        }

        // Update the department's name
        $department->name = $request->name;
        $department->save();

        return redirect()->back()->withMessage('Department updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        departments::find($id)->delete();
        return 'success';
    }
}
