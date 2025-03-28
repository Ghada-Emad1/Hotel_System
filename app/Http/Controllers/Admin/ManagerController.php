<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    use ImageUploadTrait;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);

        $managers = User::role('manager')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Managers/Index', [
            'managers' => $managers,
            'manualPagination' => true,
        ]);
    }

    public function create()
    {
        return Inertia::render('Managers/Create');
    }

    public function store(StoreManagerRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Handle avatar upload using the trait
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', null, 'avatar.png');

        $data['role'] = 'manager';

        $user = User::create($data);
        $user->assignRole('manager');

        return redirect()->route('manager.index')->with('success', 'Manager created successfully.');
    }

    public function update(UpdateManagerRequest $request, User $manager)
    {
        $data = $request->validated();
        unset($data['national_id']); // Prevent updating the national ID

        // Handle avatar upload using the trait
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', $manager->avatar_image, 'avatar.png');

        $manager->update($data);

        return redirect()->route('manager.index')->with('success', 'Manager updated successfully.');
    }

    public function destroy(User $manager)
    {
        // Delete the avatar using the trait
        $this->deleteImage($manager->avatar_image, 'avatars');

        $manager->delete();

        return back()->with('success', 'Manager deleted successfully.');
    }
}
