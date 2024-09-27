<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Setting\AuthenticationController as auth;
use App\Http\Controllers\Backend\Setting\UserController as user;
use App\Http\Controllers\Backend\Setting\DashboardController as dashboard;
use App\Http\Controllers\Backend\Setting\RoleController as role;
use App\Http\Controllers\Backend\Setting\PermissionController as permission;
use App\Http\Controllers\Backend\Students\StudentController as student;
use App\Http\Controllers\Backend\Instructors\InstructorController as instructor;
use App\Http\Controllers\Backend\Courses\CourseCategoryController as courseCategory;
use App\Http\Controllers\Backend\Courses\CourseController as course;
use App\Http\Controllers\Backend\Courses\MaterialController as material;
use App\Http\Controllers\Backend\Quizzes\QuizController as quiz;
use App\Http\Controllers\Backend\Quizzes\QuestionController as question;
use App\Http\Controllers\Backend\Quizzes\OptionController as option;
use App\Http\Controllers\Backend\Quizzes\AnswerController as answer;
use App\Http\Controllers\Backend\Reviews\ReviewController as review;
use App\Http\Controllers\Backend\Communication\DiscussionController as discussion;
use App\Http\Controllers\Backend\Communication\MessageController as message;
use App\Http\Controllers\MailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchCourseController;
use App\Http\Controllers\CheckoutController as checkout;
use App\Http\Controllers\CouponController as coupon;
use App\Http\Controllers\WatchCourseController as watchCourse;
use App\Http\Controllers\LessonController as lesson;
use App\Http\Controllers\EnrollmentController as enrollment;
use App\Http\Controllers\EventController as event;
use App\Http\Controllers\CustomForgotPasswordController;

/* students */
use App\Http\Controllers\Students\AuthController as sauth;
use App\Http\Controllers\Students\DashboardController as studashboard;
use App\Http\Controllers\Students\ProfileController as stu_profile;
use App\Http\Controllers\Students\sslController as sslcz;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//------Reset Password Route
Route::get('/forgot-password', function () {
    return view('students.auth.forgot-password-instructor');
})->middleware('guest')->name('password.request');
Route::post('/forgot-password', [CustomForgotPasswordController::class, 'forgotPassword'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}/{email}', function ($token,$email) {
    return view('students.auth.reset-password-instructor', ['token' => $token , 'email' => $email]);
})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [CustomForgotPasswordController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

//===========User/Instructor Verify email address routes================================
Route::get('user/email-verify', [MailController::class, 'emailVerifyInstructor'])
->name('email-verify-instructor');
Route::get('user/email-verify-done/{token}', [MailController::class, 'emailVerifyDoneInstructor'])
->name('email-verify-done-instructor');
Route::get('user/resend-verification-email', [MailController::class, 'resendEmailVerificationInstructor'])
->name('resend-verification-email.instructor');
Route::post('user/resend-verification', [MailController::class, 'resendVerificationInstructor'])
->name('resend-verification-instructor');
Route::post('user/email-not-verify', [MailController::class, 'emailNotVerifyInstructor'])
->name('email-not-verify-instructor');
    
//----Student reset password routes----------------
Route::get('user/forgot-password', function () {
    return view('students.auth.forgot-password');
})->middleware('guest')->name('user.password.request');
Route::post('user/forgot-password', [CustomForgotPasswordController::class, 'userForgotPassword'])
    ->middleware('guest')
    ->name('user.password.email');
Route::get('user/reset-password/{token}/{email}', function ($token,$email) {
    return view('students.auth.reset-password', ['token' => $token , 'email' => $email]);
})->middleware('guest')->name('user.password.reset');
Route::post('user/reset-password', [CustomForgotPasswordController::class, 'userResetPassword'])
    ->middleware('guest')
    ->name('user.password.update');

//===========Student Verify email address routes================================
Route::get('email-verify', [MailController::class, 'emailVerify'])
->name('email-verify');
Route::get('email-verify-done/{token}', [MailController::class, 'emailVerifyDone'])
->name('email-verify-done');
Route::get('resend-verification-email', [MailController::class, 'resendEmailVerification'])
->name('resend-verification-email');
Route::post('resend-verification', [MailController::class, 'resendVerification'])
->name('resend-verification');
Route::post('email-not-verify', [MailController::class, 'emailNotVerify'])
->name('email-not-verify');

//-----------------------
Route::get('/signup', [auth::class, 'signUpForm'])->name('register');
Route::post('/signup', [auth::class, 'signUpStore'])->name('register.store');
Route::get('/login', [auth::class, 'signInForm'])->name('login');
Route::post('/login', [auth::class, 'signInCheck'])->name('login.check');
Route::get('/logout', [auth::class, 'signOut'])->name('logOut');


Route::middleware(['checkauth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [dashboard::class, 'index'])->name('dashboard');
    Route::get('userProfile', [auth::class, 'show'])->name('userProfile');
});

Route::prefix('admin')->group(function () {
    Route::resource('user', user::class); 
    Route::resource('role', role::class);
    Route::resource('student', student::class);
    Route::resource('instructor', instructor::class);
    Route::resource('courseCategory', courseCategory::class);
    Route::resource('course', course::class);
    Route::get('/courseList', [course::class, 'indexForAdmin'])->name('courseList');
    Route::patch('/courseList/{update}', [course::class, 'updateforAdmin'])->name('course.updateforAdmin');
    Route::resource('material', material::class);
    Route::resource('lesson', lesson::class);
    Route::resource('event', event::class);
    Route::resource('quiz', quiz::class);
    Route::resource('question', question::class);
    Route::resource('option', option::class);
    Route::resource('answer', answer::class);
    Route::resource('review', review::class); 
    Route::resource('discussion', discussion::class);
    Route::resource('message', message::class);
    Route::resource('coupon', coupon::class);
    Route::resource('enrollment', enrollment::class);
    Route::get('permission/{role}', [permission::class, 'index'])->name('permission.list');
    Route::post('permission/{role}', [permission::class, 'save'])->name('permission.save');
});

Route::get('/register', [HomeController::class, 'signUpForm'])->name('signup');

/* students controllers */
Route::get('/student/register', [sauth::class, 'signUpForm'])->name('studentRegister');
Route::post('/student/register/{back_route}', [sauth::class, 'signUpStore'])->name('studentRegister.store');

Route::get('/user/login', [sauth::class, 'signInForm'])->name('studentLogin');
Route::post('/student/login/{back_route}', [sauth::class, 'signInCheck'])->name('studentLogin.check');
Route::get('/user/logout', [sauth::class, 'signOut'])->name('studentlogOut');

Route::middleware(['checkstudent'])->prefix('students')->group(function () {
    Route::get('student/dashboard', [studashboard::class, 'index'])->name('studentdashboard');
    Route::get('/profile', [stu_profile::class, 'index'])->name('student_profile');
    Route::post('/profile/save', [stu_profile::class, 'save_profile'])->name('student_save_profile');
    Route::post('/profile/savePass', [stu_profile::class, 'change_password'])->name('change_password');
    Route::post('/change-image', [stu_profile::class, 'changeImage'])->name('change_image');

    // ssl Routes
    Route::post('/payment/ssl/submit', [sslcz::class, 'store'])->name('payment.ssl.submit');
});

//----------instructor routes --------------------------------
Route::get('/instructor/register', [sauth::class, 'instructorSignUpForm'])->name('instructorRegister');
Route::post('/instructor/register/{back_route}', [sauth::class, 'instructorSignUpStore'])->name('instructorRegister.store');
// Route::get('/instructor/login', [sauth::class, 'instructorSignInForm'])->name('instructorLogin');
//Route::post('/instructor/login/{back_route}', [sauth::class, 'instructorSignInCheck'])->name('instructorLogin.check');
// Route::get('/instructor/logout', [sauth::class, 'signOut'])->name('studentlogOut');
 
// frontend pages
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('searchCourse', [SearchCourseController::class, 'index'])->name('searchCourse'); 
Route::get('courseDetails/{id}', [course::class, 'frontShow'])->name('courseDetails');
Route::get('watchCourse/{id}', [watchCourse::class, 'watchCourse'])->name('watchCourse');
Route::get('instructorProfile/{id}', [instructor::class, 'frontShow'])->name('instructorProfile');
Route::get('checkout', [checkout::class, 'index'])->name('checkout');
Route::post('checkout', [checkout::class, 'store'])->name('checkout.store');

// Cart
Route::get('/cart_page', [CartController::class, 'index']);
Route::get('cart', [CartController::class, 'cart'])->name('cart');
Route::get('add-to-cart/{id}', [CartController::class, 'addToCart'])->name('add.to.cart');
Route::patch('update-cart', [CartController::class, 'update'])->name('update.cart');
Route::delete('remove-from-cart', [CartController::class, 'remove'])->name('remove.from.cart');

// Coupon
Route::post('coupon_check', [CartController::class, 'coupon_check'])->name('coupon_check');

/* ssl payment */
Route::post('/payment/ssl/notify', [sslcz::class, 'notify'])->name('payment.ssl.notify');
Route::post('/payment/ssl/cancel', [sslcz::class, 'cancel'])->name('payment.ssl.cancel');

Route::get('send-mail', [MailController::class, 'index'])
    ->name('send-mail');

Route::get('user/send-mail', [MailController::class, 'indexInstructor'])
    ->name('send-mail-instructor'); 

Route::get('/about', function () {
    return view('frontend.about');
})->name('about');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');
