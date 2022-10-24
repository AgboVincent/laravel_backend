<?php

use App\Http\Controllers\Authentication\Login;
use App\Http\Controllers\Claims\All;
use App\Http\Controllers\Claims\CreateClaim;
use App\Http\Controllers\Claims\SingleClaimInfo;
use App\Http\Controllers\Notifications\AllNotifications;
use App\Http\Controllers\Notifications\ToggleNotificationReadStatus;
use App\Http\Controllers\Password\Reset;
use App\Http\Controllers\Password\ResetRequest;
use App\Http\Controllers\Policy\UserPolicies;
use App\Http\Controllers\Upload\NewFileUpload;
use App\Http\Controllers\User\Profile;
use App\Http\Controllers\Evaluations\PreEvaluations;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Upload\FileUploadNew;
use App\Http\Controllers\Evaluations\PreEvaluationTypes;
use App\Http\Controllers\Evaluations\GetPolicies;
use App\Http\Controllers\Evaluations\PurchasePolicy;

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

Route::get('banks', \App\Http\Controllers\BankList::class);
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

Route::get('notifications', AllNotifications::class)->middleware('auth');
Route::post('notifications/{notification}', ToggleNotificationReadStatus::class)->middleware('auth');

Route::get('policies', UserPolicies::class)
    ->middleware('auth');

 Route::post('uploads', NewFileUpload::class)
    ->middleware('auth');

Route::post('file/uploads', [PreEvaluationTypes::class, 'store']);

Route::put('result', [PreEvaluationTypes::class,'update']);

Route::get('new/policies', GetPolicies::class);

Route::post('validate/detect', [PreEvaluationTypes::class, 'mlValidate']);

Route::post('policy/select', [PurchasePolicy::class, 'chosePolicy']);

Route::get('user/{email}', [PurchasePolicy::class, 'getUserPolicyStatus']);

Route::get('configurations', \App\Http\Controllers\Company\Configurations\ConfigurationList::class)
    ->middleware('auth');

Route::resource('evals', PreEvaluations::class);
Route::prefix('admin')
    ->group(function (Router $group) {
        $group->post('import/policy_holders', \App\Http\Controllers\Company\Import\PolicyHolderImport::class)
            ->middleware('auth:insurer');
        $group->middleware('auth:admin')->group(function (Router $admin) {
            $admin->get('claims', \App\Http\Controllers\Admin\Claims\ListClaims::class);
            $admin->post('claims/{claim}/comments', \App\Http\Controllers\Claims\Comments\AddComment::class);
            $admin->get('claims/{claim_id}', \App\Http\Controllers\Admin\Claims\SingleClaim::class);
            $admin->get('overview', \App\Http\Controllers\Admin\Dashboard\Overview::class);
            $admin->get('customers', \App\Http\Controllers\Admin\CustomerList::class);
            $admin->get('policies', \App\Http\Controllers\Admin\Policies\AllPolicies::class);
            $admin->post('claims/{claim}/items', \App\Http\Controllers\Admin\Claims\AddItems::class);
            $admin->post('claims/{claim}/responsibility', \App\Http\Controllers\Admin\Claims\UpdateClientResponsibility::class);
            $admin->get('claims/responsibilities/all', \App\Http\Controllers\Admin\Claims\GetClientResponsibilities::class);
            $admin->post('claims/{claim}/expert-requirement', \App\Http\Controllers\Admin\Claims\UpdateExpertRequirement::class);
            $admin->get('experts', \App\Http\Controllers\Admin\Experts\GetExperts::class);
            $admin->post('claims/{claim}/experts/{expert}', \App\Http\Controllers\Admin\Claims\AssignExpert::class);
            $admin->post('claims/{claim}/experts/{expert}/report', \App\Http\Controllers\Admin\Claims\UploadExpertReport::class);

            $admin->get('claims/policy/guarantee-types', \App\Http\Controllers\Admin\Policies\ListGuaranteeTypes::class);
            $admin->post('policies/{policy}/guarantees/', \App\Http\Controllers\Admin\Policies\SaveGuarantees::class);
            $admin->get('claims/{claim}/experts', \App\Http\Controllers\Admin\Claims\GetClaimExperts::class);

            $admin->post('claims/{claim}/financial-movements', \App\Actions\Claims\SaveFinancialMovement::class);
            $admin->get('claims/{claim}/financial-movements', \App\Actions\Claims\ListFinancialMovements::class);

            $admin->get('garages', \App\Actions\Garages\ListGarages::class);
            $admin->get('claims/{claim}/garage', \App\Actions\Claims\GetGarage::class);
            $admin->post('claims/{claim}/garage', \App\Actions\Claims\SetGarage::class);

            $admin->get('policies/{policy}/insurer', \App\Actions\Policies\GetInsurer::class);
            $admin->resource('evals', PreEvaluations::class);
            $admin->get('inspection/{id}', [PreEvaluationTypes::class,'getFiles']);
            $admin->get('purchase/policy', [PurchasePolicy::class, 'getPurchasePolicies']);
            $admin->put('status/update', [PurchasePolicy::class, 'updateUserPolicyStatus']);

        });
        $group->post('policies/{policy}/claims', \App\Http\Controllers\Company\Claims\CreateNewClaim::class)
            ->middleware('auth:insurer');
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
        $group->post('claims/{claim}', \App\Http\Controllers\Admin\Claims\ProcessPayment::class)
            ->middleware('auth:insurer');
        $group->post('claims/{claim}/offline', \App\Http\Controllers\Admin\Claims\MarkAsPaid::class)
            ->middleware('auth:insurer');
    });

Route::prefix('/integrations')
    ->group(function (Router $integrations) {
        $integrations->prefix('/baloon')
            ->group(function (Router $baloon) {
                $baloon->post('/create-claim', \App\Actions\Integrations\Baloon\GetClaimsURL::class);
                $baloon->post('/list-claims', \App\Actions\Integrations\Baloon\GetClaimsListURL::class);
            });
    });

Route::prefix('brands')->group(function(Router $router){
    $router->get('/', \App\Actions\Vehicle\GetBrand::class);
   $router->get('{brand}/models', \App\Actions\Vehicle\GetModel::class);
});    