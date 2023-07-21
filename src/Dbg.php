<?

namespace Rolland;

class Dbg{
    static $enabled = false;
    static $pointPre = true;

    static $start;
    static $lastpoint;

    static function reset(){
        self::$start = microtime(false);
        self::$lastpoint = self::$start;
    }

    static function point(){
        if(!self::$enabled) return;

        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];

        $now = microtime(true);
        $fromLast = round($now-self::$lastpoint,3);
        if($fromLast> 0.01){
            $fromLast = '<b>'.$fromLast.'</b>';
        }
        $string ="line:".$line.", previous:".$fromLast.", form start:".round($now-self::$start,3).", \nfile:".$file;
        if(self::$pointPre){
            self::formatPre($string,0,1);
        }else{
            self::echoStr($string);
        }
        self::$lastpoint = $now;
    }

    function setPointPre($newState = true){
        self::$pointPre = (bool)$newState;
    }

    static function enable($reset=false){
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];
        self::formatPre("Debug enabled from line:".$line.", \nfile:".$file,0,1);
        if(!self::$enabled || $reset)
            self::reset();
        self::$enabled = true;
    }

    static function disable(){
        self::$enabled = false;
    }

    static function pre($data,$exit=0){
        if(!self::$enabled) return;
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];
        self::formatPre("Dump from from line:".$line.", \nfile:".$file,0,1);
        self::formatPre($data,$exit,1);
    }

    function dump($data,$exit=0){
        if(!self::$enabled) return;
		$stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];
        echo "Dump from from line:".$line.", \nfile:".$file.PHP_EOL;
        var_dump($data);
        if($exit){
            exit();
        }
    }

    function echoStr($string,$htmlMode = false){
        if(!self::$enabled) return;
        echo $string.($htmlMode?'<br>':"\n");
    }

    static function place($data,$exit=0){
        if(!self::$enabled) return;
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $stack[0]['line'];
        $file = $stack[0]['file'];
        $function = $stack[1]['function'];
        self::formatPre("Function: ".$function."(), Line: ".$line.", \nfile:".$file,0,1);
    }

    function formatPre($var,$exit=false){
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        if($exit){
            exit('********************');
        }
    }


}
