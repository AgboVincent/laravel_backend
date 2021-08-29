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

Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function (Router $profile) {
    $profile->get('/', Profile::class)
        ->name('profile');
    $profile->patch('/', \App\Http\Controllers\Profile\Update::class);
    $profile->post('/bank', \App\Http\Controllers\Profile\CreateOrUpdateBankAccount::class);
    $profile->patch('password', \App\Http\Controllers\Profile\PasswordUpdate::class);
});

Route::get('accident/types', \App\Http\Controllers\Claims\Accident\TypeList::class);
Route::get('claims/items/types', \App\Http\Controllers\Claims\Items\ListTypes::class);

Route::group([
    'prefix' => 'claims',
    'middleware' => 'auth'
], function (Router $claims) {
    $claims->post('', CreateClaim::class);
    $claims->get('', All::class);
    $claims->get('{claim}', SingleClaimInfo::class);
    $claims->get('{claim}/comments', \App\Http\Controllers\Claims\Comments\ListComments::class);
    $claims->post('{claim}/comments', \App\Http\Controllers\Claims\Comments\AddComment::class);
    $claims->post('{claim}', \App\Http\Controllers\Claims\Settlement\Approval::class);
});

Route::get('policies', UserPolicies::class)
    ->middleware('auth');

Route::post('uploads', NewFileUpload::class)
    ->middleware('auth');

Route::get('configurations', \App\Http\Controllers\Company\Configurations\ConfigurationList::class)
    ->middleware('auth');

Route::prefix('admin')
    ->group(function (Router $group) {
        $group->middleware('auth:admin')->group(function (Router $admin) {
            $admin->get('claims', \App\Http\Controllers\Admin\Claims\ListClaims::class);
            $admin->post('claims/{claim}/comments', \App\Http\Controllers\Claims\Comments\AddComment::class);
            $admin->get('claims/{claim}', \App\Http\Controllers\Admin\Claims\SingleClaim::class);
            $admin->get('overview', \App\Http\Controllers\Admin\Dashboard\Overview::class);
            $admin->get('customers', \App\Http\Controllers\Admin\CustomerList::class);
            $admin->get('policies', \App\Http\Controllers\Admin\Policies\AllPolicies::class);
        });
        $group->patch('claims/{claim}', \App\Http\Controllers\Claims\ModifyClaim::class)
            //todo: add middleware to prevent another broker/company from accessing other company's user claim.
            ->middleware('auth:broker');
        $group->post('claims/{claim}', \App\Http\Controllers\Admin\Claims\InvolveInsurer::class)
            ->middleware('auth:broker');
        $group->post('configurations/{configuration}', \App\Http\Controllers\Company\Configurations\UpdateConfiguration::class)
            ->middleware('auth:insurer');
        $group->group(['prefix' => 'claims/{claim}/{claimItem}', 'middleware' => 'auth:insurer'], function (Router $item) {
            $item->patch('approve', \App\Http\Controllers\Claims\Items\ApproveClaimItem::class);
            $item->patch('reject', \App\Http\Controllers\Claims\Items\RejectClaimItem::class);
            $item->patch('update', \App\Http\Controllers\Claims\Items\UpdateClaimItem::class);
        });
    });
