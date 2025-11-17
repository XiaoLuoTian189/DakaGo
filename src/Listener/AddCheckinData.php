<?php

namespace Flarum\Checkin\Listener;

use Flarum\Api\Serializer\DiscussionSerializer;

class AddCheckinData
{
    public function __invoke(DiscussionSerializer $serializer, $discussion, array $attributes): array
    {
        $attributes['isCheckinType'] = (bool) $discussion->is_checkin_type;
        
        return $attributes;
    }
}

