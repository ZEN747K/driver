<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\QueueData;
use Illuminate\Http\Request;
use Carbon\Carbon;


class CustomerQueueController extends Controller
{
    /**
     * Create a new queue record
     */
    public function store(Request $request)
    {
        // Convert empty strings to null so validation passes for optional dates
        if ($request->input('first_time') === '') {
            $request->merge(['first_time' => null]);
        }
        if ($request->input('pickup_time') === '') {
            $request->merge(['pickup_time' => null]);
        }

        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'customer_phone' => 'required|string',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'first_time' => 'nullable|date',
            'status' => 'sometimes|in:waiting,pickuped,abort',
            'pickup_time' => 'nullable|date',
        ]);

        $status = $validated['status'] ?? 'waiting';

        $firstTime = isset($validated['first_time'])
            ? Carbon::parse($validated['first_time'])
            : Carbon::now();

        if ($status === 'pickuped') {
            return response()->json([
                'success' => false,
                'message' => 'New queue must start with waiting status.',
            ], 422);
        }else if ($status === 'abort') {
            return response()->json([
                'success' => false,
                'message' => 'New queue must start with waiting status.',
            ], 422);
        }

        // Reject new queue if similar record exists within five minutes
        $recentDuplicate = QueueData::where('customer_id', $validated['customer_id'])
            ->where('customer_phone', $validated['customer_phone'])
            ->where('pickup_location', $validated['pickup_location'])
            ->where('destination', $validated['destination'])
            ->whereBetween('first_time', [
                $firstTime->copy()->subMinutes(5),
                $firstTime->copy()->addMinutes(5),
            ])
            ->first();

        if ($recentDuplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Queue already exists with the same information within 5 minutes.',
            ], 422);
        }

        // Reject duplicate pickuped queue for same customer and locations
        $duplicatePickup = QueueData::where('customer_id', $validated['customer_id'])
            ->where('customer_phone', $validated['customer_phone'])
            ->where('pickup_location', $validated['pickup_location'])
            ->where('destination', $validated['destination'])
            ->where('status', 'pickuped')
            ->first();

        if ($duplicatePickup) {
            return response()->json([
                'success' => false,
                'message' => 'Queue already pickuped with the same information.',
            ], 422);
        }

        // Abort previous waiting queue with same customer and locations
        $existing = QueueData::where('customer_id', $validated['customer_id'])
            ->where('pickup_location', $validated['pickup_location'])
            ->where('destination', $validated['destination'])
            ->where('status', 'waiting')
            ->first();

        if ($existing) {
            $existing->update(['status' => 'abort']);
        }

        $firstTime = $validated['first_time'] ?? now();
        $pickupTime = null;
        if ($status === 'pickuped') {
            $pickupTime = $validated['pickup_time'] ?? now();
        }

        $queue = QueueData::create([
            'customer_id' => $validated['customer_id'],
            'customer_phone' => $validated['customer_phone'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'first_time' => $firstTime,
            'status' => $status,
            'pickup_time' => $pickupTime,
        ]);

        return response()->json([
            'success' => true,
            'data' => $queue,
        ], 201);
    }

    /**
     * Update queue status
     */
    public function update(Request $request, $id)
    {
        $queue = QueueData::findOrFail($id);

        if ($queue->status === 'pickuped') {
            return response()->json([
                'success' => false,
                'message' => 'Queue already pickuped and cannot be modified.',
            ], 422);
        }

        $validated = $request->validate([
            'status' => 'required|in:waiting,pickuped,abort',
        ]);

        $queue->status = $validated['status'];

        if ($validated['status'] === 'pickuped') {
            $queue->pickup_time = now();
        }

        if ($validated['status'] === 'abort') {
            $queue->pickup_time = null;
        }

        $queue->save();

        return response()->json([
            'success' => true,
            'data' => $queue,
        ]);
    }
}