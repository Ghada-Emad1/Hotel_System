<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\Floor;
use App\Models\Reservation;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Room::query()
            ->with('floor:id,name', 'manager:id,name');

        // Apply pagination
        $rooms = $query->paginate($perPage)->withQueryString();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
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

        return redirect()->route('room.index')->with('success', 'Room created successfully.');
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

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
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