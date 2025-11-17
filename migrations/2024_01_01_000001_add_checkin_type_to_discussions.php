<?php

use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

return Migration::addColumns(
    'discussions',
    function (Blueprint $table) {
        $table->boolean('is_checkin_type')->default(0);
    }
);

