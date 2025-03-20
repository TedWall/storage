<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Throwable;

class UpdateUserBalanceJob implements ShouldQueue
{
    public $userId;
    public $amount;
    public $timeout = 60;
    public $tries = 3;
    public function __construct($userId, $amount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
    }
    public function handle():
    {
        $lockKey = "update_balance_user_{$this->userId}";
      
        $lock = Cache::lock($lockKey, 10);

        if ($lock->get()) {
            try {
                $user = User::findOrFail($this->userId);
                $user->balance += $this->amount;
                $user->save();
                $lock->release();
            } catch (Throwable $e) {
                $lock->release();
                throw $e;
            }
        }
    }
}
