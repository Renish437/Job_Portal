<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Jobe;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    //
    public function index(){
        $jobs=Jobe::orderBy('created_at','desc')->with(['user','applications'])->paginate(10);
        return view('admin.jobs.list',[
            'jobs'=>$jobs
        ]);
    }
    public function edit($id){
        $job=Jobe::findOrFail($id);
         $categories=Category::orderBy('name','asc')->get();
         $job_types=JobType::orderBy('name','asc')->get();
        return view('admin.jobs.edit',compact('job','categories','job_types'));
    }
    public function update(Request $request,$id){
        $rules=[
            'title'=>'required|min:3|max:200',
            'category'=>'required',
            'job_nature'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|min:3|max:75',

        ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->passes()){

            $job=Jobe::findOrFail($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->job_nature;
           
            $job->vacancy=$request->vacancy;
            $job->salary=$request->salary;
            $job->location =$request->location ;
            $job->description=$request->description;
            $job->benefits =$request->benefits ;
            $job->experience =$request->experience ;
            $job->responsibility =$request->responsibility ;
            $job->qualifications=$request->qualifications;
            $job->keywords=$request->keywords;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->company_website;
            $job->status=$request->status;
            $job->is_featured=(!empty($request->is_featured))?$request->is_featured:0;

            $job->save();

            session()->flash('success','Job updated successfully');
            return response()->json([
                'status'=>true,
                'errors'=>[]
            ]);
            
            
        }else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }
    public function destroy(Request $request){
        $job= Jobe::findOrFail($request->id);
       
        Jobe::where('id',$request->id)->delete();
        session()->flash('success','Job deleted successfully');
        return response()->json([
        'status'=>true,
        'errors'=>[]
        ]);
    }
}
