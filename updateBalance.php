<?php
use App\Jobs\UpdateUserBalanceJob;
use Illuminate\Support\Facades\Log;

public function updateBalance(int $userId, float $amount)
{
    try {
        UpdateUserBalanceJob::dispatch($userId, $amount);
        return response()->json(['message' => 'Balance update queued'], 200);
    } catch (\Throwable $e) {
        Log::error("Queue dispatch failed: " . $e->getMessage());
        return response()->json(['error' => 'Queue dispatch failed'], 500);
    }
}
