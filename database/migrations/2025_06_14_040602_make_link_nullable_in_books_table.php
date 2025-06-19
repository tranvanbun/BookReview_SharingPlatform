<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeLinkNullableInBooksTable extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('link')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('link')->nullable(false)->change();
        });
    }
}

