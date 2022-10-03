<?php

namespace Devvly\Ffl\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Subscription;

class SetFflsDailyCallsAllowedNumberToZero extends Command
{
  protected $signature = 'set-ffls-daily-calls-allowed-number-to-zero';
  protected $description = 'set ffls daily calls allowed number to zero';

  public function handle()
  {
    $this->comment('Set all ffls subscriptions to zero');
    $set_all_ffls_subscriptions_to_zero = $this->setAllFflsSubscriptionsToZero();
    $this->comment('all ffls subscriptions has been set to zero');

  }
  protected function setAllFflsSubscriptionsToZero()
  {
    $subscriptions=Subscription::where('package','like','%Ffl%')->get();
    $this->output->progressStart(sizeof($subscriptions));
    foreach ($subscriptions as $subscription){
      $subscription->calls_number=0;
      $subscription->save();
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();
  }

}
