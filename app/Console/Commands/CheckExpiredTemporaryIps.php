<?php

namespace App\Console\Commands;

use App\Models\EmployeeLoginIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckExpiredTemporaryIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-temporary-ips';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $expiredIps = EmployeeLoginIp::where('is_temporary', true)
        ->where('allowed_until', '<', now())
        ->get();

    foreach ($expiredIps as $ip) {

        DB::table('sessions')
            ->where('user_id', $ip->employee->user_id)
            ->delete();
    }
}

}
