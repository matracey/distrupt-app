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
            'test2' => 'testTWO',
        );

        return Response::json($response_data);
    }

    public function postUsers()
    {
        // command line curl:
        // curl -X POST -d 'd=d' http://local.disrupt.me/api/users
        // TODO: get lines, and duration, time @ work. sms/email

        $from_line = $_POST['transport']['directions'][0]['line'];
        $to_line = $_POST['transport']['directions'][1]['line'];
        $duration = $_POST['transport']['duration'];
        $time_at_work = $_POST['timeAtWork'];

        $sms_to = array();
        foreach ($_POST['sms'] as $cell_number) {
            $sms_to[] = $cell_number;
        }
        $sms_s = implode(',', $sms_to);

        $email_to = array();
        foreach ($_POST['email'] as $email_address) {
            $email_to[] = $email_address;
        }
        $email_addresses = implode(',', $email_to);

        $insert = array(
            'user_emails' => $email_addresses,
            'user_sms' => $sms_s,
            'user_time_at_work' => $time_at_work,
            'user_journey_duration' => $duration,
            'user_line_one' => $from_line,
            'user_line_two' => $to_line,
        );

        $cols = array();
        foreach ($insert as $field => $value) {
            $cols[] = $field;
        }
        $colummns = implode(',', $cols);

        // TODO: save this info to DB
        DB::insert('insert into users ('.$colummns.') values (?, ?, ?, ?, ?, ?)', $insert);

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
                        "text" => $message,
                        "mo" => 1,
                        "from" => "447860024233"
                        )
                    ));

                $result = curl_exec($handler);

                var_dump($result);
            }
        }
    }

    public function postReturnMessage()
    {
        // curl -X POST -d 'd=d' http://local.disrupt.me/api/returnMessage
        // curl -X POST -d 'd=d' http://martin.disrupt.local/api/returnMessage
        die("Hello");
        $xml = new XMLWriter();
        // $xml->openURI("php://output");
        $xml->startDocument('1.0','UTF-8');
        $xml->setIndent(4);
        $xml->startElement("response");
        foreach ($_POST as $k => $v)
        {
            if(isset($v))
            {
                $xml->startElement($k);
                $xml->text($v);
                $xml->endElement($k);
            }
        }
        $xml->endElement("response");
        $xml->endDocument();
        echo $xml->flush();
    }

    private function clickatellAuth()
    {
        if( !(isset($this->clickatellAuth["session"]) and isset($this->clickatellAuth["retrievalTime"])) or (  $this->clickatellAuth["retrievalTime"]->diff(new DateTime(date("d-m-Y H:i:s")))->i >= 15  ) )
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
                $this->clickatellAuth["retrievalTime"] = new DateTime(date("d-m-Y H:i:s"));

                // $this->clickatellAuth["retrievalTime"] = new DateTime("2013-10-26 22:53:51");
                // $retrievalTime = new DateTime($this->clickatellAuth["retrievalTime"]);
                // var_dump($this->clickatellAuth["retrievalTime"]->diff(new DateTime(date("d-m-Y H:i:s")))->i); exit();
                // var_dump($this->clickatellAuth);
            }
        }
    }
}