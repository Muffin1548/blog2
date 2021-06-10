<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * Class Comment
 * @package App\Models
 *
 * @mixin Builder
 */
class Comment extends Model
{
    protected $guarded = [];

    public function author(): BelongsTo
    {
        return $this->belongsTo(USER::class,'from_user');
    }

    public function posts(): BelongsTo
    {
        return $this->belongsTo(POST::class, 'on_post');
    }
}
