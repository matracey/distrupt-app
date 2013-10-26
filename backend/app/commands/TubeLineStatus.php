<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TubeLineStatus extends Command {

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
//            die('sldclsdnclsdncklsdncksldnc');
            // get disrupted tube lines
            $raw_xml = file_get_contents('http://cloud.tfl.gov.uk/TrackerNet/LineStatus/IncidentsOnly');
            $xml = simplexml_load_string($raw_xml);

            $disruptedLines = array();

            foreach ($xml as $lineStatus) {
                die(var_dump($lineStatus->BranchDisruptions));
                // continue if no disruption node exists
                if (isset($lineStatus->BranchDisruptions) === false
                    && $lineStatus->BranchDisruptions->hasChildren() === false) {

                    continue;
                }

                // get all disruptions for line
                foreach ($lineStatus->BranchDisruptions as $branchDisruption) {

                    $stationTo = $branchDisruption->StationTo->attributes();
                    $stationFrom = $branchDisruption->StationFrom->attributes();

                    $stationToName = $stationTo['Name'];
                    $stationFromName = $stationFrom['Name'];
                    var_dump($stationTo);
                    $disruptedLines[] = array(
                        'from' => '',
                        'to' => '',
                    );
                }

                break;
            }
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

}