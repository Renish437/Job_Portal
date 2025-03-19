@extends('front.layouts.app')
@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;

            </div>

            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    @if (Session::has('success'))
                        <div class="alert alert-success" id="Message">
                            <p class="mb-0 pb-0"> {{ Session::get('success') }}</p>
                        </div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger" id="Message">
                        <p class="mb-0 pb-0"> {{ Session::get('error') }}</p>
                    </div>
                @endif
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Reset Password</h1>
                        <form action="{{ route('account.reset-password-store') }}" method="post">
                           @csrf
                           <input type="hidden" name="token" value="{{ $tokenString }}">
                            <div class="mb-3">
                                <label for="" class="mb-2">New password*</label>
                                <input type="password" name="new_password" id="new_password"
                                   value="" class="form-control 
                            @error('new_password') is-invalid @enderror"
                                    placeholder="New Password">
                                @error('new_password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm password*</label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                   value="" class="form-control 
                            @error('confirm_password') is-invalid @enderror"
                                    placeholder="Confirm Password">
                                @error('confirm_password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                           <div class="justify-content-between d-flex">
                                <button class="btn btn-primary mt-2">Submit</button>
                               
                            </div>
                           
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Do not have an account? <a href="{{ route('login') }}">Back to Login</a></p>
                    </div>
                </div>
            </div>
            <div class="py-lg-5">&nbsp;</div>
        </div>
    </section>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mx-3">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            $("#Message").fadeOut('slow');
        }, 3000); // 4 seconds
    </script>
@endsection
