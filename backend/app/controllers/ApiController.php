<?php

class ApiController extends \BaseController {

    public function getIndex()
    {
        // access this via: example.com/api
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

    public function postSms()
    {
        die('smsm');
        if( !(isset($_POST["phoneNumber"]) and isset($_POST["message"])) )
        {
            // not sure why but the below doesnt work, will fix later
//            Response::abort(400, "Bad Request");
        }
        $phoneNumber = $_POST["phoneNumber"];
        $message = $_POST["message"];
    }

}