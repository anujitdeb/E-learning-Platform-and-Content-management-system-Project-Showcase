<?php

use App\Http\Controllers\Authentication\AdminAuthController;
use App\Http\Controllers\Authorization\RoleManageController;
use App\Http\Controllers\v1\Backend\Admin\AdminController;
use App\Http\Controllers\v1\Backend\Apps\BannerController;
use App\Http\Controllers\v1\Backend\Apps\ContactController;
use App\Http\Controllers\v1\Backend\Apps\WelcomeScreenController;
use App\Http\Controllers\v1\Backend\ContentManagement\AboutController;
use App\Http\Controllers\v1\Backend\ContentManagement\Achievement\AchievementController;
use App\Http\Controllers\v1\Backend\ContentManagement\Achievement\InitiativeController;
use App\Http\Controllers\v1\Backend\ContentManagement\ConditionPageController;
use App\Http\Controllers\v1\Backend\ContentManagement\FacilityController;
use App\Http\Controllers\v1\Backend\ContentManagement\FAQController;
use App\Http\Controllers\v1\Backend\ContentManagement\HomePageController;
use App\Http\Controllers\v1\Backend\Course\CourseCategoryController;
use App\Http\Controllers\v1\Backend\Course\CourseContentController;
use App\Http\Controllers\v1\Backend\Course\CourseController;
use App\Http\Controllers\v1\Backend\Course\CourseFacilityController;
use App\Http\Controllers\v1\Backend\Course\CourseCurriculumController;
use App\Http\Controllers\v1\Backend\Course\CourseMediaController;
use App\Http\Controllers\v1\Backend\Course\CourseSoftwareController;
use App\Http\Controllers\v1\Backend\Course\SoftwareController;
use App\Http\Controllers\v1\Backend\Mentor\MentorController;
use App\Http\Controllers\v1\Backend\Course\MarketplaceController;
use App\Http\Controllers\v1\Backend\Feedback\FeedbackController;
use App\Http\Controllers\v1\Backend\Mentor\MarketplaceMentorController;
use App\Http\Controllers\v1\Backend\Mentor\MentorExperienceController;
use App\Http\Controllers\v1\Backend\Promotion\HeaderCountdownController;
use App\Http\Controllers\v1\Backend\Promotion\OfferNoticeController;
use App\Http\Controllers\v1\Backend\Promotion\PromotionBannerController;
use App\Http\Controllers\v1\Backend\Seminar\SeminarController;
use App\Http\Controllers\v1\Backend\Seminar\SeminarDetailController;
use App\Http\Controllers\v1\Backend\Seminar\SeminarDetailContentController;
use App\Http\Controllers\v1\Backend\SuccessStory\SuccessCategoryController;
use App\Http\Controllers\v1\Backend\SuccessStory\SuccessStoryController;
use App\Http\Controllers\v1\Backend\TermsCondition\TermsConditionController;
use App\Http\Controllers\v1\Settings\LanguageController;
use App\Http\Controllers\v1\Settings\LocationController;
use App\Http\Controllers\v1\Settings\LogoController;
use Illuminate\Support\Facades\Route;

Route::get('/app/login-process', [AdminAuthController::class, 'loginProcess']);
Route::post('/app/login-sso', [AdminAuthController::class, 'loginWithSSO']);
Route::post('/app/login', [AdminAuthController::class, 'login']);

Route::middleware(['auth:admin-api', 'admin', 'scope:admin'])->prefix('app/')->group(function () {
   Route::get('check', [AdminAuthController::class, 'check']);
   Route::get('user/permission', [AdminAuthController::class, 'permissions']);
   Route::post('logout', [AdminAuthController::class, 'logout']);

   // Language
   Route::apiResource('language', LanguageController::class)->middleware('permission:language');
   Route::get('languages/active', [LanguageController::class, 'activeLanguages']);

   // Course Category here
   Route::apiResource('course/category', CourseCategoryController::class)->middleware('permission:course.category');
   Route::post('update/higher/key', [CourseCategoryController::class, 'updateHigherKey'])->middleware('permission:course.category');
   Route::get('courses/category/active', [CourseCategoryController::class, 'activeCourseCategories']);

   // About
   Route::apiResource('about', AboutController::class)->middleware('permission:about');

   // Course
   Route::apiResource('course', CourseController::class)->middleware('permission:course');
   Route::patch('course/status-update/{course}', [CourseController::class, 'statusUpdate'])->middleware('permission:course');
   Route::get('courses/active', [CourseController::class, 'activeCourses'])->middleware('permission:course');

   // Course Content
   Route::get('course-content/{course}', [CourseContentController::class, 'show'])->middleware('permission:course');
   Route::put('course-content/{course}', [CourseContentController::class, 'update'])->middleware('permission:course.content');

   Route::get('contact', [ContactController::class, 'index'])->middleware('permission:contact');
   Route::put('contact', [ContactController::class, 'update'])->middleware('permission:contact');

   //Mentor
   Route::apiResource('mentor', MentorController::class)->middleware('permission:mentor');

   Route::apiResource('offer/notice', OfferNoticeController::class)->middleware('permission:offer.notice');

   // Seminar
   Route::apiResource('seminars', SeminarController::class)->middleware('permission:seminar');

   // Seminar Detail
   Route::prefix('seminar/')->group(function () {
      Route::apiResource('detail', SeminarDetailController::class)->middleware('permission:seminar.detail');
      Route::get('active/details/{course_id?}', [SeminarDetailController::class, 'activeSeminarDetail'])->middleware('permission:seminar.detail');
      Route::get('detail/{detail}/content', [SeminarDetailContentController::class, 'show'])->middleware('permission:seminar.detail');
      Route::put('detail/{detail}/content', [SeminarDetailContentController::class, 'update'])->middleware('permission:seminar.detail');
   });

   //Role management
   Route::apiResource('/role', RoleManageController::class)->middleware('permission:role');
   Route::get('/all-permissions', [RoleManageController::class, 'groupWisePermissions']);
   Route::get('roles/active', [RoleManageController::class, 'activeRoles']);

   //Admin
   Route::apiResource('admin', AdminController::class)->middleware('permission:admin');
});
