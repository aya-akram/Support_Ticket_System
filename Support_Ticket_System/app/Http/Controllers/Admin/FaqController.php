<?php

namespace App\Http\Controllers\Admin;

use App\Models\faqs;
use App\Models\settings;
use App\Models\departments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class FaqController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $faqs = faqs::orderBy('id', 'desc')->Paginate(15);
        // return view('admin.faq.index', compact('faqs'));
            // Load the FAQs with their related departments
            $settings = settings::first();

            $faqs = faqs::with('departments')->paginate(15);

            return view('admin.faq.index', compact('faqs','settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $departments =  departments::all();
        return view('admin.faq.create', compact('departments'));

    //     // Validation logic here
    // $request->validate([
    //     'department' => 'required',
    //     'subject' => 'required',
    //     'description' => 'required',
    // ]);

    // // Create a new FAQ entry using the validated data
    // faqs::create([
    //     'department_id' => $request->input('department'),
    //     'subject' => $request->input('subject'),
    //     'description' => $request->input('description'),
    // ]);

    // // Redirect back with a success message
    // return redirect()->back()->withMessage('FAQ entry created successfully');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'department' => 'required',
            'subject' => 'required',
            'description' => 'required',
        ]);
        $faq = new faqs();
        $faq->subject = $request->subject;
        $faq->department_id = $request->department;
        $faq->description = $request->description;
        $faq->save();

        return Redirect::to('admin/faq');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $departments =  Departments::all();
        $faq = faqs::find($id);
        return view('admin.faq.edit', compact('faq', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'department' => 'required',
            'subject' => 'required',
            'description' => 'required',
        ]);
        $faq = faqs::find($id);
        $faq->subject = $request->subject;
        $faq->department_id = $request->department;
        $faq->description = $request->description;
        $faq->save();
        return redirect()->back()->withMessage('Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        faqs::find($id)->delete();
        return 'success';
    }
}
