@extends('front.layouts.app')
@section('main')
<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <select name="sort" id="sort" class="form-control">
                        <option value="1" {{ Request::get('sort')=='1' ? 'selected' : '' }}>Latest</option>
                        <option value="0" {{ Request::get('sort')=='0' ? 'selected' : '' }}>Oldest</option>
                        
                        
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-4 col-lg-3 sidebar mb-4">
               <form action="" name="searchForm" id="searchForm">
                <div class="card border-0 shadow p-4">
                    <div class="mb-4">
                        <h2>Keywords</h2>
                        <input type="text" name="keyword" id="keyword" value="{{ Request::get('keyword') }}" placeholder="Keywords" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Location</h2>
                        <input type="text" name="location" id="location" value="{{ Request::get('location') }}" placeholder="Location" class="form-control">
                    </div>

                    <div class="mb-4">
                        <h2>Category</h2>
                        <select name="category" id="category" class="form-control">
                            <option value="">Select a Category</option>
                            @if (!empty($categories))
                            @foreach ($categories as $item)
                            <option {{ Request::get('category')==$item->id ? 'selected' : '' }} value="{{ $item->id }}"> {{  $item->name }}</option>
                            @endforeach
                            
                            @endif
                          
                            
                        </select>
                    </div>                   

                    <div class="mb-4">
                        <h2>Job Type</h2>
                        @if (!empty($job_types))
                        @foreach ($job_types as $item)
                        <div class="form-check mb-2"> 
                            <input {{ (in_array($item->id, $jobTypeArray)) ? 'checked' : '' }} class="form-check-input " name="job_type" type="checkbox" value="{{ $item->id }}" id="job_type-{{ $item->id }}">    
                            <label class="form-check-label " for="job_type-{{ $item->id }}">{{ $item->name }}</label>
                        </div>
                        @endforeach
                        
                        @endif
                    

                    </div> 

                    <div class="mb-4">
                        <h2>Experience</h2>
                        <select name="experience" id="experience" class="form-control">
                            <option value="">Select a Experience</option>
                            <option value="1" {{ (Request::get('experience')==1) ? 'selected' : '' }} >1 Year</option>
                            <option value="2" {{ (Request::get('experience')==2) ? 'selected' : '' }} >2 Years</option>
                            <option value="3" {{ (Request::get('experience')==3) ? 'selected' : '' }} >3 Years</option>
                            <option value="4" {{ (Request::get('experience')==4) ? 'selected' : '' }} >4 Years</option>
                            <option value="5" {{ (Request::get('experience')==5) ? 'selected' : '' }} >5 Years</option>
                            <option value="6" {{ (Request::get('experience')==6) ? 'selected' : '' }} >6 Years</option>
                            <option value="7" {{ (Request::get('experience')==7) ? 'selected' : '' }} >7 Years</option>
                            <option value="8" {{ (Request::get('experience')==8) ? 'selected' : '' }} >8 Years</option>
                            <option value="9" {{ (Request::get('experience')==9) ? 'selected' : '' }} >9 Years</option>
                            <option value="10" {{ (Request::get('experience')==10) ? 'selected' : '' }} >10 Years</option>
                            <option value="10_plus" {{ (Request::get('experience')=='10_plus') ? 'selected' : '' }} >10+ Years</option>
                        </select>
                    </div> 
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-search"></i> Search</button>               
                    <a class="btn btn-secondary mt-2" href="{{ route('jobs') }}" >
                        <i class="fa fa-refresh"></i> Reset</a>               
                </div>
               </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if ($jobs->isNotEmpty())

                    @foreach ($jobs as $item)
                    <div class="col-md-4">
                        <div class="card border-0 p-3 shadow mb-4">
                            <div class="card-body">
                                <h3 class="border-0 fs-5 pb-2 mb-0">{{ Str::words($item->title,2,'...') }}</h3>
                                <p>{{ strip_tags(Str::words($item->description,2)) }}</p>
                                <div class="bg-light p-3 border">
                                    <p class="mb-0">
                                        <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                        <span class="ps-1">{{ $item->location }}</span>
                                    </p>
                                    <p class="mb-0">
                                        <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                        <span class="ps-1">{{ $item->job_types->name }}</span>
                                    </p>
                                    @if (!is_null($item->salary))
                                    <p class="mb-0">
                                        <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                        <span class="ps-1">{{ $item->salary }}</span>
                                       
                                     
                                    </p>
                                  
                                    @endif
                                    {{ $item->categories->name }}
                                </div>

                                <div class="d-grid mt-3">
                                    <a href="{{ route('job-detail', $item->id) }}" class="btn btn-primary btn-lg">Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @else
                    <div class="col-md-12">
                        <h1>Jobs Not found</h1>
                    </div>
                    @endif
                       
                                                 
                    </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 col-md-12">
                {{ $jobs->withQueryString()->links() }}
            </div>
        </div>
    </div>
</section>


    
@endsection

@section('customJs')
{{-- <script>
    $('#searchForm').submit(function(e){
        e.preventDefault();

        var url='{{ route("jobs") }}?';
        //if keyword has a value
        var keyword=$("#keyword").val();
        var location=$("#location").val();
        var category=$("#category").val();
        if(keyword){
            url+='&keyword='+keyword;
        }
        //if location has a value
        if(location){
            url+='&location='+location;
        }
        //if category has a value
        if(category){
            url+='&category='+category;
        }
         // if experience has a value
     var experience=$("#experience").val();
        if(experience!=''){
            url+='&experience='+experience;
        }
   
      var checkedJobTypes=  $("input:checkbox[name='job_type']:checked").map(function() {
         return $(this).val();   
        }).get();
             // if user has checked job type
      if(checkedJobTypes.length>0){
        url+='&jobType='+checkedJobTypes;
      }


      var sort=$("#sort").val();
     
        url+='&sort='+sort;
    
        window.location.href=url;
     
    })
   
$("#sort").change(function(){
    $('#searchForm').submit();
})
</script> --}}
<script>
    $(document).ready(function () {
    function submitForm() {
        var url = '{{ route("jobs") }}?';
        
        // Collect form data
        var keyword = $("#keyword").val();
        var location = $("#location").val();
        var category = $("#category").val();
        var experience = $("#experience").val();
        var sort = $("#sort").val();
        var checkedJobTypes = $("input:checkbox[name='job_type']:checked").map(function () {
            return $(this).val();
        }).get();

        // Append parameters to URL
        if (keyword) url += '&keyword=' + encodeURIComponent(keyword);
        if (location) url += '&location=' + encodeURIComponent(location);
        if (category) url += '&category=' + encodeURIComponent(category);
        if (experience !== '') url += '&experience=' + encodeURIComponent(experience);
        if (checkedJobTypes.length > 0) url += '&jobType=' + encodeURIComponent(checkedJobTypes);
        url += '&sort=' + encodeURIComponent(sort);

        // Redirect to new URL with parameters
        window.location.href = url;
    }

    // Trigger form submission on change events
    $("#sort, #category, #experience").change(submitForm);
    $("input:checkbox[name='job_type']").change(submitForm);

    // Prevent manual form submission (if needed)
    $('#searchForm').submit(function (e) {
        e.preventDefault();
        submitForm();
    });
});

</script>
@endsection