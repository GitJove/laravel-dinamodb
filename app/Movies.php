<?php

namespace App;

use BaoPham\DynamoDb\DynamoDbModel;

class Movies extends DynamoDbModel
{
    protected $table = 'Movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'created_at_uuid',
        'count'
    ];
}
