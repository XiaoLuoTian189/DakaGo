<?php

namespace Flarum\Checkin\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Checkin\CheckinRecord;
use Flarum\Http\RequestUtil;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListCheckinRecordsController extends AbstractListController
{
    public $serializer = \Flarum\Checkin\Api\Serializer\CheckinRecordSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);
        $discussionId = $request->getQueryParams()['filter']['discussionId'] ?? null;

        $query = CheckinRecord::query();

        if ($discussionId) {
            $query->where('discussion_id', $discussionId);
        }

        return $query->orderBy('checkin_date', 'desc')->get();
    }
}

