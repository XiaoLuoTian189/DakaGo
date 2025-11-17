<?php

namespace Flarum\Checkin\Listener;

use Flarum\Discussion\Event\Saving;
use Illuminate\Support\Arr;

class SaveCheckinType
{
    public function handle(Saving $event)
    {
        $data = $event->data ?? [];
        $attributes = Arr::get($data, 'attributes', []);
        
        if (isset($attributes['isCheckinType'])) {
            $event->discussion->is_checkin_type = (bool) $attributes['isCheckinType'];
        }
    }
}

