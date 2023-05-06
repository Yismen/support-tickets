<?php

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Migrations\Migration;
use Dainsys\Support\Enums\DepartmentRolesEnum;

class CreateSupportDepartmentRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(resolve(DepartmentRole::class)->getTable(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->unique();
            $table->foreignIdFor(Department::class)->constrained(resolve(Department::class)->getTable());
            $table->string('role')->default(DepartmentRolesEnum::Agent->value);

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
        Schema::dropIfExists(resolve(DepartmentRole::class)->getTable());
    }
}
