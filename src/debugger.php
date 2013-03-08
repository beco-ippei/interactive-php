<?php
/**
 * debugger (like ruby-debug)
 * usage:
 *      eval(Debugger::get());      # debug. stop and run any code on console.
 * NOTE:
 *      use only programmer who know eval()'s lisk.
 */
class Debugger
{
    function get()
    {
        $proc = '
        for ($i=1; $i<100; $i++) {       // 安全装置つき
            echo "phpc($i) >> ";
            $input = rtrim(fgets(STDIN), "\n");       // 末尾改行不要
            if ($input == "exit") {
                echo "終了します\n";
                exit;
            } else if ($input == "\\q") {
                break;
            } else if (empty($input)) {
                // 何もしない
            } else { eval($input); echo "\n";
            }
        }
        ';
        return $proc;
    }

    function print_position($evaled_file)
    {
        $temp = implode(':', $evaled_file);
        $file = $temp[0];

        preg_match('/(^[^\(]*)\((\d+)\)/', $file, $matched);
        $file_path = $matched[1];
        $line_no = $matched[2];
    }
}

require_once $argv[1];

