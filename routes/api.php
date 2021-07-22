<?php

use App\Http\Controllers\Admin\Auth\Login as AdminLogin;
use App\Http\Controllers\Authentication\Login;
use App\Http\Controllers\Claims\All;
use App\Http\Controllers\Claims\CreateClaim;
use App\Http\Controllers\Claims\SingleClaimInfo;
use App\Http\Controllers\Policy\UserPolicies;
use App\Http\Controllers\Upload\NewFileUpload;
use App\Http\Controllers\User\Profile;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::post('authentication/login', Login::class)
    ->name('auth.login');
Route::get('profile', Profile::class)
    ->middleware('auth')
    ->name('profile');

Route::group([
    'prefix' => 'claims',
    'middleware' => 'auth'
], function (Router $claims) {
    $claims->post('', CreateClaim::class);
    $claims->get('', All::class);
    $claims->get('{claim}', SingleClaimInfo::class);
});

Route::get('policies', UserPolicies::class)
    ->middleware('auth');

Route::post('uploads', NewFileUpload::class)
    ->middleware('auth');

Route::prefix('admin')
    ->group(function (Router $admin) {
        $admin->post('login', AdminLogin::class);
        $admin->middleware('auth:admin')->group(function (Router $auth) {
            $auth->get('profile', \App\Http\Controllers\Admin\Auth\Profile::class);
        });
    });
