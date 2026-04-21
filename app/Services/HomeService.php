<?php

/**
 * @Author: Anwarul
 * @Date: 2026-01-15 12:02:48
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-15 14:20:33
 * @Description: Innova IT
 */

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BookOrder;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\ImportantLink;
use App\Models\Course;
use App\Models\JobCircular;
use App\Models\LessonWatch;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Slider;
use App\Models\Book;
use App\Models\CourseCategory;
use App\Models\StudentReview;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletHistory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;

class HomeService
{

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function getSiteSetting()
    {
        // $setting =  Setting::select([
        //     'site_title',
        //     'phone',
        //     'email',
        //     'address',
        //     'whatsapp_number',
        //     'logo',
        //     'favicon',
        //     'facebook',
        //     'youtube',
        //     'instagram',
        //     'twitter',
        //     'telegram',
        //     'app_section_title',
        //     'app_section_description',
        //     'app_play_store_link',
        //     'app_app_store_link',
        //     'footer_description',
        // ])->first();

        $setting =  Setting::first();
        $setting->home_banner = asset($setting->home_banner);
        $setting->about_bottom_banner = asset($setting->about_bottom_banner);
        $setting->about_top_banner = asset($setting->about_top_banner);
        return $setting;
    }
    public function importantlink()
    {
        return ImportantLink::where('status', 'Active')->orderby('order', 'ASC')->get();
    }

    public function reviews()
    {
        return StudentReview::where('status', 'Active')->orderby('order', 'ASC')->get();
    }
    public function submitReview($request)
    {
        $user = Auth::guard('api')->user();

        $data = [
            'course_id' => $request->course_id,
            'review' => $request->review,
            'star' => $request->star,
            'name' => $user->name,
            'designation' => 'Student',
            'status' => 'Inactive',
        ];

        return StudentReview::create($data);
    }

    public function sliders()
    {
        return Slider::where('status', 'Active')->orderby('order', 'ASC')->get();
    }


    public function getAllCourses()
    {
        return Course::with('category:id,title')
            ->where('status', 'Active')
            ->orderBy('order', 'ASC')
            ->simplePaginate(10);
    }

    public function getAffilateCourses()
    {
        $user =  Auth::guard('api')->user();

        return Course::select(
            'id',
            'name',
            'thumbnail',
            'price',
            'slug',
            'course_category_id'
        )
            ->with('category:id,title')
            ->with(['walletHistories' => function ($q) {
                $q->select('id', 'course_id', 'user_id', 'amount', 'type', 'description', 'created_at')
                    ->where('type', 'credit')
                    ->latest();
            }])

            ->with(['enrolls' => function ($q) use ($user) {
                $q->select('id', 'course_id', 'user_id', 'referral_id', 'created_at')
                    ->with(['referralUser:id,name,phone'])
                    ->where('referral_id', $user->id)
                    ->latest();
            }])

            ->withCount([
                'enrolls as referral_count' => function ($q) use ($user) {
                    $q->where('referral_id', $user->id);
                }
            ])

            ->withSum([
                'walletHistories as total_earnings' => function ($q) use ($user) {
                    $q->where('type', 'credit')
                        ->where('user_id', $user->id);
                }
            ], 'amount')


            ->where('status', 'Active')
            ->orderBy('order', 'ASC')
            ->simplePaginate(10);
    }

    public function getfilterCourses(array $filters)
    {
        $categoryId = $filters['category_id'] ?? null;
        $sortBy     = $filters['sort_by'] ?? null;
        $price      = $filters['price'] ?? null;
        $search     = $filters['search'] ?? null;
        $perPage    = $filters['per_page'] ?? 9;

        $category = $categoryId ? CourseCategory::find($categoryId) : null;

        $query = Course::query()
            ->with('category:id,title')
            ->where('status', 'Active');

        if ($categoryId) {
            $query->where('course_category_id', $categoryId);
        }

        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        if ($price === 'free') {
            $query->where('price', 0);
        } elseif ($price === 'paid') {
            $query->where('price', '>', 0);
        }

        switch ($sortBy) {
            case 'newest_first':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest_first':
                $query->orderBy('created_at', 'asc');
                break;
            case 'course_title_az':
                $query->orderBy('name', 'asc');
                break;
            case 'course_title_za':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('order', 'ASC');
                break;
        }

        $courses = $query->paginate($perPage);

        return [
            'category' => $category,
            'courses'  => $courses,
        ];
    }




    public function getCourseDetails($slug)
    {
        $course = Course::with(['modules.lessons', 'instructors.teacher'])->where('slug', $slug)
            ->where('status', 'Active')
            ->first();

        if (!$course) {
            return null;
        }

        $totalLessons   = 0;
        $totalVideos    = 0;
        $totalDocuments = 0;
        $totalQuizzes   = 0;
        $totalSeconds   = 0;

        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {

                $totalLessons++;

                if ($lesson->type === 'video') {
                    $totalVideos++;
                }

                if ($lesson->type === 'docs') {
                    $totalDocuments++;
                }

                if ($lesson->type === 'quiz') {
                    $totalQuizzes++;
                }


                $lesson->document = $lesson->document
                    ? asset($lesson->document)
                    : null;
            }
        }

        $hours   = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $course->total_lessons   = $totalLessons;
        $course->total_videos    = $totalVideos;
        $course->total_documents = $totalDocuments;
        $course->total_quizzes   = $totalQuizzes;
        foreach ($course->instructors as $instructor) {
            if ($instructor->teacher && $instructor->teacher->photo) {
                $instructor->teacher->photo = asset($instructor->teacher->photo);
            }
        }
        $course->reviews   =  StudentReview::where('status', 'Active')->get();

        return $course;
    }



    public function getAllCourseCategory()
    {
        return CourseCategory::where('status', 'Active')->orderby('order', 'ASC')->get();
    }




    public function getFaqs()
    {
        return Faq::select(
            'id',
            'question',
            'answer',
            'order'
        )->where('status', 'Active')->orderby('order', 'ASC')->get();
    }

    public function getSinglePage($slug)
    {
        return Page::select(
            'id',
            'title',
            'slug',
            'content',
        )->where([['slug', $slug], ['status', 'Active']])->first();
    }



    public function getBlogCategories()
    {
        return BlogCategory::select(
            'id',
            'title',
            'slug',
            'order'
        )->where('status', 'Active')->orderby('order', 'ASC')->get();
    }


    public function getBlogs($categorySlug = null)
    {
        $categoryData = null;
        $categoryId = null;

        if (!empty($categorySlug)) {
            $category = BlogCategory::where('slug', $categorySlug)
                ->where('status', 'Active')
                ->first();

            if ($category) {
                $categoryId = $category->id;
                $categoryData = [
                    'id' => $category->id,
                    'title' => $category->title,
                    'slug' => $category->slug,
                ];
            }
        }

        $blogs = Blog::with('category')
            ->select('id', 'blog_category_id', 'title', 'slug', 'thumbnail', 'content', 'order')
            ->where('status', 'Active')
            ->when($categoryId, fn($query) => $query->where('blog_category_id', $categoryId))
            ->orderBy('order', 'ASC')
            ->paginate(9);

        $blogs->getCollection()->transform(fn($blog) => [
            'id' => $blog->id,
            'category' => $blog->category->title ?? null,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'thumbnail' => $blog->thumbnail ? asset($blog->thumbnail) : null,
            'content' => $blog->content,
            'order' => $blog->order,
        ]);

        $data = [
            'blog_category' => $categoryData,
            'blogs' => $blogs,
        ];

        return $data;
    }


    public function getSingleBlog($slug)
    {

        $blog = Blog::select(
            'id',
            'blog_category_id',
            'title',
            'slug',
            'thumbnail',
            'content',
            'order',
            'created_at'
        )->where([['status', 'Active'], ['slug', $slug]])->first();
        if ($blog) {
            $mappedBlog = [
                'id' => $blog->id,
                'category' => $blog->category->title,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'thumbnail' => $blog->thumbnail ? asset($blog->thumbnail) : null,
                'content' => $blog->content,
                'order' => $blog->order,
                'created_at' => $blog->created_at,
            ];
        } else {
            $mappedBlog = null;
        }
        if (!$blog || $mappedBlog === null) {
            throw new Exception("Blog Not Found");
        }
        return $mappedBlog;
    }


    public function getJobCirculars()
    {

        $job_circulars = JobCircular::select(
            'id',
            'title',
            'deadline',
            'slug',
            'thumbnail',
            'file',
            'description',
            'order'
        )
            ->where('status', 'Active')
            ->orderby('order', 'ASC')
            ->simplePaginate(9);

        $job_circulars->getCollection()->transform(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'deadline' => $job->deadline,
                'slug' => $job->slug,
                'thumbnail' => $job->thumbnail ? asset($job->thumbnail) : null,
                'file' => $job->file ? asset($job->file) : null,
                'description' => $job->description,
                'order' => $job->order,
            ];
        });

        return $job_circulars;
    }




    public function getSingleJobCircular($slug)
    {

        $job = JobCircular::select(
            'id',
            'title',
            'deadline',
            'slug',
            'thumbnail',
            'file',
            'description',
            'order'
        )->where([['status', 'Active'], ['slug', $slug]])->first();
        if ($job) {
            $mappedBlog = [
                'id' => $job->id,
                'title' => $job->title,
                'deadline' => $job->deadline,
                'slug' => $job->slug,
                'thumbnail' => $job->thumbnail ? asset($job->thumbnail) : null,
                'file' => $job->file ? asset($job->file) : null,
                'description' => $job->description,
                'order' => $job->order,
            ];
        } else {
            $mappedBlog = null;
        }
        if (!$job || $mappedBlog === null) {
            throw new Exception("Job Circular Not Found");
        }
        return $mappedBlog;
    }


    public function submitContactMessage(array $data)
    {
        return ContactMessage::create($data);
    }


    public function getCoursesForCheckout($category_id, $slug = null)
    {
        $courses = Course::when($slug, function ($query, $slug) {
            $query->where('slug', $slug);
        })
            ->when($category_id, function ($query, $slug) {
                $query->where('course_category_id', $slug);
            })
            ->orderBy('order')
            ->get();
        if ($courses->isEmpty()) {
            throw new Exception("Courses Not Found");
        }
        return $courses;
    }

    public function submitQuizOrWatchLesson(array $data)
    {
        return LessonWatch::updateOrCreate(
            [
                'user_id' => Auth::guard('api')->user()->id,

                'lesson_id' => $data['lesson_id'],
            ],
            [
                'course_id' => $data['course_id'],
                'module_id' => $data['module_id'],
                'mark' => $data['mark'],
            ]
        );
    }


    public function universalSearch($search)
    {
        if (!$search) {
            throw new Exception("No data found");
        }



        $courses = Course::where('name', 'LIKE', "%{$search}%")
            ->select('name', 'slug', 'thumbnail')
            ->get();

        if (

            $courses->isEmpty()
        ) {
            throw new Exception("No data found");
        }

        return [
            'courses' => $courses,
        ];
    }


    public function becomeAffiliate(User $user, array $paymentData)
    {
        if ($user->is_affiliate) {
            throw new \Exception('আপনি ইতিমধ্যে অ্যাফিলিয়েট মেম্বার।');
        }

        $affiliateCode = 'AFF' . strtoupper(uniqid());

        DB::transaction(function () use ($user, $affiliateCode, $paymentData) {
            Affiliate::create(array_merge([
                'user_id'        => $user->id,
                'affiliate_code' => $affiliateCode,
                'status'         => 'Pending',
                'total_earnings' => get_settings('became_affiliate', 0),
            ], $paymentData));

            $user->update(['is_affiliate' => 1]);

            WalletHistory::create([
                'user_id' => $user->id,
                'amount' => get_settings('became_affiliate', 0),
                'type' => 'credit',
                'description' => 'Became an affiliate',
            ]);
        });

        return [
            'affiliate_code' => $affiliateCode,
            'message'        => 'অ্যাফিলিয়েট মেম্বার হওয়া সফল হয়েছে!',
        ];
    }
}
