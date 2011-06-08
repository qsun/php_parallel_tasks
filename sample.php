<?php

/* This script will run 10 tasks, and 4 tasks at the same time. Each
 * task took 4 seconds, but all tasks can be finished in 12 seconds */

require('include.php');

/* define my own sample task object */
class SampleTask extends Task
{
    protected function perform() 
    {
        sleep(4);
        return new Result('success');
    }

    protected function reportResult($result) 
    {
        echo($result . "\n");
    }
}

/* create task manager, with 4 running slots */
$manager = new TaskManager(4);

/* create 10 tasks */
$nTasks = 10;
while ($nTasks > 0) {
    $manager->addTask(new SampleTask);
    $nTasks--;
}

/* run! */
$manager->tickForever();

