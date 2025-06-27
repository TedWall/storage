namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserBalanceJob implements ShouldQueue,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $userId;
    public float $amount;
    
    public function __construct(int $userId, float $amount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
    }

    public function uniqueId(): string
    {
        return 'user-balance-' . $this->userId;
    }

    public function handle(): void
    {
        $user = User::findOrFail($this->userId);

        \DB::transaction(function () use ($user) {
            $user->balance += $this->amount;
            $user->save();
        });
    }
}
