<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserPreferencesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;


Route::get('/auth/redirect', function () {

    return Socialite::driver('github')->stateless()->redirect();

});

Route::get('/auth/callback',[AuthController::class,'login']);

Route::post('/auth/token', [AuthController::class, 'exchangeToken']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //private project feed    
    Route::get('/projects/user/', action: [ProjectController::class, 'projects']);

    Route::get('/projects/{id}',[ProjectController::class,'getProject']);

    Route::get('/projects/{projectId}/issues',[IssueController::class,'index']);

    Route::get('/projects/{projectId}/issues/{issueId}',[IssueController::class,'getIssue']);

    Route::post('/user/preferences', [UserPreferencesController::class, 'store']);

    Route::get('/user/preferences', [UserPreferencesController::class, 'show']);
    
    Route::get('/user/onboarding-status', [UserPreferencesController::class, 'onboardingStatus']);

});

// public routes 
   Route::get('/projects', action: [ProjectController::class, 'index']);



