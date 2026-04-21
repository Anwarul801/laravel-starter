<?php

/**
 * @Author: Anwarul
 * @Date: 2026-01-22 11:03:42
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 12:37:39
 * @Description: Innova IT
 */


namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\courses;
use App\Models\Enroll;
use App\Models\Transaction;
use App\Models\BookOrder;
use App\Models\BookOrderDetails;
use App\Models\Course;
use App\Models\CourseComment;
use App\Models\Lesson;
use App\Models\LessonWatch;
use App\Models\QuizQuestion;
use App\Models\StudentReview;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\FileCustomizeTrait;
use Carbon\Carbon;

use function PHPUnit\Framework\throwException;

class CommonService
{
    public function getDashboardInfo($request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return [];
        }

        $userId = $user->id;

        $totalCourses = Enroll::where('user_id', $userId)
            ->count();



        $courses = Enroll::with([
            'course' => function ($q) {
                $q->select('id', 'name', 'thumbnail', 'slug')
                    ->withCount('lessons');
            }
        ])
            ->where('user_id', $userId)
            ->where('status', 'Active')
            ->get()
            ->map(function ($enroll) use ($userId) {

                $course = $enroll->course;

                $watchedLessons = LessonWatch::where('user_id', $userId)
                    ->where('course_id', $course->id)
                    ->distinct('lesson_id')
                    ->count('lesson_id');

                return [
                    'course_id'       => $course->id,
                    'course_name'     => $course->name,
                    'slug'            => $course->slug,
                    'thumbnail'       => asset($course->thumbnail),
                    'total_lessons'   => $course->lessons_count,
                    'watched_lessons' => $watchedLessons,
                    'progress'        => $course->lessons_count > 0
                        ? round(($watchedLessons / $course->lessons_count) * 100)
                        : 0,
                ];
            });

        return [
            'counts' => [
                'total_courses' => $totalCourses,

            ],
            'courses' => $courses,
        ];
    }
    public function mycourse($request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return [];
        }
        $userId = $user->id;

        $courses = Enroll::with('course:id,name,thumbnail,slug')
            ->where('user_id', $userId)
            ->where('status', 'Active')
            ->latest()
            ->paginate(10);

        $courses->getCollection()->transform(function ($enroll) use ($userId) {

            $course = $enroll->course;

            if (!$course) {
                $enroll->completion = 0;
                return $enroll;
            }

            $totalLessons = $course->lessons()->count();

            $completedLessons = LessonWatch::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->count();

            $completion = 0;
            if ($totalLessons > 0) {
                $completion = round(($completedLessons / $totalLessons) * 100);
            }

            $enroll->total_lessons = $totalLessons;
            $enroll->completed_lessons = $completedLessons;
            $enroll->completion = $completion;

            return $enroll;
        });


        return [
            'courses'  => $courses,
        ];
    }


    public function courseDetails($slug)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $course = Course::with([
            'modules.lessons.userWatch'
        ])
            ->where('slug', $slug)
            // ->where('status', 'Active')
            ->first();

        if (!$course) {
            return null;
        }

        $totalLessons   = 0;
        $totalVideos    = 0;
        $totalDocuments = 0;
        $totalQuizzes   = 0;

        foreach ($course->modules as $module) {
            $moduleTotalLessons = 0;
            $moduleCompletedLessons = 0;

            foreach ($module->lessons as $lesson) {

                $lesson->watch = $lesson->userWatch;

                $moduleTotalLessons++;
                if ($lesson->watch) {
                    $moduleCompletedLessons++;
                }

                $totalLessons++;

                if ($lesson->type === 'video') {
                    $totalVideos++;
                }
                if ($lesson->type === 'docs') {
                    $totalDocuments++;
                }
                if ($lesson->type === 'quiz') {
                    $totalQuizzes++;
                    $lesson->question_count = QuizQuestion::where('course_id', $course->id)
                        ->where('lesson_id', $lesson->id)
                        ->count();
                }

                unset($lesson->userWatch);
            }

            $module->total_lessons = $moduleTotalLessons;
            $module->completed_lessons = $moduleCompletedLessons;
        }

        $course->total_lessons   = $totalLessons;
        $course->total_videos    = $totalVideos;
        $course->total_documents = $totalDocuments;
        $course->total_quizzes   = $totalQuizzes;

        return [
            'course' => $course,
        ];
    }


    public function payment_history()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return [];
        }
        $userId = $user->id;
        return Transaction::where('user_id', $userId)->orderby('id', 'DESC')->paginate(10);
    }

    public function devices()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return [];
        }
        $userId = $user->id;
        return UserDevice::where('user_id', $userId)->orderby('id', 'DESC')->get();
    }

    public function order_history()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return [];
        }
        $userId = $user->id;
        return Enroll::with('course')->where('user_id', $userId)->orderby('id', 'DESC')->paginate(10);
    }



    public function removeDevice($id)
    {
        $user = Auth::guard('api')->user();

        $device = UserDevice::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $device->delete();

        return true;
    }

    public function courseComments($data)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return false;
        }

        $comment = CourseComment::create([
            'user_id'   => $user->id,
            'course_id' => $data['course_id'],
            'module_id' => $data['module_id'] ?? null,
            'lesson_id' => $data['lesson_id'] ?? null,
            'question' => $data['question'],
            'answer'   => null,
            'status'   => 'active',
        ]);

        return $comment;
    }


    public function getCourseComments($courseId, $lessonId = null)
    {
        $user = Auth::guard('api')->user();

        $query = CourseComment::with('user')
            ->where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->where('status', 'active');

        if ($lessonId) {
            $query->where('lesson_id', $lessonId);
        }

        return $query->latest()->get();
    }


    public function getQuizQuestions($courseSlug, $lessonId)
    {
        $course = Course::where('slug', $courseSlug)->first();
        $lesson = Lesson::where('id', $lessonId)->first();
        $user = Auth::guard('api')->user();

        $query = QuizQuestion::where('course_id', $course->id)
            ->where('lesson_id', $lessonId)->get();
        return [
            'course' => $course,
            'lesson' => $lesson,
            'questions' => $query,
        ];
    }


    public function enrollInfo($id)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $enroll = Enroll::with('course:id,name,slug,price,discount')
            ->where('id', $id)
            ->first();

        if (!$enroll) {
            return response()->json(['message' => 'Enroll not found'], 404);
        }
        return  $enroll;

      
    }
}
