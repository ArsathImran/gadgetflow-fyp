<?php

namespace App\Console\Commands;

use App\Models\Rental;
use App\Notifications\RentalEndingSoon;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRentalEndingSoonReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-rental-ending-soon-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify customers whose approved rentals are due back within the next 24 hours';

    public function handle(): int
    {
        $windowEnd = now()->addDay();

        $rentals = Rental::query()
            ->with(['user', 'gadget', 'bundle'])
            ->where('status', 'approved')
            ->whereNull('returned_at')
            ->whereNull('ending_soon_notified_at')
            ->get()
            ->filter(function (Rental $rental) use ($windowEnd) {
                if (empty($rental->end_date)) {
                    return false;
                }

                $dueAt = Carbon::parse($rental->end_date)->endOfDay();

                return $dueAt->isFuture() && $dueAt->lte($windowEnd);
            });

        foreach ($rentals as $rental) {
            $rental->user?->notify(new RentalEndingSoon($rental));
            $rental->update(['ending_soon_notified_at' => now()]);
        }

        $this->info("Sent {$rentals->count()} rental ending-soon reminder(s).");

        return self::SUCCESS;
    }
}
