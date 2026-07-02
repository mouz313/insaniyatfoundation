<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();

        $query = User::with('roles');

        if ($superAdminRole) {
            $query->whereDoesntHave('roles', fn($q) => $q->where('roles.id', $superAdminRole->id));
        }

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $totalStaff = User::count();
        $staff = $query->latest()->paginate(20);
        return view('admin.staff.index', compact('staff', 'totalStaff'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super_admin')->orderBy('name')->get();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $roles = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
        $user->assignRole($roles);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $isSuperAdmin = $user->hasRole('super_admin');

        if ($isSuperAdmin && !auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'You cannot edit a super admin.');
        }

        $roles = $isSuperAdmin
            ? Role::where('name', 'super_admin')->orderBy('name')->get()
            : Role::where('name', '!=', 'super_admin')->orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.staff.edit', compact('user', 'roles', 'userRoles', 'isSuperAdmin'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('super_admin') && !auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'You cannot modify a super admin.');
        }

        if ($user->hasRole('super_admin')) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if ($data['password']) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            return redirect()->route('admin.staff.edit', $user->id)
                ->with('success', 'Profile updated. Super admin roles cannot be modified.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if ($data['password']) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        $roles = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
        $user->syncRoles($roles);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('super_admin') && !auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'You cannot delete a super admin.');
        }

        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Super admin cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff deleted successfully.');
    }
}
