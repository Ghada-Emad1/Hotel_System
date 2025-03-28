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
        $perPage = $request->input('per_page', 10);
        $user = auth()->user();

        $query = User::role('receptionist')
            ->with('manager:id,name')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Receptionists/Index', [
            'receptionists' => $query,
            'isAdmin' => $user->hasRole('admin'),
            'userId' => $user->id,
            'manualPagination' => true,
        ]);
    }

    public function store(StoreReceptionistRequest $request)
    {
        $data = $request->validated();
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

        // Handle avatar upload using the trait
        $data['avatar_image'] = $this->uploadImage($request->file('avatar_image'), 'avatars',$receptionist->avatar_image, 'avatar.png');

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
