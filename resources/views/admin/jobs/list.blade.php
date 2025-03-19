@extends('front.layouts.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
         
            <div class="col-lg-3">
              @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.account.message')
                   <div class="card border-0 shadow">
                        <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Jobs</h3>
                            </div>
                            <div style="margin-top: -10px;">
                              
                            </div>
                            
                        </div>
                        <div class="table-responsive">
                            <table class="table ">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Created By</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col ">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    @if($jobs->isNotEmpty())
                                    @foreach ($jobs as $job)
                                        
                                  
                                    <tr class="active">
                                    <td>
                                    {{ $job->id }}
                                    </td>
                                        <td>
                                            <p class="">{{ $job->title }}</p>
                                           <p>Applicants: {{ $job->applications->count() }}</p>
                                           
                                        </td>
                                        <td> <div>{{ $job->user->name }}</div></td>
                                        <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M, Y') }}</td>
                                       <td>
                                       @if($job->status == 1)
                                        <p class="badge text-bg-success">Active</p>
                                        @else    
                                        <p class="badge text-bg-danger ">Block</p>    
                                       @endif
                                       </td>
                                        <td>
                                            <div class="action-dots ml-5 ">
                                                <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                  
                                                    <li><a class="dropdown-item" href="{{ route('admin.jobs.edit',$job->id) }}"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteJob({{ $job->id }})"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a></li>
                                                </ul>
                                                {{-- {{ route('admin.jobs.edit',$user->id) }} --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                   
                              
                                </tbody>
                                
                            </table>
                        </div>
                        <div>
                            {{ $jobs->links()  }}
                        </div>
                    </div>
                   </div>       
            </div>
        </div>
    </div>
</section>


    
@endsection

@section('customJs')

<script type="text/javascript">
function deleteJob(id){
    if(confirm('Are you sure you want to delete this job?')){
        $.ajax({
            url:'{{ route("admin.jobs.delete") }}',
            type:'delete',
            data:{id:id},
            dataType:'json',
            success:function(response){
                window.location.href = "{{ route('admin.jobs') }}"
            }
        })
        
    } else{
        return false;
    }
}
</script>
@endsection