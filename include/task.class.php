<?php

abstract class Task
{
    const PENDING = 0;
    const RUNNING = 1;
    const FINISHED = 2;
    const HARVESTED = 3;
    
    var $status;
    var $exchangeFilename;
    var $child_pid = false;
    
    function __construct() 
    {
        $this->status = self::PENDING;
        $this->exchangeFilename = tempnam(sys_get_temp_dir(), uniqid());
    }
    
    public function run() 
    {
        $this->status = self::RUNNING;

        $child_pid = pcntl_fork();
        if ($child_pid != 0) {
            $this->child_pid = $child_pid;
            return true;
        } else {
            $result = $this->perform();
            $this->recordResult($result);
        }
    }

    public function isFinished() 
    {
        return ($this->getStatus() == self::FINISHED);
    }

    public function harvestResult() 
    {
        $result = $this->restoreResult();
        $this->reportResult($result);
        $this->status = self::HARVESTED;
    }

    protected function getStatus() 
    {
        if ($this->status == self::RUNNING) {
            $status = -1;

            /* When child process finished, pcntl_waitpid should
             * return 0 */
            $result = pcntl_waitpid($this->child_pid, $status, WNOHANG);
            if ($result != 0) {
                $this->status = self::FINISHED;
            }
        }
        
        return $this->status;
    }

    protected function recordResult($result) 
    {
        file_put_contents($this->exchangeFilename, $result->freeze());
    }

    /* return the stored result */
    protected function restoreResult() 
    {
        return Result::thaw(file_get_contents($this->exchangeFilename));
    }

    /* must be implemented */
    abstract protected function perform();
    abstract protected function reportResult($result);
}
