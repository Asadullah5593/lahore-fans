<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

/**
 * Admin accounts. There is no public sign-up — every account that can reach the
 * back office is created here by someone who is already signed in.
 */
class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create', ['user' => new User()]);
    }

    public function store(UserRequest $request)
    {
        // The 'hashed' cast on User::$casts hashes the password on assignment.
        User::create($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        // A blank password field means "leave the current password alone".
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete the account you are signed in with.');
        }

        if (User::count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'At least one account must remain — the admin panel would be locked out otherwise.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted.');
    }
}
