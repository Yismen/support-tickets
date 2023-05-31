<?php

namespace Dainsys\Support\Console\Commands;

use Illuminate\Console\Command;
use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\TicketsExpiredMail;
use Dainsys\Support\Services\RecipientsService;

class SendTicketsExpiredReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:send-tickets-expired-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a report with the tickets that are expired!';

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
    public function handle(RecipientsService $recipientsService): int
    {
        $tickets = Ticket::query()
            ->incompleted()
            ->expired()
            ->orderBy('expected_at', 'ASC')
            ->with([
                'subject',
                'owner',
                'agent',
                'department',
            ])
            ->get();

        Mail::to($recipientsService->superAdmins()->allDepartmentAdmins()->get())
            ->queue(new TicketsExpiredMail($tickets));

        $this->info("Report Sent with {$tickets->count()} tickets");

        return 0;
    }
}
