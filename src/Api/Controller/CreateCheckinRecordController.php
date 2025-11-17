<?php

namespace Flarum\Checkin\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\Checkin\CheckinRecord;
use Flarum\Http\RequestUtil;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateCheckinRecordController extends AbstractCreateController
{
    public $serializer = \Flarum\Checkin\Api\Serializer\CheckinRecordSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $data = Arr::get($request->getParsedBody(), 'data', []);
        $attributes = Arr::get($data, 'attributes', []);

        $discussionId = Arr::get($attributes, 'discussionId');
        $checkinDate = Arr::get($attributes, 'checkinDate', date('Y-m-d'));
        $photoUrl = Arr::get($attributes, 'photoUrl');
        $note = Arr::get($attributes, 'note');

        // 检查是否已经打卡
        $existing = CheckinRecord::where('discussion_id', $discussionId)
            ->where('user_id', $actor->id)
            ->where('checkin_date', $checkinDate)
            ->first();

        if ($existing) {
            throw new \Flarum\Foundation\ValidationException([
                'checkin' => ['今天已经打卡过了，请明天再试']
            ]);
        }

        $record = CheckinRecord::create([
            'discussion_id' => $discussionId,
            'user_id' => $actor->id,
            'checkin_date' => $checkinDate,
            'photo_url' => $photoUrl,
            'note' => $note,
        ]);

        return $record;
    }
}

