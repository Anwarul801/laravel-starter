<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ImageCustomizeTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.index|users.create|users.edit|users.destroy', ['only' => ['index', 'store']]);
        $this->middleware('permission:users.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users.destroy', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $users = User::when($request->name, fn($q) =>
                $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn($q) =>
                $q->where('email', 'like', "%{$request->email}%"))
            ->when($request->phone, fn($q) =>
                $q->where('phone', 'like', "%{$request->phone}%"))
            ->when($request->date_filter, function ($query) use ($request) {
                $now = now();
                return match ($request->date_filter) {
                    'today'      => $query->whereDate('created_at', $now->toDateString()),
                    'this_week'  => $query->whereBetween('created_at', [$now->startOfWeek(), $now->copy()->endOfWeek()]),
                    'this_month' => $query->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year),
                    'custom'     => $query->when($request->start_date, fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
                                         ->when($request->end_date, fn($q) => $q->whereDate('created_at', '<=', $request->end_date)),
                    default      => $query,
                };
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('backend.users.index', compact('users', 'request'));
    }

    public function create()
    {
        return view('backend.users.create', [
            'page_type' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email',
            'phone'         => 'required|string|max:20|unique:users,phone',
            'password'      => 'required|string|min:6',
            'dob'           => 'nullable|date',
            'gender'        => 'nullable|in:Male,Female,Others',
            'profession'    => 'nullable|string|max:255',
            'status'        => 'required|in:Active,Inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'password'      => bcrypt($request->password),
            'dob'           => $request->dob,
            'gender'        => $request->gender,
            'profession'    => $request->profession,
            'address'       => $request->address,
            'status'        => $request->status,
            'profile_image' => $profileImagePath,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(Request $request, User $user)
    {
        if ($request->filled('delete_device')) {
            $user->devices()->where('id', $request->delete_device)->delete();
            return redirect()->route('users.show', $user->id)->with('success', 'Device deleted successfully');
        }

        if ($request->has('delete_all_devices')) {
            $user->devices()->delete();
            return redirect()->route('users.show', $user->id)->with('success', 'All devices deleted successfully');
        }

        $user->load('devices');
        return view('backend.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('backend.users.create', [
            'page_type' => 'edit',
            'user'      => $user,
        ]);
    }

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
            'address'       => 'nullable|string|max:500',
            'status'        => 'required|in:Active,Inactive',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                ImageCustomizeTrait::deleteImage($user->profile_image);
            }
            $user->profile_image = ImageCustomizeTrait::uploadImage(
                $request->file('profile_image'),
                'users',
                200,
                200
            );
        }

        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->dob        = $request->dob;
        $user->gender     = $request->gender;
        $user->profession = $request->profession;
        $user->address    = $request->address;
        $user->status     = $request->status;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
