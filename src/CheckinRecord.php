<?php

namespace Flarum\Checkin;

use Flarum\Database\AbstractModel;
use Flarum\User\User;
use Flarum\Discussion\Discussion;

class CheckinRecord extends AbstractModel
{
    protected $table = 'checkin_records';

    protected $dates = ['created_at', 'updated_at', 'checkin_date'];

    protected $fillable = ['discussion_id', 'user_id', 'checkin_date', 'photo_url', 'note'];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

