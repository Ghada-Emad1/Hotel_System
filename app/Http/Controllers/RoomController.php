<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Reservation;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Room::query()->with('floor:id,name', 'manager:id,name');

        if ($user->hasRole('manager')) {
            $query->where('manager_id', $user->id);
        }

        return Inertia::render('Rooms/Index', [
            'rooms' => $query->get(),
            'floors' => Floor::select('id', 'name')->get(),
            'isAdmin' => $user->hasRole('admin'),
            'userId' => $user->id,
        ]);
    }

    public function store(StoreRoomRequest $request)
    {
        Room::create([
            'floor_id' => $request->floor_id,
            'number' => $request->number,
            'capacity' => $request->capacity,
            'price' => $request->price * 100, // Store in cents
            'manager_id' => auth()->id(),
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        if (auth()->id() !== $room->manager_id && auth()->user()->hasRole('manager')) {
            abort(403);
        }

        $room->update([
            'floor_id' => $request->floor_id,
            'capacity' => $request->capacity,
            'price' => $request->price * 100,
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if (Reservation::where('room_id', $room->id)->exists()) {
            return back()->with('error', 'Cannot delete this room because it is reserved.');
        }

        if (auth()->id() !== $room->manager_id && auth()->user()->hasRole('manager')) {
            abort(403);
        }

        $room->delete();

        return back()->with('success', 'Room deleted.');
    }
}
