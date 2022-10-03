<?php

namespace Devvly\Zanders\Console\Commands;

use Devvly\Zanders\Jobs\ProcessTmpQueue;
use Illuminate\Console\Command;

class TmpQueue extends Command
{
    protected $signature = 'tmp:queue';

    public function handle()
    {
        ProcessTmpQueue::dispatch()->onConnection('database')->onQueue('tmp_queue');
    }
}