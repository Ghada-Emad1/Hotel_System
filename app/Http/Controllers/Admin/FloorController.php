<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFloorRequest;
use App\Http\Requests\UpdateFloorRequest;
use App\Models\Floor;
use App\Models\Room;
use Inertia\Inertia;

class FloorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Floor::query()->with('manager:id,name');

        if ($user->hasRole('manager')) {
            $query->where('manager_id', $user->id);
        }

        $floors = $query->get();

        return Inertia::render('Floors/Index', [
            'floors' => $floors,
            'isAdmin' => $user->hasRole('admin'),
            'userId' => $user->id,
        ]);
    }

    public function store(StoreFloorRequest $request)
    {
        $nextNumber = max(1000, (Floor::max('number') ?? 999) + 1);

        Floor::create([
            'name' => $request->name,
            'number' => $nextNumber,
            'manager_id' => auth()->id(),
        ]);

        return redirect()->route('floors.index')->with('success', 'Floor created successfully.');
    }

    public function update(UpdateFloorRequest $request, Floor $floor)
    {
        if (auth()->id() !== $floor->manager_id && auth()->user()->hasRole('manager')) {
            abort(403);
        }

        $floor->update($request->validated());

        return redirect()->route('floors.index')->with('success', 'Floor updated successfully.');
    }

    public function destroy(Floor $floor)
    {
        if (Room::where('floor_id', $floor->id)->exists()) {
            return back()->with('error', 'Cannot delete this floor because it has rooms.');
        }

        if (auth()->id() !== $floor->manager_id && auth()->user()->hasRole('manager')) {
            abort(403);
        }

        $floor->delete();

        return back()->with('success', 'Floor deleted.');
    }
}
