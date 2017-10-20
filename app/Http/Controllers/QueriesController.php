<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;

class QueriesController extends Controller
{
    public function yearCondition()
    {
        $sdk = new Sdk([
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'endpoint'   => 'http://localhost:8000',
            'region'   => 'us-west-2',
            'version'  => 'latest'
        ]);

        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $eav = $marshaler->marshalJson('
            {
                ":yyyy": 1985 
            }
        ');

        $params = [
            'TableName' => $tableName,
            'KeyConditionExpression' => '#yr = :yyyy',
            'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
            'ExpressionAttributeValues'=> $eav
        ];

        echo "Querying for movies from 1985.\n";

        try {
            $result = $dynamodb->query($params);

            echo "Query succeeded.\n";

            foreach ($result['Items'] as $movie) {
                echo '<pre>';
                echo $marshaler->unmarshalValue($movie['year']) . ': ' .
                    $marshaler->unmarshalValue($movie['title']) . "\n";
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function yearCondition1()
    {
        $sdk = new Sdk([
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'endpoint'   => 'http://localhost:8000',
            'region'   => 'us-west-2',
            'version'  => 'latest'
        ]);

        $dynamodb = $sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $eav = $marshaler->marshalJson('
            {
                ":yyyy":1992,
                ":letter1": "A",
                ":letter2": "L"
            }
        ');

        $params = [
            'TableName' => $tableName,
            'ProjectionExpression' => '#yr, title, info.genres, info.actors[0]',
            'KeyConditionExpression' =>
                '#yr = :yyyy and title between :letter1 and :letter2',
            'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
            'ExpressionAttributeValues'=> $eav
        ];

        echo "Querying for movies from 1992 - titles A-L, with genres and lead actor\n";

        try {
            $result = $dynamodb->query($params);

            echo "Query succeeded.\n";

            foreach ($result['Items'] as $i) {
                $movie = $marshaler->unmarshalItem($i);
                print $movie['year'] . ': ' . $movie['title'] . ' ... ';

                foreach ($movie['info']['genres'] as $gen) {
                    print $gen . ' ';
                }
                echo '<pre>';
                echo ' ... ' . $movie['info']['actors'][0] . "\n";
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function scan()
    {
        $sdk = new Sdk([
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'endpoint'   => 'http://localhost:8000',
            'region'   => 'us-west-2',
            'version'  => 'latest'
        ]);

        $dynamodb = $sdk->createDynamoDb();

        $marshaler = new Marshaler();

        //Expression attribute values
        $eav = $marshaler->marshalJson('
            {
                ":start_yr": 1950
            }
        ');

        $params = [
            'TableName' => 'Movies',
            'ProjectionExpression' => '#yr, title, info.rating',
            'FilterExpression' => '#yr <= :start_yr ',
            'ExpressionAttributeNames'=> [ '#yr' => 'year' ],
            'ExpressionAttributeValues'=> $eav,
            'ScanIndexForward' => false
        ];

        echo "Scanning Movies table.\n";

        try {
            while (true) {
                $result = $dynamodb->scan($params);

                dd($result);
                foreach ($result['Items'] as $i) {
                    $movie = $marshaler->unmarshalItem($i);
                    echo '<pre>';
                    echo $movie['year'] . ': ' . $movie['title'];
                    echo ' ... ' . $movie['info']['rating']
                        . "\n";
                }

                if (isset($result['LastEvaluatedKey'])) {
                    $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
                } else {
                    break;
                }
            }

        } catch (DynamoDbException $e) {
            echo "Unable to scan:\n";
            echo $e->getMessage() . "\n";
        }
    }
}
