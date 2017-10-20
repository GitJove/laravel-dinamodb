<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Aws\DynamoDb\Exception\DynamoDbException;

class Dinamodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function up()
    {
        $sdk = new Aws\Sdk([
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'endpoint'   => 'http://localhost:8000',
            'region'   => 'us-west-2',
            'version'  => 'latest',
        ]);

        $dynamodb = $sdk->createDynamoDb();

        $params = [
            'TableName' => 'composite_test_model',
            'KeySchema' => [
                [ // Required HASH type attribute
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH',
                ],
                [
                    'AttributeName' => 'id2',
                    'KeyType' => 'RANGE',
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'id2',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'count',
                    'AttributeType' => 'N'
                ],

            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 1,
                'WriteCapacityUnits' => 1
            ],
            'GlobalSecondaryIndexes' => [
                [
                    'IndexName' => 'count_index',
                    'KeySchema' => [
                        [ // Required HASH type attribute
                            'AttributeName' => 'count',
                            'KeyType' => 'HASH',
                        ]
                    ],
                    'Projection' => [ // attributes to project into the index
                        'ProjectionType' => 'ALL', // (ALL | KEYS_ONLY | INCLUDE)
                    ],
                    'ProvisionedThroughput' => [ // throughput to provision to the index
                        'ReadCapacityUnits' => 1,
                        'WriteCapacityUnits' => 1,
                    ],
                ],
                [
                    'IndexName' => 'id_count_index',
                    'KeySchema' => [
                        [ // Required HASH type attribute
                            'AttributeName' => 'id',
                            'KeyType' => 'HASH',
                        ],
                        [ // Required HASH type attribute
                            'AttributeName' => 'count',
                            'KeyType' => 'RANGE',
                        ]
                    ],
                    'Projection' => [ // attributes to project into the index
                        'ProjectionType' => 'ALL', // (ALL | KEYS_ONLY | INCLUDE)
                    ],
                    'ProvisionedThroughput' => [ // throughput to provision to the index
                        'ReadCapacityUnits' => 1,
                        'WriteCapacityUnits' => 1,
                    ],
                ],
            ]
        ];

        try {
            $result = $dynamodb->createTable($params);
            echo 'Created table.  Status: ' .
                $result['TableDescription']['TableStatus'] ."\n";

        } catch (DynamoDbException $e) {
            echo "Unable to create table:\n";
            echo $e->getMessage() . "\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
