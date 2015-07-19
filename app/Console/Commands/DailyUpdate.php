<?php namespace App\Console\Commands;

require app_path('Notifications.php');

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DailyUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'brainstorm:dailyupdate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends an email with latest ideas, comments, etc to subscribed users.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		\Notifications::dailyUpdate();
	}

}
