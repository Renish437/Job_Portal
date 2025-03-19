<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JobApplication;
use App\Models\Jobe;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    //This will show  registertion page to user
    public function register(){
return view('front.account.register');
    }

    public function saveUser(Request $request){
       $validator=Validator::make($request->all(),[
        'name'=>'required',
        'email'=>'required|email|unique:users,email',
        'password'=>'required|min:5|same:confirm_password',
        'confirm_password'=>'required',
       ]);
       if($validator->passes()){
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->save();

        session()->flash('success','Your account has been created successfully');
          return response()->json([
            'status'=>true,
            'errors'=>[],

          ]);
       }
       else{
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors(),

        ]);
       }
    }
    //This will show login page to user
    public function login(){

        return view('front.account.login');
    }
    public function authenticate(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        if($validator->passes()){
           if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            return redirect()->route('profile');
           }
           else{
            return redirect()->route('login')
            ->with('error','Either Email or Password is incorrect');
           }
           
        }
        else{
           return redirect()->route('login')
           ->withErrors($validator)
           ->withInput($request->only('email'));
        }
    }
    public function profile(){

       $id= Auth::user()->id;
       $user=User::find($id);
       

       
        return view('front.account.profile',compact('user'));
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function updateProfile(Request $request){
        $id=Auth::user()->id;
       
        $validator=Validator::make($request->all(),[
         'name'=>'required|min:3',
         'email'=>'required|email|unique:users,email,'.$id.',id',
        
        ]);
        if( $validator->passes()){
       $user=User::find($id);
       $user->name=$request->name;
       $user->email=$request->email;
       $user->designation=$request->designation;
       $user->mobile=$request->mobile;
       $user->save();
         
       session()->flash('success','User Updated Successfully!');
       return response()->json([
        'status'=>true,
        'errors'=>[]
        ]);
       
        }
        else{
            return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
            ]);
        }
    }
    public function updateProfilePic(Request $request){
        // dd($request->all());
        $id= Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);
    
        if ($validator->passes()) {
            $image=$request->image;
            $ext=$image->getClientOriginalExtension();
            $imageName=$id.'-'.time().'.'.$ext; 
             
            $image->move(public_path('/profile_pic'),$imageName);

            // Create a small thumbnail
$sourcePath=public_path('/profile_pic/'.$imageName);

            $manager = new ImageManager(Driver::class);
$image = $manager->read($sourcePath);

// crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
$image->cover(150, 150);
$image->toPng()->save(public_path('/profile_pic/thumb/'.$imageName));

File::delete(public_path('/profile_pic/thumb/'.Auth::user()->image));
File::delete(public_path('/profile_pic/'.Auth::user()->image));
            User::where('id',$id)->update(['image'=>$imageName]);


            session()->flash('success','Profile Picture updated successfully');
            return response()->json([
                'status' => true,
                'errors' => []
            ]);
            
        }
        else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
       
    }
    public function updatePassword(Request $request){
       $validator=Validator::make($request->all(),[
        'old_password'=>'required',
        'new_password'=>'required|min:5',
        'confirm_password'=>'required|same:new_password'
       ]);
       if($validator->fails()){
        return response()->json([
            'status'=>false,
             'errors'=>$validator->errors()
        ]);
       }
       if(Hash::check($request->old_password,Auth::user()->password)==false){
        session()->flash('error','Your old password is incorrect');
        return response()->json([
            'status'=>true,
             
        ]);
       }
       $user=User::find(Auth::user()->id);
       $user->password=Hash::make($request->new_password);
       $user->save();
       session()->flash('success','Password updated successfully');
       return response()->json([
           'status'=>true,
            
       ]);
    }
    public function createJob(){
       
         $category = Category::orderBy('name','asc')->where('status',1)->get();
         $job_types=JobType::orderBy('name','asc')->where('status',1)->get();
       
        return view('front.account.job.create', compact('category','job_types'));
    }
    public function saveJob(Request $request){
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

            $job=new Jobe();
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->job_nature;
            $job->user_id=Auth::user()->id;
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
          

            $job->save();

            session()->flash('success','Job added successfully');
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
    public function updateJob(Request $request,$id){
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

            $job=Jobe::find($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->job_nature;
            $job->user_id=Auth::user()->id;
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

    public function myJobs(){
        $jobs=Jobe::with(['categories','job_types'])
        ->orderBy('created_at','desc')
        ->where('user_id',Auth::user()->id)->paginate(10);
        return view('front.account.job.my-jobs',compact('jobs'));
    }
    public function showJobDetails($id){
        $job=Jobe::with(['categories','job_types'])->findOrFail($id);
        
        return view('front.account.job.show-job-detail',compact('job'));
    }
    public function jobEdit(Request $request, $id) {
        $categories = Category::orderBy('name', 'asc')->where('status', 1)->get();
        $job_types = JobType::orderBy('name', 'asc')->where('status', 1)->get();
    
        // Load the job with its job_type relation
        $job = Jobe::with('job_types')->where('user_id', Auth::id())->findOrFail($id);
    
        return view('front.account.job.edit', [
            'job' => $job,
            'categories' => $categories,
            'job_types' => $job_types
        ]);
    }
    public function deleteJob(Request $request){
       $job= Jobe::where([
           'user_id'=>Auth::user()->id,
           
        ])->findOrFail($request->id);
        if($job==null){
            session()->flash('error','Either job deleted or not found');
            return response()->json([
                'status'=>false,
               
            ]);
        }
        Jobe::where('id',$request->id)->delete();
        session()->flash('success','Job deletd successfully');
        return response()->json([
        'status'=>true
        ]);
    }
    public function myJobApplication(){
        return view('front.account.job.my-job-application',[
            'job_application'=>JobApplication::with(['job','categories'])->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10)]);
    }
    public function removeJob(Request $request){
   $jobApplication=JobApplication::where([
    'id'=>$request->id,
    'user_id'=>Auth::user()->id
   ])->first();
if($jobApplication==null){
    session()->flash('error','Either job application deleted or not found');
    return response()->json([
        'status'=>false,
       
    ]);
}
JobApplication::findOrFail($request->id)->delete();
session()->flash('success','Job application deleted successfully');
return response()->json([
    'status'=>true,
    'message'=>'Job application deleted successfully'

]);



    }
    public function savedJobs(){
        // $job_application=JobApplication::with(['job','categories'])->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);
        $savedJobs=SavedJob::where([
            'user_id'=>Auth::user()->id
        ])->with('job')->orderBy('created_at','desc')
        ->paginate(10);
        return view('front.account.job.saved-job',[
            'savedJobs'=> $savedJobs
        ]);
    }
    public function deleteSavedJob(Request $request){
        $id=$request->id;
          $savedJob=SavedJob::findOrFail($id);
          if(  $savedJob==null){
            session()->flash('error','Saved job not found');
            return response()->json([
                'status'=>false,
                'message'=>"Saved job not found"
            ]);
          }
          SavedJob::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
          ])->delete();
          $message="Saved job deleted successfully";
          session()->flash('success',$message);
   
              return response()->json([
                'status'=>true,
                'message'=>$message
              ]);
              
    }
    public function forgot_password(){
return view('front.account.forgot-password');
    }
    public function store_forgot_password(Request $request){
        // dd(\App\Models\User::where('email', 'renish@gmail.com')->exists());

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:mysql.users,email'

        ]);
        if($validator->fails()){
          
            return redirect()->route('account.forgot-password')->withInput()
            ->withErrors($validator);
          
        }
        // $email=$request->email;
        // $user=User::where('email',$email)->first();
        // if($user==null){
        //     session()->flash('error','Email not found');
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>'Email not found'
        //     ]);
        // }
        // $password=Str::random(8);
        // $user->password=Hash::make($password);
        // $user->save();
        // $message="Your new password is ".$password;
        // session()->flash('success',$message);
        // return response()->json([
        //     'status'=>true,
        //     'message'=>$message
        //   ]);
    }

    
}
