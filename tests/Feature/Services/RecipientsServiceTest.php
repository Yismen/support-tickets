<?php

namespace Dainsys\Support\Tests\Feature\Services;

use App\Models\User;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Services\RecipientsService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientsServiceTest extends TestCase
{
    use RefreshDatabase;

    // is istance of Recipientsservice
    // Chain methods
    // owner()
    // departmentAgents()
    // DepartmentAdmins
    // departmentTeam
    // include or exclude current user
    /** @test */
    public function service_can_be_initialized()
    {
        $service = new RecipientsService();

        $this->assertInstanceOf(RecipientsService::class, $service);
    }

    /** @test */
    public function service_collection_contains_ticket_owner()
    {
        $ticket = Ticket::factory()->createQuietly();
        $service = new RecipientsService();

        $recipients = $service->ofTicket($ticket)->owner()->get();

        $this->assertTrue($recipients->contains($ticket->owner));
    }

    /** @test */
    public function service_collection_contains_super_admin_user()
    {
        $super_admin_user = $this->SupportSuperAdmin();
        $ticket = Ticket::factory()->createQuietly();

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->get();

        $this->assertTrue($recipients->contains($super_admin_user));
    }

    /** @test */
    public function service_collection_contains_departmet_admins()
    {
        $ticket = Ticket::factory()->createQuietly();
        $department_admin = $this->departmentAdmin($ticket->department);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->get();

        $this->assertTrue($recipients->contains($department_admin));
    }

    /** @test */
    public function service_collection_contains_departmet_agents()
    {
        $ticket = Ticket::factory()->createQuietly();
        $user = $this->departmentAgent($ticket->department);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->allDepartmentAgents()
            ->get();

        $this->assertTrue($recipients->contains($user));
    }

    /** @test */
    public function service_collection_contains_ticket_agent()
    {
        $ticket = Ticket::factory()->createQuietly();
        $user = $this->departmentAgent($ticket->department);
        $ticket->assignTo($user);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->agent()
            ->get();

        $this->assertTrue($recipients->contains($user));
    }

    /** @test */
    public function service_collection_can_include_current_user()
    {
        $user = $this->user();
        $this->actingAs($user);
        $ticket = Ticket::factory()->createQuietly(['created_by' => $user->id]);
        config()->set('support.email.include_current_user', true);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->agent()
            ->get();

        $this->assertTrue($recipients->contains($user));
    }

    /** @test */
    public function service_collection_can_exclude_current_user()
    {
        $user = $this->user();
        $this->actingAs($user);
        $ticket = Ticket::factory()->createQuietly(['created_by' => $user->id]);
        config()->set('support.email.include_current_user', false);

        $service = new RecipientsService();
        $recipients = $service
            ->ofTicket($ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->agent()
            ->get();

        $this->assertTrue($recipients->doesntContain($user));
    }
}
