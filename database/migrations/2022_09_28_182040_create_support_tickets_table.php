<?php

use Dainsys\Support\Models\Reason;
use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Dainsys\Support\Enums\TicketStatus;
use Dainsys\Support\Enums\TicketPriority;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(supportTableName('tickets'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(Department::class);
            $table->foreignIdFor(Reason::class);
            $table->text('description');
            $table->foreignIdFor(User::class, 'assigned_to')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->integer('status')->default(TicketStatus::Pending->value); // pending, in_progress, on_hold, complete
            $table->dateTime('expected_at')->nullable();
            $table->integer('priority')->default(TicketPriority::Normal->value); // normal, medium, high
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(supportTableName('tickets'));
    }
}
