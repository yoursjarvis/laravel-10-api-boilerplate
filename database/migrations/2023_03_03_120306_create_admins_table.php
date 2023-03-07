<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255)->index();
            $table->string('middle_name', 255)->index()->nullable();
            $table->string('last_name', 255)->index();
            $table->string('username', 16)->index()->unique();
            $table->string('personal_email', 100)->index()->unique();
            $table->string('company_email', 100)->index()->unique()->comment('this email address provided by the company')->nullable();
            $table->string('country_calling_code')->index();
            $table->string('mobile_number')->index()->unique();
            $table->string('alternate_country_calling_code')->index()->comment('this field is used to store alternate mobile number\'s country code')->nullable();
            $table->string('alternate_mobile_number')->index()->unique()->nullable();
            $table->boolean('is_active')->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('avatar')->nullable();
            $table->longText('meta')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->index('created_by');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->index('updated_by');

            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->index('deleted_by');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
