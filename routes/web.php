<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs',[JobsController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{id}',[JobsController::class,'detail'])->name('job-detail');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('apply-job');
Route::post('/saves-job',[JobsController::class,'savesJob'])->name('saves-job');
Route::get('/forgot-password',[AccountController::class,'forgot_password'])->name('account.forgot-password');
Route::post('/store-forgot-password',[AccountController::class,'store_forgot_password'])->name('account.forgot-password-store');
Route::get('/reset-password/{token}',[AccountController::class,'reset_password'])->name('account.reset-password');
Route::post('/reset-password',[AccountController::class,'reset_password_store'])->name('account.reset-password-store');


Route::group(['prefix' => 'admin'], function () {
  
 
    Route::group(['middleware'=>'admin.check'],function(){
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
       
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users', [UserController::class, 'destroy'])->name('admin.users.delete');
       
        Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
        Route::get('/jobs/edit/{id}', [JobController::class, 'edit'])->name('admin.jobs.edit');
        Route::put('/jobs/update/{id}', [JobController::class, 'update'])->name('admin.jobs.update');
        Route::delete('/jobs/delete', [JobController::class, 'destroy'])->name('admin.jobs.delete');


        Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.job-applications');
        Route::delete('/job-applications', [JobApplicationController::class, 'destroy'])->name('admin.job-applications-delete');
      });
});


Route::group(['prefix' => 'account'], function () {

    // Guest Routes (Only for Unauthenticated Users)
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'register'])->name('register');
        Route::get('/login', [AccountController::class, 'login'])->name('login');
        Route::post('/process-register', [AccountController::class, 'saveUser'])->name('process-register');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('authenticate');
    });

    // Authenticated Routes (Only for Logged-in Users)
    Route::group(['middleware' => 'auth'], function () {
        
        Route::get('/profile',[AccountController::class,'profile'])->name('profile');
        Route::put('/update-profile',[AccountController::class,'updateProfile'])->name('update-profile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('logout');
        Route::post('/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('update-profile-pic');
        Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('update-password');

        Route::get('/create-job', [AccountController::class, 'createJob'])->name('create-job');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('save-job');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('my-jobs');
        Route::get('/show-jobs/{id}', [AccountController::class, 'showJobDetails'])->name('show-job-details');
        Route::get('/edit-jobs/{id}', [AccountController::class, 'jobEdit'])->name('edit-job');
        Route::put('/update-jobs/{id}', [AccountController::class, 'updateJob'])->name('update-job');
        Route::post('/delete-job', [AccountController::class, 'deleteJob'])->name('delete-job');
        Route::get('/my-job-application', [AccountController::class, 'myJobApplication'])->name('my-job-application');
        Route::delete('/remove-job-application', [AccountController::class, 'removeJob'])->name('remove-job-application');
        Route::delete('/delete-saved-job', [AccountController::class, 'deleteSavedJob'])->name('delete-saved-job');
        Route::get('/saved-jobs', [AccountController::class, 'savedJobs'])->name('saved-job');
       
            
    });

});
