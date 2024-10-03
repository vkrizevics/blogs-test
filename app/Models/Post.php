<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtFormatted()
    {
        $converter = DateTime::createFromFormat('Y-m-d H:i:s', $this->created_at);

        if ($converter) {
            $ret = $converter->format('d.m.Y. H:i');
        } else {
            $ret = $this->created_at;
        }

        return $ret;
    }
}
