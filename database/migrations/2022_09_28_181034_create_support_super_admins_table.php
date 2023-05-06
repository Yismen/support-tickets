<?php

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportSuperAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(resolve(SuperAdmin::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained(resolve(User::class)->getTable());
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
        Schema::dropIfExists(resolve(SuperAdmin::class)->getTable());
    }
}
