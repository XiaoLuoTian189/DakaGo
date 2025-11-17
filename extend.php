<?php

namespace Flarum\Checkin;

use Flarum\Extend;
use Flarum\Checkin\Api\Controller;
use Flarum\Checkin\Api\Serializer\CheckinRecordSerializer;
use Flarum\Checkin\Listener\SaveCheckinType;
use Flarum\Checkin\Listener\AddCheckinData;
use Flarum\Discussion\Event\Saving;

$extensions = [
    (new Extend\Locales(__DIR__.'/locale')),

    (new Extend\Routes('api'))
        ->post('/checkin-records', 'checkin.records.create', Controller\CreateCheckinRecordController::class)
        ->get('/checkin-records', 'checkin.records.index', Controller\ListCheckinRecordsController::class)
        ->delete('/checkin-records/{id}', 'checkin.records.delete', Controller\DeleteCheckinRecordController::class)
        ->post('/checkin-upload', 'checkin.upload', Controller\UploadPhotoController::class),

    (new Extend\ApiSerializer(\Flarum\Api\Serializer\DiscussionSerializer::class))
        ->attributes(AddCheckinData::class)
        ->hasMany('checkinRecords', CheckinRecordSerializer::class),

    (new Extend\Event())
        ->listen(Saving::class, SaveCheckinType::class),

    (new Extend\Model(\Flarum\Discussion\Discussion::class))
        ->relationship('checkinRecords', function ($model) {
            return $model->hasMany(\Flarum\Checkin\CheckinRecord::class, 'discussion_id');
        }),

    (new Extend\Settings())
        ->default('flarum-checkin.max-photos-per-day', 10)
        ->default('flarum-checkin.allowed-file-types', 'jpg,jpeg,png,gif'),
];

// 添加前端资源（如果文件存在）
$forumJs = __DIR__.'/js/dist/forum.js';
$forumCss = __DIR__.'/less/forum.less';
$adminJs = __DIR__.'/js/dist/admin.js';
$adminCss = __DIR__.'/less/admin.less';

if (file_exists($forumJs) || file_exists($forumCss)) {
    $forumExtend = new Extend\Frontend('forum');
    if (file_exists($forumJs)) {
        $forumExtend->js($forumJs);
    }
    if (file_exists($forumCss)) {
        $forumExtend->css($forumCss);
    }
    $extensions[] = $forumExtend;
}

if (file_exists($adminJs) || file_exists($adminCss)) {
    $adminExtend = new Extend\Frontend('admin');
    if (file_exists($adminJs)) {
        $adminExtend->js($adminJs);
    }
    if (file_exists($adminCss)) {
        $adminExtend->css($adminCss);
    }
    $extensions[] = $adminExtend;
}

return $extensions;
