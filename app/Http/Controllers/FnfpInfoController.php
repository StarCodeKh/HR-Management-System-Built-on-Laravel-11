<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FnfpInfo;

class FnfpInfoController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'account_no' => 'required|string|max:255',
            'employee_contribution' => 'required|numeric',
            'employer_contribution' => 'required|numeric',
        ]);
    
        // Assuming you're getting the user_id from the session or request
        $user_id = auth()->id();  // or use $request->user_id if you're passing it in the form
    
        // Store the data with the user_id
        FnfpInfo::create([
            'user_id' => $user_id,  
            'name' => $request->name,
            'account_no' => $request->account_no,
            'employee_contribution' => $request->employee_contribution,
            'employer_contribution' => $request->employer_contribution
        ]);
    
        return redirect()->back()->with('success', 'FNPF Information saved successfully!');
    }
    
    public function index()
    {
        // Retrieve all records
        $fnfpInfos = FnfpInfo::all();

        return view('fnfp.index', compact('fnfpInfos'));
    }
}
