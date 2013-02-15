#!/usr/local/bin/php4
<?php

function execute() {
    $handle = fopen('php://stdin', 'r');

    for ($i=1; $i<100; $i++) {      // 当分上限いれとく
        echo "phpc($i) >> ";
        $input = fread($handle, 8192);
    #    $input = preg_replace("/\n$$/m", $input, '');  // 改行不要
        $input = rtrim($input, "\n");       // 末尾改行不要

        if ($input == 'exit') {
            echo "終了します\n";
            exit(0);
        } else if (empty($input)) {
            // 何もしない
        } else {
            eval($input);
            echo "\n";
        }
    }
}

execute();

