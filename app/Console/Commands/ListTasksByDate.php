<?php

namespace App\Console\Commands;

use App\Actions\GetTasksAction;
use Illuminate\Console\Command;

class ListTasksByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-tasks-by-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command lists tasks by date range';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dateInit = $this->ask('Enter the date (YYYY-MM-DD) to filter tasks by:');
        $dateEnd = $this->ask('Enter the end date (YYYY-MM-DD) to filter tasks by:');

        // Validate the date format
        if (! \DateTime::createFromFormat('Y-m-d', $dateInit) || ! \DateTime::createFromFormat('Y-m-d', $dateEnd)) {
            $this->error('Invalid date format. Please use YYYY-MM-DD.');

            return Command::FAILURE;
        }

        $tasks = (new GetTasksAction)->handle([
            'date_init' => $dateInit,
            'date_end' => $dateEnd,
        ]);

        if ($tasks->isEmpty()) {
            $this->info('No tasks found for the specified date range.');
        } else {
            $this->table(['ID', 'Title', 'Description', 'Created At'], $tasks->toArray());
        }

        $this->info('Total tasks found: '.$tasks->count());

        return Command::SUCCESS;
    }
}
