<?php

namespace Flarum\Checkin\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\UserSerializer;
use Flarum\Api\Serializer\DiscussionSerializer;

class CheckinRecordSerializer extends AbstractSerializer
{
    protected $type = 'checkin-records';

    protected function getDefaultAttributes($record)
    {
        return [
            'checkinDate' => $this->formatDate($record->checkin_date),
            'photoUrl' => $record->photo_url,
            'note' => $record->note,
            'createdAt' => $this->formatDate($record->created_at),
        ];
    }

    protected function user($record)
    {
        return $this->hasOne($record, UserSerializer::class, 'user_id');
    }

    protected function discussion($record)
    {
        return $this->hasOne($record, DiscussionSerializer::class, 'discussion_id');
    }
}

