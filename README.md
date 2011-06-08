PHP Parallel Tasks
==================

perform multiple tasks simultaneously 


Code Example
------------

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
    
