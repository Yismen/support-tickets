<?php

use Dainsys\Support\Models\Subject;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Dainsys\Support\Enums\TicketStatusesEnum;
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
        Schema::create(resolve(Ticket::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(Department::class)->constrained(resolve(Department::class)->getTable());
            $table->foreignIdFor(Subject::class)->constrained(resolve(Subject::class)->getTable());
            $table->text('description');
            $table->foreignIdFor(User::class, 'assigned_to')->nullable();
            $table->dateTime('assigned_at')->nullable();
            $table->dateTime('expected_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('reference', 50)->nullable();
            $table->text('image')->nullable();
            $table->integer('status')->default(TicketStatusesEnum::Pending->value);

            $table->softDeletes();

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
        Schema::dropIfExists(resolve(Ticket::class)->getTable());
    }
}
