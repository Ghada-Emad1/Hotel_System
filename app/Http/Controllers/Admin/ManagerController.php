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

        $query = User::role('manager');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        // Apply country filter
        if ($request->has('country') && $request->country) {
            $query->where('country', $request->country);
        }

        // Apply gender filter
        if ($request->has('gender') && $request->gender) {
            $query->where('gender', $request->gender);
        }

        $managers = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Managers/Index', [
            'managers' => $managers,
            'manualPagination' => true,
            'filters' => $request->only(['search', 'country', 'gender']), // Pass filters to the view
            'countries' => $this->countries, // Pass countries to the view
        ]);
    }

    public function create()
    {
        return Inertia::render('Managers/Create', [
            'countries' => $this->countries, // Pass countries to the create view
        ]);
    }

    public function store(StoreManagerRequest $request)
    {
        $data = $request->validated();

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }

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

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }

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
