<?php

namespace App\Console\Commands;

use App\Mail\CheckCronjob;
use App\Models\Video;
use Illuminate\Console\Command;

class OrderFail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:fail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set order to fail status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //\Mail::to('lymanhha@gmail.com')->send(new CheckCronjob('OrderFail', false, []));
    }
}