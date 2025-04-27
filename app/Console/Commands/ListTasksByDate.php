<?php

namespace App\Console\Commands;

use App\Interface\GetTasksActionInterface;
use Illuminate\Console\Command;

class ListTasksByDate extends Command
{
    public function __construct(private GetTasksActionInterface $getTasksAction)
    {
        parent::__construct();
    }

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

        $tasks = $this->getTasksAction->handle([
            'date_init' => $dateInit,
            'date_end' => $dateEnd,
        ]);

        if ($tasks->isEmpty()) {
            $this->info('No tasks found for the specified date range.');

            return Command::SUCCESS;
        }

        $this->table(['ID', 'Title', 'Description', 'Created At'], $tasks->map(function ($task) {
            return [
                'ID' => $task->id,
                'Title' => $task->title,
                'Description' => $task->description,
                'Created At' => $task->created_at,
            ];
        }));

        $this->info('Total tasks found: '.$tasks->count());

        return Command::SUCCESS;
    }
}
