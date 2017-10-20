<?php

namespace App\Http\Controllers;

use App\Testing;
use Illuminate\Http\Request;
use App\Queries\DynamoDbQuery;

class DynamoDbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $params = [
            'ExpressionAttributeNames' => [ '#yr' => 'year'],
            'ExpressionAttributeValues' => [":yyyy" => 2000]
        ];


        return $dynamodb = (new DynamoDbQuery)->get($params);

        // return view('community.links', compact('dynamodb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function show(Testing $testing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function edit(Testing $testing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testing $testing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Testing  $testing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testing $testing)
    {
        //
    }
}
