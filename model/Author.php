<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @package model
 *
 * @property $name
 * @property $allow
 * @property $manual
 * @property $created_at
 * @property $updated_at
 */
class Author extends Model
{
    protected $casts = [
        'allow' => 'boolean',
        'manual' => 'boolean',
    ];
}
