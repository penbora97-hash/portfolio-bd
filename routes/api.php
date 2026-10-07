<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\LearningController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectImageController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (សម្រាប់អ្នកទស្សនាទូទៅមើល Portfolio)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::get('/profile', [ProfileController::class, 'show']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::post('/contact', [ContactMessageController::class, 'store'])
    ->middleware('throttle:5,1');

// Resource សម្រាប់អានទិន្នន័យសាធារណៈ (Read-only)
$publicResources = [
    'skills'       => SkillController::class,
    'experiences'  => ExperienceController::class,
    'education'    => EducationController::class,
    'social-links' => SocialLinkController::class,
    'testimonials' => TestimonialController::class,
     'certificates' => CertificateController::class, 
    'learnings'    => LearningController::class,    
];

foreach ($publicResources as $uri => $controller) {
    Route::get($uri, [$controller, 'index']);
}
Route::get('/certificates', [CertificateController::class, 'index']);
Route::get('/certificates/{certificate}', [CertificateController::class, 'show']);


/*
|--------------------------------------------------------------------------
| Admin Routes (ត្រូវការ Login ដោយ Sanctum Token)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () use ($publicResources) {
    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile Update
    Route::post('/profile', [ProfileController::class, 'update']);

    // Projects (CRUD ពេញលេញ លើកលែង index/show ព្រោះវានៅ Public រួចហើយ)
    Route::apiResource('projects', ProjectController::class)
        ->except(['index', 'show']);

    // Project Gallery Images
    Route::post('/projects/{project}/images', [ProjectImageController::class, 'store']);
    Route::delete('/project-images/{image}', [ProjectImageController::class, 'destroy']);

    // Skills, Experiences, Education, Social Links, Testimonials (CRUD ពេញលេញ)
    foreach ($publicResources as $uri => $controller) {
        Route::apiResource($uri, $controller)->except(['index']);
    }
    Route::apiResource('certificates', CertificateController::class)
        ->except(['index', 'show']);

    // Contact Messages
    Route::get('/contact-messages', [ContactMessageController::class, 'index']);
    Route::patch('/contact-messages/{contactMessage}/read', [ContactMessageController::class, 'markRead']);
    Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy']);
});


