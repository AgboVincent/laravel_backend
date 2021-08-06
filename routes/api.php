<?php

use App\Http\Controllers\Authentication\Login;
use App\Http\Controllers\Claims\All;
use App\Http\Controllers\Claims\CreateClaim;
use App\Http\Controllers\Claims\SingleClaimInfo;
use App\Http\Controllers\Password\Reset;
use App\Http\Controllers\Password\ResetRequest;
use App\Http\Controllers\Policy\UserPolicies;
use App\Http\Controllers\Upload\NewFileUpload;
use App\Http\Controllers\User\Profile;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::prefix('authentication')->group(function (Router $auth) {
    $auth->post('login', Login::class)
        ->name('auth.login');
    $auth->post('password/request', ResetRequest::class);
    $auth->patch('password/request', Reset::class);
});

Route::get('profile', Profile::class)
    ->middleware('auth')
    ->name('profile');

Route::get('accident/types', \App\Http\Controllers\Claims\Accident\TypeList::class);

Route::group([
    'prefix' => 'claims',
    'middleware' => 'auth'
], function (Router $claims) {
    $claims->post('', CreateClaim::class);
    $claims->get('', All::class);
    $claims->get('{claim}', SingleClaimInfo::class);
    $claims->get('{claim}/comments', \App\Http\Controllers\Claims\Comments\ListComments::class);
    $claims->post('{claim}/comments', \App\Http\Controllers\Claims\Comments\AddComment::class);
});

Route::get('policies', UserPolicies::class)
    ->middleware('auth');

Route::post('uploads', NewFileUpload::class)
    ->middleware('auth');

Route::prefix('admin')
    ->group(function (Router $group) {
        $group->middleware('auth:admin')->group(function (Router $admin) {
            $admin->get('claims', \App\Http\Controllers\Admin\Claims\ListClaims::class);
            $admin->post('claims/{claim}/comments', \App\Http\Controllers\Claims\Comments\AddComment::class);

        });
        $group->patch('claims/{claim}', \App\Http\Controllers\Claims\ModifyClaim::class)
            //todo: add middleware to prevent another broker/company from accessing other company's user claim.
            ->middleware('auth:broker');
        $group->patch('claims/{claim}/{claimItem}/approve', \App\Http\Controllers\Claims\Items\ApproveClaimItem::class)
            ->middleware('auth:insurer');
        $group->patch('claims/{claim}/{claimItem}/reject', \App\Http\Controllers\Claims\Items\RejectClaimItem::class)
            ->middleware('auth:insurer');
    });
