<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookOrder;
use App\Models\User;
use App\Models\Book;
use App\Models\Course;
use App\Models\Enroll;
use App\Models\Transaction;
use App\Models\BookOrderDetails;
use App\Models\BookQrCode;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function users_search(Request $request)
    {
        try {
            $query = $request->input('query');

            if (empty($query)) {
                return response()->json([]);
            }

            $users = User::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('phone', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name ?? 'N/A',
                        'phone' => $user->phone ?? 'N/A',
                        'email' => $user->email ?? 'N/A',
                        'url' => route('users.show', $user->id),
                    ];
                });

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('User search error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
