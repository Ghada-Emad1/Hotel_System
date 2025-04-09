<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFloorRequest;
use App\Http\Requests\UpdateFloorRequest;
use App\Models\Floor;
use App\Models\User;
use App\Models\Room;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FloorController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->input('per_page', 10);
        $query = Floor::query()->with('manager:id,name');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%");
            });
        }

        // Apply manager filter
        if ($request->has('manager_id') && $request->manager_id) {
            $query->where('manager_id', $request->manager_id);
        }

        $floors = $query->paginate($perPage)->withQueryString();

        // Fetch all managers for the filter dropdown
        $managers=User::role('manager')->select('id', 'name')->get();

        return Inertia::render('Floors/Index', [
            'floors' => $floors,
            'userId' => $user->id,
            'isAdmin' => $user->hasRole('admin'),
            'filters' => $request->only(['search', 'manager_id']), // Pass filters to the view
            'managers' => $managers, // Pass managers to the view
        ]);
    }

    public function store(StoreFloorRequest $request)
    {
        $latestFloor = Floor::latest()->first();
        $newFloorNumber = $latestFloor ? $latestFloor->number + 1 : 1000;

        Floor::create([
            'name' => $request->name,
            'number' => $newFloorNumber,
            'manager_id' => auth()->id(),
        ]);

        return redirect()->route('floor.index')->with('success', 'Floor created successfully.');
    }

    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        if (auth()->id() !== $floor->manager_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $floor->update([
            'name' => $request->name,
        ]);

        return redirect()->route('floor.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        if ($floor->rooms()->exists()) {
            return back()->with('error', 'Cannot delete this floor because it has associated rooms.');
        }

        if (auth()->id() !== $floor->manager_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $floor->delete();

        return back()->with('success', 'Floor deleted successfully.');
    }
}
