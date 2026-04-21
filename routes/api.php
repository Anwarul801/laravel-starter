<?php


use App\Http\Controllers\Api\Frontend\OrderController;
use App\Http\Controllers\Api\Frontend\PaymentController;
use App\Http\Controllers\BookOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Frontend\HomeController;
use App\Http\Controllers\Api\Student\AuthController;
use App\Http\Controllers\Api\Student\CommonController;
use App\Http\Controllers\Api\Student\PdfStreamController;
use App\Http\Controllers\Api\Student\ProfileController;
use App\Http\Controllers\LessonController;
use App\Http\Middleware\BlockDownloadManagers;
use App\Http\Middleware\StudentAuth;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['as' => 'auth.'], function () {
    Route::post('send-otp', [AuthController::class, 'sendOtp'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('send-reg-otp', [AuthController::class, 'sendOtpReg'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('forget-password', [AuthController::class, 'forgetPassword'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('register', [AuthController::class, 'register'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('landing-register', [AuthController::class, 'landingRegister'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('checkPhone/{phone}', [AuthController::class, 'checkPhone']);
    Route::post('checkAccount', [AuthController::class, 'checkAccount'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
});

Route::prefix('v2')->middleware([StudentAuth::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'getProfile']);
    Route::post('/profile', [ProfileController::class, 'updateProfile'])
        ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);;
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])
        ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);
    Route::post('/become-affiliate', [HomeController::class, 'becomeAffiliate'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);

    Route::prefix('course')->group(function () {
        Route::post('/comments', [CommonController::class, 'courseComments'])
            ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);;
        Route::get('/comments', [CommonController::class, 'courseCommentList']);
    });

    Route::get('/dashboard', [CommonController::class, 'dashboardInfo']);
    Route::get('/get-reports', [CommonController::class, 'getReports']);
    Route::get('/mycourse', [CommonController::class, 'mycourse']);
    Route::get('/course/{slug}', [CommonController::class, 'courseDetails']);
    Route::get('/course-quiz/{slug}/{lessionId}', [CommonController::class, 'courseQuiz']);
    // Route::post('/lesson/document', [CommonController::class, 'viewLessonDocument'])->withoutMiddleware([
    //     EnsureFrontendRequestsAreStateful::class,
    // ]);
    Route::get('/ebook', [CommonController::class, 'ebook']);
    Route::get('/ebook/{slug}', [CommonController::class, 'ebookDetails']);
    Route::get('/payment-history', [CommonController::class, 'payment_history']);
    Route::get('/order-history', [CommonController::class, 'order_history']);
    Route::get('/devices', [CommonController::class, 'devices']);
    Route::delete('/devices/{id}', [CommonController::class, 'removeDevice'])
        ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);
    Route::post('/submitQuizOrWatchLesson', [HomeController::class, 'submitQuizOrWatchLesson'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::post('/coupon-applied', [OrderController::class, 'couponApplied'])
        ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);
    Route::post('/reviews', [HomeController::class, 'reviewsAdd'])
        ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);
    Route::get('/enroll/{id}', [CommonController::class, 'enrollInfo']);

    Route::post('/withdraw/request', [HomeController::class, 'requestWithdraw']) ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);
    Route::get('/withdraw/list', [HomeController::class, 'listWithdraws']);
});

Route::post('/lesson/pdf-secure', [PdfStreamController::class, 'streamLessonPdf'])
    ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class]);


Route::group(['as' => 'frontend'], function () {
    Route::get('/sitesetting', [HomeController::class, 'sitesetting']);
    Route::get('/importantlink', [HomeController::class, 'importantlink']);
    Route::get('/sliders', [HomeController::class, 'sliders']);
    Route::get('/all-courses', [HomeController::class, 'getAllCourses']);
    Route::get('/affilate-courses', [HomeController::class, 'getAffilateCourses']);
    Route::get('/getAllCourseCategory', [HomeController::class, 'getAllCourseCategory']);
    Route::get('/filter-courses', [HomeController::class, 'getfilterCourses']);
    Route::get('/courses/{slug}', [HomeController::class, 'getCourseDetails']);
    Route::get('/book/{slug}', [HomeController::class, 'getBookDetails']);
    Route::get('/all-books', [HomeController::class, 'getAllBooks']);
    Route::get('/ebooks', [HomeController::class, 'getAllEbooks']);
    Route::get('/faqs', [HomeController::class, 'getFaqs']);
    Route::get('/page/{slug}', [HomeController::class, 'getSinglePage']);
    Route::get('/getBlogCategories', [HomeController::class, 'getBlogCategories']);
    Route::get('/getBlogs/{categorySlug}', [HomeController::class, 'getBlogs']);
    Route::get('/blog/{slug}', [HomeController::class, 'getSingleBlog']);
    Route::get('/job-circulars', [HomeController::class, 'getJobCirculars']);
    Route::get('/job/{slug}', [HomeController::class, 'getSinpgleJobCircular']);
    Route::post('/contacts-message', [HomeController::class, 'submitContactMessage'])->withoutMiddleware([
        EnsureFrontendRequestsAreStateful::class,
    ]);
    Route::get('/universal-search', [HomeController::class, 'universalSearch']);
    Route::get('/reviews', [HomeController::class, 'reviews']);



    Route::prefix('checkout')->name('checkout.')->group(function () {

        Route::get('/courses', [HomeController::class, 'getCoursesForCheckout'])
            ->name('courses');

        Route::get('/books', [HomeController::class, 'getBooksForCheckout'])
            ->name('books');

        Route::post('/courses', [OrderController::class, 'courseCheckout'])
            ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class])
            ->name('courses.store');

        Route::post('/books', [OrderController::class, 'bookOrderCheckout'])
            ->withoutMiddleware([EnsureFrontendRequestsAreStateful::class])
            ->name('books.store');
    });
    Route::prefix('payment')->name('payment.')->group(function () {

        Route::post('/success', [PaymentController::class, 'success'])
            ->name('success');

        Route::post('/fail', [PaymentController::class, 'fail'])
            ->name('fail');

        Route::post('/cancel', [PaymentController::class, 'cancel'])
            ->name('cancel');

        Route::post('/ipn', [PaymentController::class, 'ipn'])
            ->name('ipn');
    });
});
