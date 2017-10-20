<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;

class MoviesController extends Controller
{

    public $sdk;

    public $tableName = 'Movies';

    public function __construct()
    {
        $this->sdk = new Sdk([
            'credentials' => [
                'key' => '',
                'secret' => '',
            ],
            'endpoint'   => 'http://localhost:8000',
            'region'   => 'us-west-2',
            'version'  => 'latest'
        ]);
    }

    public function getAllMovies()
    {

    }

    public function addAllMovies()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $movies = json_decode(file_get_contents(public_path('data/moviedata.json')), true);

        foreach ($movies as $movie) {
            $year = $movie['year'];
            $title = $movie['title'];
            $info = $movie['info'];

            $json = json_encode([
                'year' => $year,
                'title' => $title,
                'info' => $info
            ]);

            $params = [
                'TableName' => $this->tableName,
                'Item' => $marshaler->marshalJson($json)
            ];

            try {
                $dynamodb->putItem($params);
            } catch (DynamoDbException $e) {
                echo "Unable to add movie:\n";
                echo $e->getMessage() . "\n";
                break;
            }
        }

        echo 'Done';
    }


    public function create()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $year = 2015;
        $title = 'The Big New Movie';

        $item = $marshaler->marshalJson('
            {
                "year": ' . $year . ',
                "title": "' . $title . '",
                "info": {
                    "plot": "Nothing happens at all.",
                    "rating": 0
                }
            }
        ');

        $params = [
            'TableName' => $this->tableName,
            'Item' => $item
        ];

        try {
            $dynamodb->putItem($params);
            echo "Added item: $year - $title\n";

        } catch (DynamoDbException $e) {
            echo "Unable to add item:\n";
            echo $e->getMessage() . "\n";
        }
    }


    public function getItem()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $year = 2015;
        $title = 'The Big New Movie';

        $key = $marshaler->marshalJson('
            {
                "year": ' . $year . ', 
                "title": "' . $title . '"
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key
        ];

        try {
            $result = $dynamodb->getItem($params);
            dd($result["Item"]);

        } catch (DynamoDbException $e) {
            echo "Unable to get item:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function update()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $year = 2015;
        $title = 'The Big New Movie';

        $key = $marshaler->marshalJson('
            {
                "year": ' . $year . ', 
                "title": "' . $title . '"
            }
        ');


        $eav = $marshaler->marshalJson('
            {
                ":r": 5.5 ,
                ":p": "Everything happens all at once.",
                ":a": [ "Larry", "Moe", "Curly" ]
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key,
            'UpdateExpression' =>
                'set info.rating = :r, info.plot=:p, info.actors=:a',
            'ExpressionAttributeValues'=> $eav,
            'ReturnValues' => 'UPDATED_NEW'
        ];

        try {
            $result = $dynamodb->updateItem($params);
            echo "Updated item.\n";
            dd($result['Attributes']);

        } catch (DynamoDbException $e) {
            echo "Unable to update item:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function incrementRating()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $year = 2015;
        $title = 'The Big New Movie';

        $key = $marshaler->marshalJson('
            {
                "year": ' . $year . ', 
                "title": "' . $title . '"
            }
        ');

        $eav = $marshaler->marshalJson('
            {
                ":val": 1
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key,
            'UpdateExpression' => 'set info.rating = info.rating + :val',
            'ExpressionAttributeValues'=> $eav,
            'ReturnValues' => 'UPDATED_NEW'
        ];

        try {
            $result = $dynamodb->updateItem($params);
            echo "Updated item. ReturnValues are:\n";
            dd($result['Attributes']);

        } catch (DynamoDbException $e) {
            echo "Unable to update item:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function conditionally()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $year = 2015;
        $title = 'The Big New Movie';

        $key = $marshaler->marshalJson('
            {
                "year": ' . $year . ', 
                "title": "' . $title . '"
            }
        ');

        $eav = $marshaler->marshalJson('
            {
                ":num": 3
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key,
            'UpdateExpression' => 'remove info.actors[0]',
            'ConditionExpression' => 'size(info.actors) >= :num',
            'ExpressionAttributeValues'=> $eav,
            'ReturnValues' => 'UPDATED_NEW'
        ];

        try {
            $result = $dynamodb->updateItem($params);
            echo "Updated item. ReturnValues are:\n";
            dd($result['Attributes']);

        } catch (DynamoDbException $e) {
            echo "Unable to update item:\n";
            echo $e->getMessage() . "\n";
        }
    }


    public function deleteItem()
    {
        $dynamodb = $this->sdk->createDynamoDb();
        $marshaler = new Marshaler();

        $tableName = 'Movies';

        $year = 2015;
        $title = 'The Big New Movie';

        $key = $marshaler->marshalJson('
            {
                "year": ' . $year . ', 
                "title": "' . $title . '"
            }
        ');

        $eav = $marshaler->marshalJson('
            {
                ":val": 10 
            }
        ');

        $params = [
            'TableName' => $tableName,
            'Key' => $key,
            'ConditionExpression' => 'info.rating <= :val',
            'ExpressionAttributeValues'=> $eav
        ];

        try {
            $dynamodb->deleteItem($params);
            echo "Deleted item.\n";

        } catch (DynamoDbException $e) {
            echo "Unable to delete item:\n";
            echo $e->getMessage() . "\n";
        }
    }
}
