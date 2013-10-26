<?php

class ApiController extends \BaseController {

    public function getIndex()
    {
        // access this via: example.com/
        $response_data = array(
            'test' => 'test',
            'test2' => 'test2',
        );

        return Response::json($response_data);
    }

    public function postProfile()
    {
        // accessed via data POSTED to example.com/profile
    }

}