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

        if ($status === 'pickuped' && $validated['first_time'] !== null) {
            // Skip validation for pickuped status with non-null first_time
        } else if ($status === 'pickuped') {
            return response()->json([
                'success' => false,
                'message' => 'New queue must start with waiting status.',
            ], 422);
        }

        if ($status === 'abort') {
            $waitingQueue = QueueData::where('customer_id', $validated['customer_id'])
                ->where('status', 'waiting')
                ->first();

            if ($waitingQueue) {
                $waitingQueue->update([
                    'status' => 'abort',
                    'first_time' => null,
                    'pickup_time' => null,
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $waitingQueue,
                    'message' => 'Queue status updated to abort and cannot be modified further.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching waiting queue found for the provided customer_id.',
                ], 422);
            }
        }

        if (!($status === 'pickuped' && $validated['first_time'] !== null)) {
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

        if ($existing && $status === 'pickuped') {
            $existing->update([
                'status' => 'pickuped',
                'pickup_time' => $validated['pickup_time'] ?? now(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $existing,
                'message' => 'Queue status updated to pickuped.',
            ], 200);
        }

        if ($existing) {
            $existing->update(['status' => 'abort']);
        }

        
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

        // ตรวจสอบกรณีข้อมูลใหม่หมด ต้องไม่มี first_time และ pickup_time
        $isNewQueue = !$existing && !$recentDuplicate && !$duplicatePickup;
        if ($isNewQueue && ($validated['first_time'] !== null || $validated['pickup_time'] !== null)) {
            return response()->json([
                'success' => false,
                'message' => 'New queue must have first_time and pickup_time as null.',
            ], 422);
        }

        // ตรวจสอบกรณีข้อมูลใหม่แต่สถานะเริ่มต้นเป็น pickuped
        if ($status === 'pickuped' && $validated['first_time'] !== null) {
            $waitingQueue = QueueData::where('customer_id', $validated['customer_id'])
                ->where('first_time', $validated['first_time'])
                ->where('status', 'waiting')
                ->first();
            if (!$waitingQueue) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching waiting queue found for the provided customer_id and first_time.',
                ], 422);
            }
        }

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

        $rules = [
            'status' => 'required|in:waiting,pickuped,abort',
        ];

        $validated = $request->validate($rules);

        if ($request->input('status') === 'pickuped') {
            $rules['first_time'] = 'required|date';
            $rules['pickup_time'] = 'required|date';
        }

        $validated = $request->validate($rules);

        $status = $validated['status'];

        if ($status === 'pickuped') {
            if ($queue->status !== 'waiting') {
                return response()->json([
                    'success' => false,
                    'message' => 'Queue is not in waiting status.',
                ], 422);
            }

            if (Carbon::parse($validated['first_time'])->ne($queue->first_time)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid first_time provided.',
                ], 422);
            }

            $queue->pickup_time = Carbon::parse($validated['pickup_time']);
        }

        if ($status === 'abort') {
            $queue->pickup_time = null;
        }
        $queue->status = $status;

        $queue->save();

        return response()->json([
            'success' => true,
            'data' => $queue,
        ]);
    }
}