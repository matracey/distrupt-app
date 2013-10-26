<?php

class ApiController extends \BaseController {
    public $restful = true;
    private $clickatellAuth = array("session" => null, "retrievalTime" => null);
    const CLICKATELL_API_ID = '3445709';
    const CLICKATELL_API_PASS = '9y8NvTuNu';
    const CLICKATELL_API_USER = 'HACKATHON102';

    public function getIndex()
    {
        // access this via: example.com/api
        $response_data = array(
            'test' => 'test',
            'test2' => 'test2',
        );

        return Response::json($response_data);
    }

    public function postUsers()
    {
        // command line curl:
        // curl -X POST -d 'd=d' http://local.disrupt.me/api/users
        die('post users');
    }

    public function postProfile()
    {
        // accessed via data POSTED to example.com/profile
    }

    public function postSms()
    {
        // die('smsm');
        if( !(isset($_POST["phoneNumber"]) and isset($_POST["message"])) )
        {
           return Response::make("400: Bad Request", 400);
        }else{
            $phoneNumbers = explode(",", $_POST["phoneNumber"]);
            $message = $_POST["message"];

            // var_dump($phoneNumber, $message, );
            // exit();

            $this->clickatellAuth();

            foreach ($phoneNumbers as $phoneNumber) {
                // http://api.clickatell.com/http/sendmsg?session_id=xxxx&to=xxxx&text=xxxx
                $handler = curl_init();
                curl_setopt_array($handler, array(
                    CURLOPT_URL => "http://api.clickatell.com/http/sendmsg",
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POSTFIELDS => array(
                        "session_id" => $this->clickatellAuth["session"],
                        "to" => $phoneNumber,
                        "text" => $message
                        )
                    ));

                $result = curl_exec($handler);

                var_dump($result);
            }
        }
    }

    private function clickatellAuth()
    {
        if( !(isset($clickatellAuth["session"]) and isset($clickatellAuth["retrievalTime"])) )
        {
            /*
             * If no session or retieval time is set, or if it has expired, then we must get a new session.
             */

            // echo "http://api.clickatell.com/http/auth?api_id=".$this::CLICKATELL_API_ID."&user=".$this::CLICKATELL_API_USER."&password=".$this::CLICKATELL_API_PASS;

            $handler = curl_init();
            curl_setopt_array($handler, array(
                CURLOPT_URL => "http://api.clickatell.com/http/auth?api_id=".$this::CLICKATELL_API_ID."&user=".$this::CLICKATELL_API_USER."&password=".$this::CLICKATELL_API_PASS,
                CURLOPT_POST => FALSE,
                CURLOPT_RETURNTRANSFER => TRUE
                // CURLOPT_POSTFIELDS => array(
                    // 'phoneNumber' => "09090"
                    // 'message' => "Message"
                    // );
                ));

            $result = curl_exec($handler);

            if(stristr($result, "OK: ") != FALSE)
            {
                $this->clickatellAuth["session"] = substr($result, 4);
                $this->clickatellAuth["retrievalTime"] = date("d/m/Y H:i:s");
                // var_dump($this->clickatellAuth);
            }
        }
    }
}