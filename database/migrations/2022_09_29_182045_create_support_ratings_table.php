<?php

use Dainsys\Support\Models\Rating;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Dainsys\Support\Enums\TicketRatingsEnum;
use Illuminate\Database\Migrations\Migration;

class CreateSupportRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(resolve(Rating::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(resolve(User::class)->getTable());
            $table->foreignIdFor(Ticket::class)->constrained(resolve(Ticket::class)->getTable());
            $table->integer('score')->default(TicketRatingsEnum::MeetsExpectations->value);
            $table->text('comment')->nullable();

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
        Schema::dropIfExists(resolve(Rating::class)->getTable());
    }
}
