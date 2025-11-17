<?php

namespace Flarum\Checkin\Api\Controller;

use Flarum\Checkin\CheckinRecord;
use Flarum\Http\RequestUtil;
use Flarum\Http\Exception\RouteNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;

class DeleteCheckinRecordController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $routeParams = $request->getAttribute('routeParameters');
        $id = $routeParams['id'] ?? null;

        if (!$id) {
            throw new RouteNotFoundException();
        }

        $record = CheckinRecord::findOrFail($id);

        // 只能删除自己的打卡记录
        if ($record->user_id !== $actor->id) {
            throw new \Flarum\Foundation\ValidationException([
                'permission' => ['无权删除此打卡记录']
            ]);
        }

        $record->delete();

        return new EmptyResponse(204);
    }
}

