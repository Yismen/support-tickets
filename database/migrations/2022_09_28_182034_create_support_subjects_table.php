<?php

use Dainsys\Support\Models\Subject;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Dainsys\Support\Enums\TicketPrioritiesEnum;

class CreateSupportSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(resolve(Subject::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->unique();
            $table->integer('priority')->default(TicketPrioritiesEnum::Normal->value); // normal, medium, high
            $table->foreignIdFor(Department::class)->constrained(resolve(Department::class)->getTable());
            $table->text('description')->nullable();
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
        Schema::dropIfExists(resolve(Subject::class)->getTable());
    }
}
