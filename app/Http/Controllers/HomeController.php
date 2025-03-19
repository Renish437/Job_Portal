<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Jobe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //This method will show our home page
    public function index(Request $request){

        $categories=Category::where('status',1)
        ->with('jobes')
        ->orderBy('name','asc')
        ->take(8)->get();

       $newCategories= Category::where('status',1)
        ->with('jobes')
        ->orderBy('name','asc')
      ->get();

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
        $featured_job=Jobe::where('status',1)
        ->with('job_types')
        ->orderBy('created_at','desc')
        ->where('is_featured',1)->take(6)
        ->get();

        $latest_job=Jobe::where('status',1)
        ->with('job_types')
        ->orderBy('created_at','desc')
        ->take(6)
        ->get();


       return view('front.home',[
        'categories'=>$categories,
        'featured_job'=>$featured_job,
        'latest_job'=>$latest_job,
        'jobs'=>$jobs,
        'newCategories'=>$newCategories
       ]);
    }

}
