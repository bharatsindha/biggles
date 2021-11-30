<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */

Route::group(['middleware' => ['auth', 'acl']], function () {
    Route::resource('move', 'MoveController');
    Route::resource('deal', 'DealController');
    Route::resource('customer', 'CustomerController');
    Route::get('deal/create/{move_id}', [
        'as' => 'move.deal.create',
        'uses' => 'DealController@create',
    ]);
    Route::get('job-details/{move}', [
        'as' => 'move.job_details',
        'uses' => 'MoveController@showJobDetails',
    ]);
    Route::patch('move/decline/{move}', [
        'as' => 'move.decline',
        'uses' => 'MoveController@declineJob',
    ]);

    Route::post('move/accept-job-html/{move}', [
        'as' => 'move.accept_job_html',
        'uses' => 'MoveController@acceptJobHtml',
    ]);

    Route::post('move/accept-job-scheduler-html/{move}', [
        'as' => 'move.accept_job_scheduler_html',
        'uses' => 'MoveController@acceptJobSchedulerHtml',
    ]);

    Route::post('move/decline-job-html/{move}', [
        'as' => 'move.decline_job_html',
        'uses' => 'MoveController@declineJobHtml',
    ]);

    Route::patch('move/accept-job/{move}', [
        'as' => 'move.accept_job',
        'uses' => 'MoveController@acceptJob',
    ]);

    Route::post('move/accept-job/{move}', [
        'as' => 'move.schedule_job',
        'uses' => 'MoveController@acceptJob',
    ]);

    Route::resource('schedule-job', 'ScheduleJobController');

    Route::get('move/create-stripe-customer/{customer}', [
        'as' => 'move.create-stripe-customer',
        'uses' => 'MoveController@createStripeUser',
    ]);

    # Route to return truck resources
    Route::get('truck-resources', [
        'as' => 'move.truck_resources',
        'uses' => 'ScheduleJobController@getTruckResources',
    ]);

    # Route to return truck resources
    Route::get('scheduled-jobs', [
        'as' => 'move.scheduled_jobs',
        'uses' => 'ScheduleJobController@getScheduledJobs',
    ]);

    # Route to return html of scheduled job
    Route::post('get-html-scheduled-job/{move}', [
        'as' => 'move.get_html_scheduled_job',
        'uses' => 'ScheduleJobController@getDragJobHtml',
    ]);

    # Route to return truck resources
    Route::get('filter-jobs/{jobName?}', [
        'as' => 'move.filter_jobs',
        'uses' => 'ScheduleJobController@filterJobs',
    ]);

    # Route to return Payment Details for the Move
    Route::get('get-payment-details', [
        'as' => 'move.payment_details',
        'uses' => 'MoveController@getPaymentDetails',
    ]);

    # Route to update job with complete
    Route::get('move/completed/{move}', [
        'as' => 'move.completed',
        'uses' => 'MoveController@completedJob',
    ]);

    # Route to update job with complete
    Route::get('move/checklist-update/{move}', [
        'as' => 'move.update_checklist',
        'uses' => 'MoveController@updateMoveChecklist',
    ]);
});

Route::get('customer-muval-access/{moveId}', [
    'as' => 'customer_access.index',
    'uses' => 'CustomerReqController@index',
]);

Route::get('verify-customer/{moveId}/{email}', [
    'as' => 'customer_verification.verify',
    'uses' => 'CustomerReqController@verify',
]);
