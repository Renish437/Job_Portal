<?php

namespace App\Http\Controllers;

use App\Mail\JobNotification;
use App\Models\Category;
use App\Models\JobApplication;
use App\Models\Jobe;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    //This method will show jobs page
    public function index(Request $request){
        $categories=Category::orderBy('name','desc')->where('status',1)->get();
        $job_types=JobType::orderBy('name','desc')->where('status',1)->get();

        $jobs=Jobe::where('status',1);
        
        //search using keywords
        if(!empty($request->keyword)){
          
            $jobs=$jobs->where(function($query) use($request){
              $query->orWhere('title','like','%'.$request->keyword.'%');
              $query->orWhere('keywords','like','%'.$request->keyword.'%');
       
            });
        }
        //search using location
        if(!empty($request->location)){
          $jobs=$jobs->where('location',$request->location);
        }

        //search using category
        if(!empty($request->category)){
          $jobs=$jobs->where('category_id',$request->category);
        }
        $jobTypeArray=[];
        //search using job_type
        if(!empty($request->jobType)){
         $jobTypeArray= explode(',',$request->jobType);
          $jobs=$jobs->whereIn('job_type_id',$jobTypeArray);
        }
        //search using experience
        if(!empty($request->experience)){
    
          $jobs=$jobs->where('experience',$request->experience);
        }

        $jobs=$jobs->with(['categories','job_types']);
        $jobs = $jobs->orderBy('created_at', ( $request->sort=='0') ? 'asc' : 'desc');
      
        
        $jobs= $jobs->paginate(9);
  

  return view('front.jobs',[
    'categories'=>$categories,
    'job_types'=>$job_types,
    'jobs'=>$jobs,
    'jobTypeArray'=>$jobTypeArray
  ]);
    }
    public function detail($id){
      $savedJobCount=0;
      if(Auth::user()){
        $savedJobCount=SavedJob::where([
          'job_id'=>$id,
          'user_id'=>Auth::user()->id
        ])->count();
      }
    
      $job=Jobe::with(['categories','job_types'])->where('status',1)->findOrFail($id);

      //fetch applicants
     $applications= JobApplication::where('job_id',$id)
     ->with('user')
     ->get();
     
        return view('front.job-detail',[
            'job'=>$job,
            'savedJobCount'=>$savedJobCount,
            'applications'=>$applications
        ]);
    }
    public function applyJob(Request $request){
$id=$request->id;

$job=Jobe::findOrFail($id);

//If job not found in db
if($job==null){
  session()->flash('error','Job not found');
return response()->json([
  'status'=>false,
  'message'=>'Job not found'
]);
}


//You cannot apply on your own job

$employer_id=$job->user_id;
if($employer_id==Auth::user()->id){
  session()->flash('error','You cannot apply on your own job');
  return response()->json([
    'status'=>false,
    'message'=>'You cannot apply on your own job'
  ]);
}
//You cannot apply on job more than once
$applicationCount=JobApplication::where([
  'job_id'=>$id,
  'user_id'=>Auth::user()->id
])->count();
if($applicationCount>0){
  session()->flash('error','You have already applied for this job');
  return response()->json([
    'status'=>false,
    'message'=>'You have already applied for this job'
  ]);
}


$application =new JobApplication();
$application->job_id=$job->id;
$application->user_id=Auth::user()->id;
$application->employer_id=$employer_id;
$application->applied_date=now();
$application->save();



session()->flash('success','You have successfully applied for this job');
//Send Notifications to employee
$employer=User::where('id',$employer_id)->first();
$mailData=[
'employer'=>$employer,
'user'=>Auth::user(),
'job'=>$job,
];
Mail::to($employer->email)->send(new JobNotification($mailData));
return response()->json([
  'status'=>true,
  'message'=>'You have successfully applied for this job'
]);


    }
    public function savesJob(Request $request){
          $id=$request->id;
          $job=Jobe::findOrFail($id);
          //If job not found in db
          if($job==null){
            session()->flash('error','Job not found');
          return response()->json([
            'status'=>false,
            'message'=>'Job not found'
          ]);
          }
          //check if job is saved by owner
          if($job->user_id==Auth::user()->id){
            session()->flash('error','You cannot save your own job');
          return response()->json([
            'status'=>false,
            'message'=>'You cannot save your own job'
          ]);
}
       //chceck if job already saved
          $savedJobCount=SavedJob::where([
            'job_id'=>$id,
            'user_id'=>Auth::user()->id
          ])->count();

          if($savedJobCount>0){
            session()->flash('error','You have already saved this job');
          return response()->json([
            'status'=>false,
            'message'=>'Job already saved'
          ]);
          }

          $savedJob=new SavedJob();
          $savedJob->job_id=$id;
          $savedJob->user_id=Auth::user()->id;
          $savedJob->save();
          session()->flash('success','Job saved successfully');
     
          return response()->json([
            'status'=>true,
            'message'=>'Job saved successfully'
          ]);
    }

  }