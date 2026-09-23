<?php

namespace App\Console\Commands;

use App\Models\PurchaseHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired subscriptions and notify expiring ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('subscriptions:check started at '.now());

        $expired = PurchaseHistory::where('status', config('static_data.status.ACTIVE'))
        ->where('ends_at', '<=', now())
        ->get();
Log::info('subscriptions:check found '.$expired->count().' expired subscriptions.');
        foreach ($expired as $sub) {
            $sub->update([
                'status' => config('static_data.status.EXPIRED'),
            ]);
        }
        Log::info('subscriptions:check finished at '.now());
        $this->info('Expired: '.$expired->count());
        return Command::SUCCESS;
    }
}
