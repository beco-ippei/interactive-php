<?php
/**
 * debugger (like ruby-debug)
 * usage:
 *      eval(Debugger::get());      # debug. stop and run any code on console.
 * NOTE:
 *      use only programmer who know eval()'s lisk.
 */
echo "load debugger....\n";
class Debugger
{
    function get()
    {
        $proc = '
        Debugger::printPosition(__FILE__);
        for ($i=1; $i<100; $i++) {       // 安全装置つき
            $handle = Debugger::handleInput();
            if ($handle == "break") {
                break;
            } else if (empty($handle)) {
                continue;   // 何もしない
            }
            eval($handle);
            echo "\n";
        }
        ';
        return $proc;
    }

    function handleInput()
    {
        static $i = 0;
        echo 'phpc('.++$i.') >> ';
        $input = rtrim(fgets(STDIN), "\n"); // 末尾改行不要
        if ($input == "exit") {
            echo "終了します\n";
            exit;
        } else if ($input == "\\q") {
            return 'break';
        } else if (empty($input)) {
            return null;    // 何もしない
        } else {
            return $input;
        }
    }

    function printPosition($evaled_file)
    {
        $temp = explode(':', $evaled_file);
        $file = $temp[0];

        preg_match('/(^[^\(]*)\((\d+)\)/', $file, $matched);
        $file_path = $matched[1];
        $current_line = $matched[2];

        $contents = Debugger::getDebuggingFileContents(
            $file_path, $current_line - 5, $current_line + 5);

        echo "\nDEBUGGER -- {$file_path} --\n";
        foreach ($contents as $line=>$value) {
            $mark = $line == $current_line ? '>' : ' ';
            echo " {$mark} {$value}";
        }
    }

    function getDebuggingFileContents($file, $read_from, $read_to)
    {
        $fp = @fopen($file, 'r');
        if (!$fp) return null;

        $contents = array();
        for ($i = 1; $line = fgets($fp); $i++) {
            if ($read_from <= $i && $i <= $read_to) {
                $contents[$i] = $line;
            }
        }
        @fclose($fp);
        return $contents;
    }
}

echo "execute debugger for {$argv[1]}\n";
require_once $argv[1];

