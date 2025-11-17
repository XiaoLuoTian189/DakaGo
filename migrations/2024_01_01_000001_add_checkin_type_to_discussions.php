<?php

use Flarum\Database\Migration;

return Migration::addColumns(
    'discussions',
    [
        'is_checkin_type' => ['type' => 'boolean', 'default' => 0],
    ]
);

