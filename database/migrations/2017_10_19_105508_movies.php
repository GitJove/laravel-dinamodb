<?php

use Aws\DynamoDb\Exception\DynamoDbException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Movies extends Migration
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
            'TableName' => 'Movies',
            'KeySchema' => [
                [
                    'AttributeName' => 'year',
                    'KeyType' => 'HASH'  //Partition key
                ],
                [
                    'AttributeName' => 'title',
                    'KeyType' => 'RANGE'  //Sort key
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'year',
                    'AttributeType' => 'N'
                ],
                [
                    'AttributeName' => 'title',
                    'AttributeType' => 'S'
                ],

            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
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
