<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::createTable(
    'checkin_records',
    function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('discussion_id');
        $table->unsignedInteger('user_id');
        $table->date('checkin_date');
        $table->string('photo_url', 255);
        $table->text('note')->nullable();
        $table->timestamps();

        $table->index('discussion_id');
        $table->index('user_id');
        $table->index('checkin_date');
        $table->unique(['discussion_id', 'user_id', 'checkin_date'], 'unique_daily_checkin');
    }
);

