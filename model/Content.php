<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @package model
 *
 * @property $parent_id
 * @property $type
 * @property $path
 * @property $timestamp
 * @property $size
 * @property $dirname
 * @property $basename
 * @property $extension
 * @property $regex
 * @property $candidate
 * @property $author_id
 * @property $enabled
 * @property $multiple
 * @property $forecast
 * @property $created_at
 * @property $updated_at
 */
class Content extends Model
{
    protected $casts = [
        'regex' => 'array',
        'candidate' => 'array',
        'enabled' => 'boolean',
        'multiple' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
