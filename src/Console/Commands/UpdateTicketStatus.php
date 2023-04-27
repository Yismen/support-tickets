<?php

namespace Dainsys\Support\Console\Commands;

use Illuminate\Console\Command;
use Dainsys\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Jobs\UpdateTicketStatusJob;

class UpdateTicketStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:update-ticket-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of the non-completed tickets!';

    protected Collection $tickets;

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
     * @return int
     */
    public function handle()
    {
        $this->tickets = Ticket::incompleted()->get();
        
        $this->tickets->each(function(Ticket $ticket) {
            $ticket->touch();
        });

        $this->info("Successfully updated {$this->tickets->count()} tickets");
    }
}
