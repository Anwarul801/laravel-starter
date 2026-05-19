<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('home');
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
                ->map(fn($user) => [
                    'id'    => $user->id,
                    'name'  => $user->name  ?? 'N/A',
                    'phone' => $user->phone ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'url'   => route('users.show', $user->id),
                ]);

            return response()->json($users);
        } catch (\Exception $e) {
            \Log::error('User search error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function page_view($slug)
    {
        return view('home');
    }
}
