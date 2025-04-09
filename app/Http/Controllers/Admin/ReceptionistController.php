<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReceptionistRequest;
use App\Http\Requests\UpdateReceptionistRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;
class ReceptionistController extends Controller
{
    use ImageUploadTrait;



    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $user = auth()->user();

        $query = User::role('receptionist')->with('manager:id,name');

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

        $receptionists = $query->paginate($perPage)->withQueryString();

        // Format the created_at field
        $receptionists->getCollection()->transform(function ($receptionist) {
            $receptionist->created_at_formatted = $receptionist->created_at->format('d M Y, h:i A');
            return $receptionist;
        });

        return Inertia::render('Receptionists/Index', [
            'receptionists' => $receptionists,
            'isAdmin' => $user->hasRole('admin'),
            'userId' => $user->id,
            'manualPagination' => true,
            'filters' => $request->only(['search', 'country', 'gender']), // Pass filters to the view
            'countries' => $this->countries, // Pass countries to the view
        ]);
    }

    public function store(StoreReceptionistRequest $request)
    {
        $data = $request->validated();

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'receptionist';

        if (auth()->user()->hasRole('manager')) {
            $data['manager_id'] = auth()->id();
        }

        // Handle avatar upload using the trait
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', null, 'avatar.png');
        $receptionist = User::create($data);
        $receptionist->assignRole('receptionist');

        return redirect()->route('receptionist.index')->with('success', 'Receptionist created successfully.');
    }

    public function update(UpdateReceptionistRequest $request, User $receptionist)
    {
        $data = $request->validated();

        // Validate country against the JSON list
        if (!in_array($data['country'], $this->countries)) {
            return back()->withErrors(['country' => 'The selected country is invalid.'])->withInput();
        }

        // Handle avatar upload using the trait
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars', $receptionist->avatar_image, 'avatar.png');

        $receptionist->update($data);

        return redirect()->route('receptionist.index')->with('success', 'Receptionist updated successfully.');
    }

    public function destroy(User $receptionist)
    {

        // Delete the avatar using the trait
        $this->deleteImage($receptionist->avatar_image, 'avatars');


        $receptionist->delete();

        return back()->with('success', 'Receptionist deleted successfully.');
    }

    public function ban(User $receptionist)
    {
        if (auth()->user()->hasRole('manager') && $receptionist->manager_id !== auth()->id()) {
            return back()->with('error', 'You can only ban receptionists you created.');
        }

        $receptionist->ban();
        return back()->with('success', 'Receptionist banned successfully.');
    }

    public function unban(User $receptionist)
    {
        if (auth()->user()->hasRole('manager') && $receptionist->manager_id !== auth()->id()) {
            return back()->with('error', 'You can only unban receptionists you created.');
        }

        $receptionist->unban();
        return back()->with('success', 'Receptionist unbanned successfully.');
    }
}
