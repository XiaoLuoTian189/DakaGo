<?php

use Flarum\Database\Migration;

return Migration::createTable(
    'checkin_records',
    [
        'id' => ['type' => 'integer', 'unsigned' => true, 'autoIncrement' => true],
        'discussion_id' => ['type' => 'integer', 'unsigned' => true],
        'user_id' => ['type' => 'integer', 'unsigned' => true],
        'checkin_date' => ['type' => 'date'],
        'photo_url' => ['type' => 'string', 'length' => 255],
        'note' => ['type' => 'text', 'nullable' => true],
        'created_at' => ['type' => 'datetime'],
        'updated_at' => ['type' => 'datetime'],
    ],
    [
        'PRIMARY KEY (`id`)',
        'KEY `discussion_id` (`discussion_id`)',
        'KEY `user_id` (`user_id`)',
        'KEY `checkin_date` (`checkin_date`)',
        'UNIQUE KEY `unique_daily_checkin` (`discussion_id`, `user_id`, `checkin_date`)',
    ]
);

