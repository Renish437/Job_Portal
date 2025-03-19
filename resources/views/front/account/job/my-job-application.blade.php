@extends('front.layouts.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
         
            <div class="col-lg-3">
               @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.account.message')
                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs Applied</h3>
                            </div>
                            <div style="margin-top: -10px;">
                                
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Applied Date</th>
                                        <th scope="col">Applicants</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($job_application->isNotEmpty())
                                    @foreach ($job_application as $application)
                                        
                                  
                                    <tr class="active">
                                        <td>
                                            <div class="job-name fw-500">{{ $application->job->title }}</div>
                                            <div class="info1">{{ $application->job->name }} {{ $application->job->location }}</div>
                                            <div>{{ $application->job->categories->name }}</div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($application->applied_date)->format('d M, Y') }}</td>
                                        <td>{{ $application->job->applications->count() }} Applications</td>
                                        <td>
                                            <div class="job-status text-capitalize">{{ $application->job->status?'Active':'Block' }}</div>
                                        </td>
                                        <td>
                                            <div class="action-dots float-end">
                                                <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('show-job-details',$application->job->id) }}"> <i class="fa fa-eye" aria-hidden="true"></i> View</a></li>
                                      
                                                    <li><a class="dropdown-item" href="#" onclick="removeJob({{ $application->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5" class="text-center fw-bolder fs-1">No Job Applied</td>
                                    </tr>
                                    @endif
                                   
                              
                                </tbody>
                                
                            </table>
                        </div>
                        <div>
                            {{ $job_application->links()  }}
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
    function removeJob(id){
        if(confirm('Are you sure you want to remove?')){
            $.ajax({
                url:'{{ route("remove-job-application") }}',
                type:'delete',
                data:{id:id},
                dataType:'json',
                success:function(response){
                    window.location.href = "{{ route('my-job-application') }}"
                }
            })
            
        } else{
            return false;
        }
    }
</script>

@endsection