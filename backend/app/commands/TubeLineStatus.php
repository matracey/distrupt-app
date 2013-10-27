<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TubeLineStatus extends Command {

    private $clickatellAuth = array("session" => null, "retrievalTime" => null);
    const CLICKATELL_API_ID = '3445709';
    const CLICKATELL_API_PASS = '9y8NvTuNu';
    const CLICKATELL_API_USER = 'HACKATHON102';

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'line:status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieve line statuses.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
            // get disrupted tube lines
            $raw_xml = file_get_contents('http://cloud.tfl.gov.uk/TrackerNet/LineStatus/IncidentsOnly');
            $xml = simplexml_load_string($raw_xml);

            $disruptedLines = array();

            foreach ($xml as $lineStatus) {
                // get line name
                $line_name = strtolower((string) $lineStatus->Line->attributes()->Name);
                $disruptedLines[] = '"' . $line_name . '"';
            }
            $disruptedLinesSql = implode(',', $disruptedLines);

            // email all users who have not been communicated with, and whose lines are down
//            print_r(strtotime('09:00 AM'));
//            print_r($disruptedLines);
//            print_r(date('H:i:s', strtotime('09:00 AM')));
//            print_r($disruptedLines);

            $sql = "SELECT * FROM users
                WHERE (user_line_one IN ({$disruptedLinesSql})
                OR user_line_two IN ({$disruptedLinesSql}) )
                AND user_last_sms < NOW() - INTERVAL 1 DAY";

            $users = DB::select( $sql );

//            print_r($users);

            foreach ($users as $user) {
                $phoneNumbers = explode(",", $user->user_sms);
                $message = 'Hi there, I will be in late. regards ';

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

//                    var_dump($result);
                }
            }

            // mark users as notified
            DB::statement( "UPDATE users
                SET user_last_sms = NOW()
                WHERE (user_line_one IN ({$disruptedLinesSql})
                OR user_line_two IN ({$disruptedLinesSql}) )
                ");


	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
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