@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show mb-0 pb-2" role="alert"  id="Message">
    {{ Session::get('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  @if(Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show mb-0 pb-2" role="alert"  id="Message">
    {{ Session::get('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <script>
        setTimeout(function(){
        $('#Message').fadeOut('slow');

    },3000);
  </script>