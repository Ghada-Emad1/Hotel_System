<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class ReservationController extends Controller
{
    public function myReservations(Request $request)
    {
        $perPage = $request->input('per_page', 6);
        $page = $request->input('page', 1);

        $reservations = Reservation::where('client_id', auth()->id())
            ->with('room:id,number')
            ->select('id', 'accompany_number', 'room_id', 'paid_price')
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        return Inertia::render('Clients/MyReservations', [
            'reservations' => $reservations
        ]);
    }
    public function availableRooms(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        $page = $request->input('page', 1);

        $availableRooms = Room::whereDoesntHave('reservations')
            ->select('id', 'number', 'capacity', 'price')
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        return Inertia::render('Clients/MakeReservation', [
            'rooms' => $availableRooms
        ]);
    }
    public function bookRoom($roomId)
    {
        $room = Room::findOrFail($roomId);

        return Inertia::render('Clients/BookRoom', [
            'room' => $room
        ]);
    }

    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        try {
         
            session([
                'room_id' => $request->room_id,
                'accompany_number' => $request->accompany_number,
                'paid_price' => $request->amount / 100,
            ]);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => ['name' => 'Room Booking'],
                        'unit_amount' => $request->amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('client/payment-success'),
                'cancel_url' => url('client.payment.cancelled'),
            ]);

            return response()->json(['checkoutUrl' => $session->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    
    public function paymentSuccess(Request $request)
    {
        
        \Log::info('Session Data:', [
            'room_id' => session('room_id'),
            'accompany_number' => session('accompany_number'),
            'paid_price' => session('paid_price'),
        ]);

       
        $roomId = session('room_id');
        $accompanyNumber = session('accompany_number');
        $paidPrice = session('paid_price');

        if (!$roomId || !$accompanyNumber || !$paidPrice) {
            return redirect()->route('client.reservations')->with('error', 'Invalid reservation data.');
        }

        
        $reservation = Reservation::create([
            'client_id' => auth()->id(),
            'room_id' => $roomId,
            'accompany_number' => $accompanyNumber,
            'paid_price' => $paidPrice,
        ]);

        
        session()->forget(['room_id', 'accompany_number', 'paid_price']);

        return Inertia::render('Clients/PaymentSuccess', [
            'message' => 'Payment was successful! Your reservation has been confirmed.',
            'reservation' => $reservation
        ]);
    }



   
    public function paymentCancelled()
    {
        return Inertia::render('Clients/PaymentCancelled', [
            'message' => 'Payment was cancelled. You can try again later.'
        ]);
    }
}
