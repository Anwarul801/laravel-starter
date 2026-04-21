<?php

/**
 * @Author: Anwarul
 * @Date: 2026-01-15 11:56:08
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-15 14:13:42
 * @Description: Innova IT
 */

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use App\Models\Affiliate;
use App\Models\Lesson;
use App\Models\WalletHistory;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Services\HomeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected $HomeService;

    public function __construct(HomeService $HomeService)
    {
        $this->HomeService = $HomeService;
    }

    public function sitesetting()
    {
        try {
            $sitesetting = $this->HomeService->getSiteSetting();
            if (!$sitesetting) {
                return ResponseHelper::error('Sitesetting not found', 404);
            }
            return ResponseHelper::success($sitesetting, 'Sitesetting fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch Sitesetting: ' . $e->getMessage());
        }
    }

    public function importantlink()
    {
        try {
            $data = $this->HomeService->importantlink();
            if (!$data) {
                return ResponseHelper::error('importantlink not found', 404);
            }
            return ResponseHelper::success($data, 'importantlink fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch importantlink: ' . $e->getMessage());
        }
    }
    public function reviews()
    {
        try {
            $data = $this->HomeService->reviews();
            if (!$data) {
                return ResponseHelper::error('Reviews not found', 404);
            }
            return ResponseHelper::success($data, 'Reviews fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch Reviews: ' . $e->getMessage());
        }
    }
    public function reviewsAdd(Request $request)
    {
        try {
            $data = $this->HomeService->submitReview($request);
            return ResponseHelper::success('Reviews submited successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to fetch Reviews: ' . $e->getMessage());
        }
    }

    public function sliders()
    {
        try {
            $data = $this->HomeService->sliders();
            return ResponseHelper::success($data, 'All Slider fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function getAllCourses()
    {
        try {
            $data = $this->HomeService->getAllCourses();
            return ResponseHelper::success($data, 'All Courses fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function getAffilateCourses()
    {
        try {
            $data = $this->HomeService->getAffilateCourses();
            return ResponseHelper::success($data, 'All Courses fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function getAllCourseCategory()
    {
        try {
            $data = $this->HomeService->getAllCourseCategory();
            return ResponseHelper::success($data, 'All getAllCourseCategory fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }


    public function getfilterCourses(Request $request)
    {
        try {
            $filters = [
                'category_id' => $request->query('category_id'),
                'sort_by'     => $request->query('sort_by'),
                'price'       => $request->query('price'),
                'search'      => $request->query('search'),
                'per_page'    => $request->query('per_page', 9),
            ];

            $data = $this->HomeService->getfilterCourses($filters);

            $message = $filters['category_id']
                ? 'Courses fetched for selected filters'
                : 'All courses fetched successfully';

            return ResponseHelper::success($data, $message);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function getCourseDetails($slug)
    {
        try {
            $data = $this->HomeService->getCourseDetails($slug);
            if (!$data) {
                return ResponseHelper::error('Course not found', 404);
            }
            return ResponseHelper::success($data, 'Course details fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }



    public function getFaqs()
    {
        try {
            $data = $this->HomeService->getFaqs();
            return ResponseHelper::success($data, 'Faqs fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function getSinglePage($slug)
    {
        try {
            $data = $this->HomeService->getSinglePage($slug);
            return ResponseHelper::success($data, 'Page fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }


    public function getBlogCategories()
    {
        try {
            $data = $this->HomeService->getBlogCategories();
            return ResponseHelper::success($data, 'Blog Categories fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function getBlogs($categorySlug = null)
    {
        try {
            $data = $this->HomeService->getBlogs($categorySlug);
            return ResponseHelper::success($data, 'Blogs fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function getSingleBlog($slug)
    {
        try {
            $data = $this->HomeService->getSingleBlog($slug);
            return ResponseHelper::success($data, 'Blog fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }


    public function getJobCirculars()
    {
        try {
            $data = $this->HomeService->getJobCirculars();
            return ResponseHelper::success($data, 'Job Circulars fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }


    public function getSingleJobCircular($slug)
    {
        try {
            $data = $this->HomeService->getSingleJobCircular($slug);
            return ResponseHelper::success($data, 'Job Circular fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }



    public function submitContactMessage(Request $request)
    {

        try {
            $data = $request->validate([
                'name'        => 'required|string|max:255',
                'phone'       => 'required|string|max:20',
                'email'       => 'required|email|max:255',
                'subject'     => 'required|string|max:255',
                'message'     => 'required|string',
            ]);
            $data = $this->HomeService->submitContactMessage($data);
            return ResponseHelper::success($data, 'Contact message submited successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }




    public function getCoursesForCheckout(Request $request)
    {
        try {
            $category_id = $request->query('category_id');
            $slug = $request->query('slug');
            $data = $this->HomeService->getCoursesForCheckout($category_id, $slug);
            return ResponseHelper::success($data, 'Course fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function getBooksForCheckout(Request $request)
    {
        try {
            $type = $request->query('type');
            $data = $this->HomeService->getBooksForCheckout($type);
            return ResponseHelper::success($data, 'Course fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }
    public function submitQuizOrWatchLesson(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'type' => 'required|string',
                'lesson_id' => 'required|integer|exists:lessons,id',
                'mark' => 'required_if:type,quiz',
            ]);
            $lesson = Lesson::findOrFail($validatedData['lesson_id']);
            $validatedData['user_id'] = Auth::id();
            $validatedData['course_id'] = $lesson->course_id;
            $validatedData['module_id'] = $lesson->module_id;
            if ($validatedData['type'] != 'quiz') {
                $validatedData['mark'] = null;
            }
            $data = $this->HomeService->submitQuizOrWatchLesson($validatedData);
            if ($validatedData['type'] == 'quiz') {
                return ResponseHelper::success($data, 'Quiz Submitted successfully');
            } else {
                return ResponseHelper::success($data, 'Lesson Watched successfully');
            }
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }



    public function universalSearch(Request $request)
    {
        try {
            $search = $request->query('search');
            $data = $this->HomeService->universalSearch($search);

            return ResponseHelper::success($data, 'Search data fetched successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function becomeAffiliate(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_method'       => 'required|in:Bank,Bkash,Nagad,Rocket',
                'phone'                => 'required_if:payment_method,Bkash,Nagad,Rocket',
                'bank_account'         => 'required_if:payment_method,Bank',
                'account_holder_name'  => 'required_if:payment_method,Bank',
                'bank_name'            => 'required_if:payment_method,Bank',
                'branch'               => 'required_if:payment_method,Bank',
                'routing_number'       => 'nullable|string',
                'swift_code'           => 'nullable|string',
                'notes'                => 'nullable|string',
            ]);

            $user = Auth::guard('api')->user();
            $data = $this->HomeService->becomeAffiliate($user, $validated);
            return ResponseHelper::success($data, $data['message']);
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function requestWithdraw(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user->is_affiliate) {
            return $this->error('আপনি অ্যাফিলিয়েট মেম্বার নন।', 403);
        }


        $walletHistoryCount = WalletHistory::where('user_id', $user->id)->count();

        if ($walletHistoryCount < 2) {
            return $this->error('উত্তোলনের জন্য কমপক্ষে ২টি ওয়ালেট হিস্টোরি থাকতে হবে।', 403);
        }


        $affiliate = Affiliate::where('user_id', $user->id)->first();
        $totalEarnings = $affiliate->total_earnings ?? 0;
        $totalWithdrawn = Withdraw::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->sum('amount');
        $availableBalance = $totalEarnings - $totalWithdrawn;

        $request->validate([
            'amount'            => 'required|numeric|max:' . $availableBalance,
            'payment_method'    => 'required|in:Bank,Bkash,Nagad,Rocket',
            'phone'             => 'required_if:payment_method,Bkash,Nagad,Rocket',
            'bank_account'      => 'required_if:payment_method,Bank',
            'account_holder_name' => 'required_if:payment_method,Bank',
            'bank_name'         => 'required_if:payment_method,Bank',
            'branch'            => 'required_if:payment_method,Bank',
            'routing_number'    => 'nullable|string',
            'swift_code'        => 'nullable|string',
            'notes'             => 'nullable|string',
        ], [
            'amount.max' => 'আপনার অ্যাকাউন্টে পর্যাপ্ত ব্যালেন্স নেই।',
        ]);

        try {
            DB::beginTransaction();

            $withdraw = Withdraw::create([
                'user_id'              => $user->id,
                'amount'               => $request->amount,
                'payment_method'       => $request->payment_method,
                'phone'                => $request->phone,
                'bank_account'         => $request->bank_account,
                'account_holder_name'  => $request->account_holder_name,
                'bank_name'            => $request->bank_name,
                'branch'               => $request->branch,
                'routing_number'       => $request->routing_number,
                'swift_code'           => $request->swift_code,
                'notes'                => $request->notes,
                'status'               => 'Pending',
            ]);

            Affiliate::where('user_id', $user->id)->update([
                'withdrawal_pending' => $request->amount,
                'withdrawal_amount'  => $request->amount,
            ]);
            DB::commit();
            return ResponseHelper::success($withdraw, 'উত্তোলনের অনুরোধ সফলভাবে জমা দেওয়া হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('উত্তোলনের অনুরোধ করতে ব্যর্থ হয়েছে।', 500);
        }
    }


    public function listWithdraws(Request $request)
    {
        $user = Auth::guard('api')->user();

        $withdraws = Withdraw::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $walletHistoryCount = \App\Models\WalletHistory::where('user_id', $user->id)->count();

        $canWithdraw = $walletHistoryCount >= 2;

        $data = [
            'withdraws'     => $withdraws,
            'can_withdraw'  => $canWithdraw
        ];

        return ResponseHelper::success($data, 'Withdraw request return');
    }
}
