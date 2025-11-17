<?php

namespace Flarum\Checkin\Listener;

use Flarum\Discussion\Event\Saving;
use Illuminate\Contracts\Events\Dispatcher;

class SaveCheckinType
{
    public function handle(Saving $event)
    {
        if (isset($event->data['attributes']['isCheckinType'])) {
            $event->discussion->is_checkin_type = (bool) $event->data['attributes']['isCheckinType'];
        }
    }
}

