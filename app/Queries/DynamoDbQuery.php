<?php

namespace App\Queries;

use Aws\Sdk;
use Aws\DynamoDb\Marshaler;
use Aws\DynamoDb\Exception\DynamoDbException;

class DynamoDbQuery
{

	protected $sdk;

	protected $dynamodb;

	protected $marshaler;

    protected $params = [
        'TableName' => 'Movies',
        'KeyConditionExpression' => '#yr = :yyyy'
    ];

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

        $this->dynamodb = $this->sdk->createDynamoDb();

        $this->marshaler = new Marshaler();
    }

    private function prepare(array $params)
    {
    	foreach ($params as $key => $value) {
    		if($key === 'ExpressionAttributeValues') {
				$this->params[$key] = $this->marshaler->marshalItem($value);
				break;
    		}

    		$this->params[$key] = $value;
    	}

    }

	/**
     * Fetch all relevant movies from 1985.
     *
     * @param  string|null  $channel
     * @return DynamoDbModel
     */
	public function get($params = [])
	{
        try {
        	$this->prepare($params);

            $result = $this->dynamodb->query($this->params);

            foreach ($result['Items'] as $movie) {
                echo '<pre>';
                echo $this->marshaler->unmarshalValue($movie['year']) . ': ' .
                    $this->marshaler->unmarshalValue($movie['title']) . "\n";
            }

        } catch (DynamoDbException $e) {
            echo "Unable to query:\n";
            echo $e->getMessage() . "\n";
        }
	}
}