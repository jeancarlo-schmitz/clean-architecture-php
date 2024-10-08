<?php
function isRunningFromCLI() {
    return (php_sapi_name() === 'cli');
}

function isNotCLIOrLinux() {
    return (!isRunningFromCLI() && php_uname('s') !== 'Linux');
}

function pre($x, $titulo = '', $exit = false) {
    ob_implicit_flush();
    $pid = getmypid();
    $shouldFormat = isNotCLIOrLinux();

    $preTag = $shouldFormat ? "<pre>" : "";
    $preEndTag = $shouldFormat ? "</pre>\n" : "\n";
    $fieldsetStart = $shouldFormat ? "<fieldset style='min-width: 50%; word-wrap: break-word; background-color: #FAFAFA; border: 2px groove #ddd !important; padding: 1.4em 1.4em 1.4em 1.4em !important;'>" : "";
    $fieldsetEnd = $shouldFormat ? "</fieldset>" : "";

    echo $fieldsetStart;
    if (!empty($titulo)) {
        $titulo = $shouldFormat ? "<legend style='color:rgb(0, 0, 123); padding: 3px 10px 3px 10px; font-weight: bold; font-size: 14px; text-transform: uppercase; border: 1px groove #ddd !important;'> $titulo </legend>" : "";
        echo $titulo;
    }
    echo $preTag;
    if($shouldFormat) {
        echo "----------------------------\r\nProcesso PID: {$pid}\r\n----------------------------\r\n\r\n";
    }

    if(!is_array($x)) {
        $x = date("Y-m-d H:i:s") . ": " . $x;
    }else{
        echo date("Y-m-d H:i:s") . ": ";
    }

    print_r($x);
    echo $preEndTag;
    echo $fieldsetEnd;
    if ($exit) {
        exit;
    }
}

function pred($x, $titulo = '') {
    pre($x, $titulo, true);
}

function dd($x, $titulo = '') {
    pre($x, $titulo, true);
}