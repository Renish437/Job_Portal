@extends('front.layouts.app')
@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">
                @include('front.account.message')
                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                    <a href="#">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->job_types->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now">
                                    <a class="heart_mark {{ $savedJobCount == 1 ? 'saved' : '' }}" href="javascript:void(0)" onclick="savedJob({{ $job->id }})">
                                        <i class="fa {{ $savedJobCount == 1 ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                                    </a>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Job description</h4>
                            @if($job->description)
                            <p>{!! nl2br($job->description) !!}</p>
                            @else
                            <p>No Description</p>
                            @endif
                        </div>
                        <div class="single_wrap">
                            <h4>Responsibility</h4>
                            @if ($job->responsibility)
                            <ul class="custom-list">
                            
                              <p>{!! nl2br($job->responsibility) !!}</p>
                              @else
                              <h2>No Responsibilities </h2>
                              @endif
                                
                            </ul>
                        </div>
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            <ul class="list-unstyled">
                               @if($job->qualifications)
                               <p style="text-decoration: none;">{!! nl2br($job->qualifications) !!}</p>
                               @else

                               <h2>No Qualifications </h2>
                               @endif
                           
                            </ul>
                        </div>
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            @if ($job->benefits)
                            <p>{!! $job->benefits !!}</p>
                            @else 
                            <h2>No Benefits</h2>            
                            @endif
                        </div>
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            @if (Auth::check())
                            <a href="#" onclick="savedJob({{ $job->id }})" class="btn btn-secondary">Save</a>
                            @else   
                            <a href="javascript:void(0)" class="btn btn-secondary disabled">Login to save</a>
                            @endif
                           @if (Auth::check())
                           <a href="#" onclick="applyJob({{ $job->id }})" class="btn btn-primary">Apply</a>
                           @else   
                           <a href="javascript:void(0)" class="btn btn-primary disabled">Login to apply</a>
                           @endif
                        </div>
                    </div>
                </div>
                @if (Auth::user())
                @if (Auth::user()->id==$job->user_id)
                <div class="card shadow border-0 mt-4">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                  
                                        <h4>Applicants</h4>
                                    
                                   
                                </div>
                            </div>
                            <div class="jobs_right">
                                
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                       <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($applications->isNotEmpty())
                 
                        @foreach ($applications as $application)
                       
                        <tr>
                            <td>{{ $application->user->name }}</td>
                            <td>{{ $application->user->email }}</td>
                            <td>{{ $application->user->mobile }}</td>
                            <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M Y') }}</td>
                        </tr>
             
                        @endforeach
                  @else         
                          <tr>
                            <td colspan="4"><h3 class="text-center pl-4">Applicants not found</h3></td>
                          </tr>
                        @endif

                    </tbody>
                       </table>
                    </div>
                </div>
                @endif
               
                @endif
             
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summary</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</span></li>
                                <li>Vacancy: <span>{{ $job->vacancy }} Position</span></li>
                                <li>Salary: <span>{{ ($job->salary)?($job->salary)  :'No salary Available' }}/monthly</span></li>
                                <li>Location: <span>{{ $job->location }}</span></li>
                            <li>Job Nature: <span> {{ $job->job_types->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>{{ $job->company_name }}</span></li>
                                <li>Locaion: <span>{{ ($job->company_location)?$job->company_location:'No loaction' }}</span></li>
                                <li>Website: <span><a href="{{ $job->company_website }}" target="_blank">{{ $job->company_website }}</a> </span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    
@endsection

@section('customJs')
<script>
    function applyJob(id){
        if(confirm('Are you sure you want to apply for this job?')){
            $.ajax({
                url:'{{ route("apply-job") }}',
                type:'post',
                data:{id:id},
                dataType:'json',
                success:function(response){
                   
                        window.location.href='{{ url()->current() }}';
                  
                    // window.location.href = "{{ route('my-jobs') }}"
                }
            })
        }
    }
    function savedJob(id){
        
            $.ajax({
                url:'{{ route("saves-job") }}',
                type:'post',
                data:{id:id},
                dataType:'json',
                success:function(response){
                   
                        window.location.href='{{ url()->current() }}';
                  
                    // window.location.href = "{{ route('my-jobs') }}"
                }
            })
        
    }
</script>

@endsection
