<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    //
    public function index(){
        $applications=JobApplication::orderBy('created_at','desc')
        ->with(['job','categories','employer','user'])
        ->paginate(3);
        return view('admin.job-applications.list',compact('applications'));
    }
    public function destroy(Request $request){
        $application=JobApplication::findOrFail($request->id);
        $application->delete();
        session()->flash('success','Application deleted successfully');
        return response()->json([
            'status'=>true,
            'errors'=>[]
          ]);
    }
}
