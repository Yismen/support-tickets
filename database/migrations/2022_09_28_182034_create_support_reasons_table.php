<?php

use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(supportTableName('reasons'), function (Blueprint $table) {
            $table->id();
            $table->string('name', 500)->unique();
            $table->foreignIdFor(Department::class);
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
        Schema::dropIfExists(supportTableName('reasons'));
    }
}
