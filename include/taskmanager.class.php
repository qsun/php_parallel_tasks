<?php

/* TaskManager - run multiple tasks at the same time */
class TaskManager 
{
    var $slots; /* How many tasks(s) can we run simutaniusly */

    var $pendingQueue;
    var $runningTasks;
    
    function __construct($slots) 
    {
        $this->pendingQueue = new Queue();
        $this->runningTasks = array();
    }

    private function _tick() 
    {
	    $runningSlots = 0;

	    foreach ($this->runningTasks as $idx => $task) {
		    if ($task->isFinished()) {
			    $task->harvestResult();
			    unset($this->runningTasks[$idx]);
			    $runningSlots++;
		    }
	    }

	    while ($runningSlots <= $this->slots) {
		    $task = $this->pendingQueue->dequeue();
		    if ($task) {
			    $task->run();

			    array_push($this->runningTasks, $task);

			    $runningSlots++;
		    } else {
			    // No other job pending
			    break;
		    }
	    }
    }

    public function tickOnce() 
    {
        $this->_tick();
    }
    
    public function tickForever() 
    {
        while (1) {
            $this->tickOnce();
            sleep(1);
        }
    }
    
    public function addTask(&$task) 
    {
        $this->pendingQueue->enqueue($task);
    }
    
}
