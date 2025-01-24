<?php

use App\Http\Controllers\Authentication\SocialiteController;
use App\Http\Controllers\Authentication\StudentAuthController;
use App\Http\Controllers\Authentication\StudentRegistrationController;
use App\Http\Controllers\v1\Frontend\Certificate\CertificateController;
use App\Http\Controllers\v1\Frontend\ContentManagement\AboutController;
use App\Http\Controllers\v1\Frontend\ContentManagement\Achievement\AchievementController;
use App\Http\Controllers\v1\Frontend\ContentManagement\Achievement\InitiativeController;
use App\Http\Controllers\v1\Frontend\ContentManagement\FacilityController;
use App\Http\Controllers\v1\Frontend\ContentManagement\FAQController;
use App\Http\Controllers\v1\Frontend\Course\CourseCategoryController;
use App\Http\Controllers\v1\Frontend\Home\BannerController;
use App\Http\Controllers\v1\Frontend\Promotion\HeaderCountdownController;
use App\Http\Controllers\v1\Frontend\Promotion\OfferNoticeController;
use App\Http\Controllers\v1\Frontend\Seminar\SeminarController;
use App\Http\Controllers\v1\Frontend\Settings\LanguageController;
use App\Http\Controllers\v1\Frontend\Settings\LogoController;
use App\Http\Controllers\v1\Frontend\WelcomeScreenController;
use App\Http\Controllers\v1\Frontend\Course\CourseController;
use App\Http\Controllers\v1\Frontend\ContactController;
use App\Http\Controllers\v1\Frontend\FeedbackController;
use App\Http\Controllers\v1\Frontend\LeadController;
use App\Http\Controllers\v1\Frontend\Home\HomeController;
use App\Http\Controllers\v1\Frontend\Mentor\MentorController;
use App\Http\Controllers\v1\Frontend\Promotion\PromotionBannerController;
use App\Http\Controllers\v1\Frontend\Settings\LocationController;
use App\Http\Controllers\v1\Frontend\ProfileController;
use App\Http\Controllers\v1\Frontend\Student\AdmissionController;
use App\Http\Controllers\v1\Frontend\Student\AttendanceController;
use App\Http\Controllers\v1\Frontend\Student\ClassModuleController;
use App\Http\Controllers\v1\Frontend\Student\ExamController;
use App\Http\Controllers\v1\Frontend\Student\PreAssessmentController;
use App\Http\Controllers\v1\Frontend\Student\StudentController;
use App\Http\Controllers\v1\Frontend\Student\StudentNoticeController;
use App\Http\Controllers\v1\Frontend\SuccessStory\SuccessStoryCategoryController;
use App\Http\Controllers\v1\Frontend\SuccessStory\SuccessStoryController;
use App\Http\Controllers\v1\Frontend\TermsConditionController;
use Illuminate\Support\Facades\Route;

//Student Authentication
Route::post('/registration', [StudentRegistrationController::class, 'registration']);
Route::post('/otp-verification', [StudentRegistrationController::class, 'verifyOTP']);
Route::post('/otp-resend', [StudentRegistrationController::class, 'resendOTP']);
Route::post('/login', [StudentAuthController::class, 'login']);
Route::get('/auth/google', [SocialiteController::class, 'googleLogin']);
Route::post('/google-auth', [SocialiteController::class, 'googleAuthentication']);

Route::get('contact', [ContactController::class, 'index']);
Route::get('/check', [StudentAuthController::class, 'check']);
Route::middleware(['auth:api', 'user', 'scope:user'])->group(function () {
    Route::post('/set-password', [StudentRegistrationController::class, 'setPassword']);
    Route::post('/logout', [StudentAuthController::class, 'logout']);
    // Language
    Route::get('languages', [LanguageController::class, 'index']);


    Route::get('course-categories', [CourseCategoryController::class, 'index']);

    Route::get('courses', [CourseController::class, 'index']);
    Route::get('department/courses', [CourseController::class, 'departmentsWithCourses']);
    Route::prefix('course/{course}/')->group(function () {
        Route::get('/', [CourseController::class, 'show']);
        Route::get('curriculums', [CourseController::class, 'getCurriculums']);
        Route::get('success/stories', [CourseController::class, 'getSuccessStories']);
        Route::get('facilities', [CourseController::class, 'getFacilities']);
        Route::get('softwares', [CourseController::class, 'getSoftwares']);
    });

    Route::get('mentors/{department}', [MentorController::class, 'index']);
    Route::prefix('mentor/{mentor}/')->group(function () {
        Route::get('/', [MentorController::class, 'show']);
        Route::get('experiences', [MentorController::class, 'getExperiences']);
        Route::get('marketplaces', [MentorController::class, 'getMarketplaces']);
    });
    Route::put('profile', [ProfileController::class, 'update']);
    Route::get('profile', [ProfileController::class, 'index']);

    Route::get('/get/booked/seminars', [SeminarController::class, 'getBookedSeminars']);
    Route::post('/book/seminar/{seminar}', [SeminarController::class, 'bookSeminar']);

    Route::prefix('student/')->group(function () {
        Route::get('/get/admission/batches', [AdmissionController::class, 'getBatches']);
        Route::get('/class/report/{admission_id?}', [StudentController::class, 'studentClassReport']);
        Route::get('/get/upcoming/classes', [ClassModuleController::class, 'upcomingClasses']);
        Route::get('/get/attendance', [AttendanceController::class, 'getAttendance']);
        Route::get('/get/modules', [ClassModuleController::class, 'getModules']);
        Route::get('/get/exams', [ExamController::class, 'getExams']);
        Route::get('/get/facebook/groups', [StudentController::class, 'getFacebookGroups']);
        Route::get('/get/student', [StudentController::class, 'getStudent']);
        Route::get('/profile', [StudentController::class, 'profile']);
        Route::put('/profile/update', [StudentController::class, 'update']);
        Route::get('/get/education/degree', [StudentController::class, 'getEduDegree']);
        Route::get('/get/education/board', [StudentController::class, 'getEduBoard']);
        Route::get('/get/division', [StudentController::class, 'getDivision']);
        Route::get('/get/district/{division_id?}', [StudentController::class, 'getDistrict']);
        Route::get('/get/upazila/{district_id?}', [StudentController::class, 'getUpazila']);
        Route::put('/profile/image/upload', [StudentController::class, 'uploadImage']);
        Route::get('/get/notices', [StudentNoticeController::class, 'getNotices']);
        Route::get('/is/unread/notice', [StudentNoticeController::class, 'isUnreadNotice']);
        Route::put('/read/notice/{notice_id}', [StudentNoticeController::class, 'readNotice']);
    });

    //Seminar
    Route::get('seminars', [SeminarController::class, 'index']);
    Route::get('seminar/{seminar}', [SeminarController::class, 'show']);

    //Offer Notice
    Route::get('get/offer-notices', [OfferNoticeController::class, 'getOfferNotices']);
    Route::post('store/device-token', [OfferNoticeController::class, 'storeDeviceToken']);

    //Certificate
    Route::post('certificate', [CertificateController::class, 'getCertificate']);
});
