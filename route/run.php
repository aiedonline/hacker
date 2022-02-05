<?php
    error_reporting(E_ALL);
    // You may use status(), start(), and stop(). notice that start() method gets called automatically one time.
    $process = new Process("/usr/bin/python3 /var/well/secanalysis/bot/app.py -s '127.0.0.1' -p 'cc40fff9-b1e6-e9b2-e108-4c01178a573f' -t 'd5c8cb88-2980-4fe2-a6a6-74d931546d48' -u 'c308f6804bdd1a856355d3a34113f22a5d5f799b'");

    // or if you got the pid, however here only the status() metod will work.
    #$process = new Process();
    #$process.setPid(my_pid);

    // Then you can start/stop/ check status of the job.
    //$process.stop();
    //$process.start();
    //if ($process.status()){
    //    echo "The process is currently running";
    //}else{
    //    echo "The process is not running.";
    //}

    echo "PID: " . strval($process->getPid());
?>

<?php
/* An easy way to keep in track of external processes.
* Ever wanted to execute a process in php, but you still wanted to have somewhat controll of the process ? Well.. This is a way of doing it.
* @compability: Linux only. (Windows does not work).
* @author: Peec
*/
class Process{
    private $pid;
    private $command;

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCom();
        }
    }
    private function runCom(){
        $command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        //$command = $this->command;
        exec($command,$op);
        
        $this->pid = (int)$op[0];
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
?>