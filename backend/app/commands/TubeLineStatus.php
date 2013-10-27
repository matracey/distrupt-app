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
            // get disrupted tube lines
            $raw_xml = file_get_contents('http://cloud.tfl.gov.uk/TrackerNet/LineStatus/IncidentsOnly');
            $xml = simplexml_load_string($raw_xml);

            $disruptedLines = array();

            foreach ($xml as $lineStatus) {
                // get line name
                $line_name = strtolower((string) $lineStatus->Line->attributes()->Name);
                $disruptedLines[] = $line_name;
            }

            // email all users who have not been communicated with, and whose lines are down

            print_r($disruptedLines);
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