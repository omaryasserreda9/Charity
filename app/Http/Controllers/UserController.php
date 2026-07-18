<?php

namespace App\Http\Controllers;

use App\Models\CharityHome;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:users.view');
    }

    public function index(Request $request): View
    {
        $this->authorize('users.view');

        $search = (string) $request->input('search', '');

        $users = User::query()
            ->when(! auth()->user()->isSuperAdmin(), fn ($query) => $query->where('charity_home_id', auth()->user()->charity_home_id))
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->with('roles', 'charityHome')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['المستخدمون' => route('users.index')];

        return view('users.index', compact('users', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $this->authorize('users.add');

        $roles = auth()->user()->isSuperAdmin()
            ? Role::orderBy('name')->get()
            : Role::where('name', '!=', 'Super Admin')->orderBy('name')->get();
        $charityHomes = auth()->user()->isSuperAdmin()
            ? CharityHome::orderBy('title')->get()
            : CharityHome::where('id', auth()->user()->charity_home_id)->orderBy('title')->get();

        abort_if(! auth()->user()->isSuperAdmin() && ! auth()->user()->charity_home_id, 403);

        $breadcrumbs = [
            'المستخدمون' => route('users.index'),
            'إضافة مستخدم' => route('users.create'),
        ];

        return view('users.create', compact('roles', 'charityHomes', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('users.add');

        $attributes = $this->validatedAttributes($request);
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['active'] = $request->boolean('active', true);

        if (! auth()->user()->isSuperAdmin()) {
            abort_if(! auth()->user()->charity_home_id, 403);
            $attributes['charity_home_id'] = auth()->user()->charity_home_id;
        }

        $user = User::create($attributes);
        $user->roles()->sync($request->input('roles', []));

        if (auth()->user()->isSuperAdmin()) {
            $this->handleCharityHomeAssignment($user, null, $user->charity_home_id);
        }

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    public function show(User $user): View
    {
        $this->authorize('users.view');
        $this->ensureUserManageable($user);

        $user->load('roles', 'charityHome');

        $breadcrumbs = [
            'المستخدمون' => route('users.index'),
            $user->name => route('users.show', $user),
        ];

        return view('users.show', compact('user', 'breadcrumbs'));
    }

    public function edit(User $user): View
    {
        $this->authorize('users.edit');
        $this->ensureUserManageable($user);

        $roles = auth()->user()->isSuperAdmin()
            ? Role::orderBy('name')->get()
            : Role::where('name', '!=', 'Super Admin')->orderBy('name')->get();
        $charityHomes = auth()->user()->isSuperAdmin()
            ? CharityHome::orderBy('title')->get()
            : CharityHome::where('id', auth()->user()->charity_home_id)->orderBy('title')->get();

        $breadcrumbs = [
            'المستخدمون' => route('users.index'),
            'تعديل المستخدم' => route('users.edit', $user),
        ];

        return view('users.edit', compact('user', 'roles', 'charityHomes', 'breadcrumbs'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('users.edit');
        $this->ensureUserManageable($user);

        $attributes = $this->validatedAttributes($request, $user);

        if ($request->filled('password')) {
            $attributes['password'] = Hash::make($request->input('password'));
        }

        if ($user->id === 1) {
            $attributes['charity_home_id'] = null;
        }

        if (! auth()->user()->isSuperAdmin()) {
            $attributes['charity_home_id'] = $user->charity_home_id ?? auth()->user()->charity_home_id;
        }

        $attributes['active'] = $request->boolean('active', $user->active ?? true);

        $oldCharityHomeId = $user->charity_home_id;

        $user->update($attributes);
        $user->roles()->sync($request->input('roles', []));

        if (auth()->user()->isSuperAdmin()) {
            $this->handleCharityHomeAssignment($user, $oldCharityHomeId, $user->charity_home_id);
        }

        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('users.delete');
        $this->ensureUserManageable($user);

        if ($user->id === 1) {
            return redirect()->route('users.index')->with('error', 'لا يمكن حذف المستخدم الإداري الرئيسي.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }

    private function ensureUserManageable(User $user): void
    {
        if (auth()->user()->isSuperAdmin()) {
            return;
        }

        abort_if(
            ! auth()->user()->charity_home_id ||
            $user->charity_home_id !== auth()->user()->charity_home_id,
            403
        );
    }

    private function validatedAttributes(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => $user
                ? ['sometimes', 'nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'],
            'charity_home_id' => [
                'nullable',
                Rule::exists('charity_homes', 'id'),
                function ($attribute, $value, $fail) use ($user) {
                    if (! auth()->user()->isSuperAdmin() && $user && $user->charity_home_id && $value && $value !== $user->charity_home_id) {
                        $fail('لا يمكن تغيير بيت الجمعية لمستخدم تم تعيينه بالفعل.');
                    }
                },
            ],
            'active' => ['sometimes', 'boolean'],
            'roles' => ['array'],
            'roles.*' => [Rule::exists('roles', 'id')],
        ]);
    }

    private function handleCharityHomeAssignment(User $user, ?int $oldCharityHomeId, ?int $newCharityHomeId): void
    {
        if ($oldCharityHomeId === $newCharityHomeId) {
            return;
        }

        // Case 1: Assigned to a new home
        if ($newCharityHomeId !== null) {
            // Find other users assigned to this home
            $oldUsers = User::where('charity_home_id', $newCharityHomeId)
                ->where('id', '!=', $user->id)
                ->get();

            foreach ($oldUsers as $oldUser) {
                // Remove their permissions
                $oldUser->roles()->detach();
                $oldUser->update(['charity_home_id' => null]);
            }

            // Find or create the Charity Home Manager role
            $managerRole = Role::firstOrCreate(
                ['name' => 'Charity Home Manager'],
                ['description' => 'Manager of a Charity Home with all permissions except charity homes management.']
            );

            // Sync all permissions except charity_homes.*
            $permissions = \App\Models\Permission::where('name', 'not like', 'charity_homes.%')->get();
            $managerRole->permissions()->sync($permissions->pluck('id')->all());

            // Assign role to the user
            $user->roles()->syncWithoutDetaching([$managerRole->id]);
        }
        // Case 2: Unassigned from a home
        elseif ($oldCharityHomeId !== null) {
            $managerRole = Role::where('name', 'Charity Home Manager')->first();
            if ($managerRole) {
                $user->roles()->detach($managerRole->id);
            }
        }
    }
}
