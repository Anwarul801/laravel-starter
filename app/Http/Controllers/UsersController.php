<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Affiliate;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Hash;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageCustomizeTrait;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users.index|users.create|users.edit|users.destroy', ['only' => ['index', 'store']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.destroy', ['only' => ['destroy']]);
    }
    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->filled('became_affiliate')) {
            $user = User::where('id', $request->became_affiliate)->first();
            if ((int)$user->is_affiliate === 0) {
                $user->is_affiliate = 1;
                $user->save();
                Affiliate::create([
                    'user_id' => $user->id,
                    'affiliate_code' => strtoupper(uniqid()),
                    'total_earnings' => get_settings('became_affiliate', 0),

                ]);
                WalletHistory::create([
                    'user_id' => $user->id,
                    'amount' => get_settings('became_affiliate', 0),
                    'type' => 'Affiliate',
                    'description' => 'Became an affiliate',
                ]);
            }
            return redirect()->back()->with('success', 'User has been marked as affiliate successfully.');
        }
        $search_result = $this->form_search($request);
        $users = User::with('devices')
            ->where($search_result)
            ->when($request->date_filter, function ($query) use ($request) {
                $now = now();
                return match ($request->date_filter) {
                    'today'      => $query->whereDate('created_at', $now->toDateString()),
                    'this_week'  => $query->whereBetween('created_at', [$now->startOfWeek(), $now->copy()->endOfWeek()]),
                    'this_month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
                    'custom'     => $query->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                        ->when($request->end_date,   fn($q) => $q->whereDate('created_at', '<=', $request->end_date)),
                    default      => $query,
                };
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.users.index', compact('users', 'request'));
    }

    private function form_search($request)
    {
        $search_items = [];

        if ($request->name) {
            $search_items[] = ['name', 'like', '%' . $request->name . '%'];
        }
        if ($request->email) {
            $search_items[] = ['email', 'like', '%' . $request->email . '%'];
        }
        if ($request->phone) {
            $search_items[] = ['phone', 'like', '%' . $request->phone . '%'];
        }
        return $search_items;
    }
    /**
     * Show form for creating user
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.users.create', [
            'page_type' => 'create'
        ]);
    }

    /**
     * Store a newly created user
     *
     * @param User $user
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'nullable|email|unique:users,email',
            'phone'           => 'required|string|max:20|unique:users,phone',
            'password'        => 'required|string|min:6',
            'dob'   => 'nullable|date',
            'gender'          => 'nullable|in:Male,Female,Others',
            'profession'      => 'nullable|string|max:255',
            'status'          => 'required|in:Active,Inactive',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = ImageCustomizeTrait::uploadImage(
                $request->file('profile_image'),
                'users',
                200,
                200
            );
        }


        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => bcrypt($request->password),
            'dob'  => $request->dob,
            'gender'         => $request->gender,
            'profession'     => $request->profession,
            'address'     => $request->address,
            'status'         => $request->status,
            'profile_image'  => $profileImagePath,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    // public function show(User $user)
    // {
    //     return view('backend.users.show', [
    //         'user' => $user
    //     ]);
    // }

    public function show(Request $request, User $user)
    {
        // 🔴 Single device delete
        if ($request->filled('delete_device')) {
            $user->devices()
                ->where('id', $request->delete_device)
                ->delete();

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', 'Device deleted successfully');
        }

        // 🔴 All devices delete
        if ($request->has('delete_all_devices')) {
            $user->devices()->delete();

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', 'All devices deleted successfully');
        }

        // 🟢 Normal page load
        $user->load('devices');

        return view('backend.users.show', compact('user'));
    }

    /**
     * Edit user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('backend.users.create', [
            'page_type' => 'edit',
            'user' => $user
        ]);
    }

    /**
     * Update user data
     *
     * @param User $user
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'phone'         => 'required|string|max:20|unique:users,phone,' . $user->id,
            'password'      => 'nullable|string|min:6',
            'dob'           => 'nullable|date',
            'gender'        => 'nullable|in:Male,Female,Others',
            'profession'    => 'nullable|string|max:255',
            'address'       => 'required|string|max:500',
            'status'        => 'required|in:Active,Inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                ImageCustomizeTrait::deleteImage($user->profile_image);
            }
            $path = ImageCustomizeTrait::uploadImage(
                $request->file('profile_image'),
                'users',
                200,
                200
            );
            $user->profile_image = $path;
        }


        // Update basic fields
        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->dob        = $request->dob;
        $user->gender     = $request->gender;
        $user->profession = $request->profession;
        $user->address    = $request->address;
        $user->status     = $request->status;

        // Password update only if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully');
    }
    public function users_list(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return (string)view('backend.users.datatable_users_roles', compact('user'));
                })->rawColumns(['roles'])
                ->addColumn('action', function ($user) {
                    return (string)view('backend.users.datatable_users_action', compact('user'));
                })->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
    }

    /**
     * Delete user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }


    public function loginAs(User $user)
    {
        $user->tokens()->where('name', 'admin-login-as')->delete();

        $token = $user->createToken('admin-login-as')->plainTextToken;

        $redirectUrl = 'https://innovainst.com/auth/callback?token=' . $token
            . '&user_id=' . $user->id;

        return redirect()->away($redirectUrl);
    }
}
