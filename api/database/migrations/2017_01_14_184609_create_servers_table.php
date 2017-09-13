<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            /**
             * Applications format:
             * [{
             *      "name":"Nginx",
             *      "version": "1.12",
             *      "vulnerabilities" : ["CVE 2001","CVE 2002",...],
             * },
             * ...
             * ]
             */
            $table->text('applications')->nullable();
            $table->boolean('has_vulnerabilities')->default(0);
            $table->text('headers');
            $table->timestamps();

            /**
             * Severity is defined by the most dangerous CVE found.
             */
            $table->string('severity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('servers');
    }
}
