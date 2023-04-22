<?php

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create(supportTableName('department_roles'), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->unique();
            $table->foreignIdFor(Department::class);
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
        Schema::dropIfExists(supportTableName('department_roles'));
    }
}
