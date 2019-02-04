<?

namespace Rolland

class dbg{
    static $enabled = false;
    static $pointPre = true;

    static $start;
    static $lastpoint;

    function reset(){
        self::$start = microtime(true);
        self::$lastpoint = self::$start;
    }

    function point(){
        if(!self::$enabled) return;

        $stack = debug_backtrace(false);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];

        $now = microtime(true);
        $fromLast = round($now-self::$lastpoint,3);
        if($fromLast> 0.01){
            $fromLast = '<b>'.$fromLast.'</b>';
        }
        $string ="line:".$line.", previous:".$fromLast.", form start:".round($now-self::$start,3).", \nfile:".$file;
        if(self::$pointPre){
            pre($string,0,1);
        }else{
            self::echoStr($string);
        }
        self::$lastpoint = $now;
    }

    function setPointPre($newState = true){
        self::$pointPre = (bool)$newState;
    }

    function enable($reset = true){
        self::$enabled = true;
        if($reset){
            self::reset();
        }
    }

    function disable(){
        self::$enabled = false;
    }

    function pre($data,$exit=0){
        if(!self::$enabled) return;
        pre($data,$exit,1);
    }

    function dump($data,$exit=0){
        if(!self::$enabled) return;
        var_dump($data);
        if($exit){
            exit();
        }
    }

    function echoStr($string,$htmlMode = false){
        if(!self::$enabled) return;
        echo $string.($htmlMode?'<br>':"\n");
    }
}