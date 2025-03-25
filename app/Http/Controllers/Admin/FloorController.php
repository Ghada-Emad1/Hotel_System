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

        return Inertia::render('Floors/Index', [
            'floors' => $query->get(),
            'userId' => $user->id,
            'isAdmin' => $user->hasRole('admin'),
        ]);
    }

    public function store(StoreFloorRequest $request)
    {
        $latestFloor = Floor::latest()->first();
        $newFloorNumber = $latestFloor ? $latestFloor->number + 1 : 1000; // يبدأ الترقيم من 1000

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
