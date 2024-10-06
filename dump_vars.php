<?php

function prettyVarDump($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}

prettyVarDump($_SERVER);

phpinfo();