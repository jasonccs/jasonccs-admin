<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


class CreateRequestLogsTable extends Migration
{
    public function up()
    {
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->unique();
            $table->string('method');
            $table->string('path');
            $table->text('request_data')->nullable();
            $table->text('response_content')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
}
