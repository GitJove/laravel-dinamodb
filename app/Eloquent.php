<?php

namespace App;

use BaoPham\DynamoDb\DynamoDbModel;

class Eloquent extends DynamoDbModel
{
    protected $table = 'composite_test_model';

    protected $primaryKey = ['id'];
    protected $compositeKey = ['id', 'id2'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id2',
        'count',
        'count2'
    ];

    protected $dynamoDbIndexKeys = [
        'count_index' => [
            'hash' => 'count'
        ],
        'id_count_index' => [
            'hash' => 'id',
            'range' => 'count'
        ]
    ];
}
