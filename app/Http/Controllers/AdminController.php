<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::with('roles')
            ->when($request->name, fn($q) =>
                $q->where('name', 'like', "%{$request->name}%"))
            ->when($request->email, fn($q) =>
                $q->where('email', 'like', "%{$request->email}%"))
            ->when($request->phone, fn($q) =>
                $q->where('phone', 'like', "%{$request->phone}%"))
            ->paginate(10);

        return view('backend.admin.index', compact('admins', 'request'));
    }

    public function create()
    {
        $roles = Role::latest()->get();
        return view('backend.admin.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'phone'    => 'required|string|unique:admins,phone',
            'password' => 'required|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);

        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role,
        ]);

        $role = Role::findById($request->role);
        $admin->assignRole($role->name);

        return redirect()
            ->route('admin.index')
            ->withSuccess('Admin created successfully.');
    }

    public function show(Admin $admin)
    {
        return view('backend.admin.show', ['user' => $admin]);
    }

    public function edit(Admin $admin)
    {
        return view('backend.admin.edit', [
            'user'     => $admin,
            'userRole' => $admin->roles->pluck('name')->toArray(),
            'roles'    => Role::latest()->get()
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'phone'    => 'required|string|unique:admins,phone,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|exists:roles,id',
        ]);

        $data = $request->except(['password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        $role = Role::findById($request->role);
        $admin->syncRoles([$role->name]);

        return redirect()
            ->route('admin.index')
            ->withSuccess('Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()
            ->route('admin.index')
            ->withSuccess('Admin deleted successfully.');
    }
}
