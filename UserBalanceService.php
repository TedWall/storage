namespace App\Services;

use App\Jobs\UpdateUserBalanceJob;
use Illuminate\Support\Facades\Cache;

class UserBalanceService
{
    public function increase(int $userId, float $amount): bool
    {
        $key = 'user_balance_update_lock:' . $userId;
        $lockAcquired = Cache::add($key, true, 30);
        if (! $lockAcquired) {
            return false;
        }

        UpdateUserBalanceJob::dispatch($userId, $amount)->onQueue('user-balance');

        return true;
    }
}
