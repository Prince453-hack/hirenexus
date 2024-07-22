<?php

function basePath($path = "")
{
    return __DIR__ . "/" . $path;
}

function loadView($name)
{

    require basePath("/views/{$name}.views.php");
}


function partialView($name)
{
    require basePath("/views/partials/{$name}.php");
}


function inspect($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}

function inspectAndDie($value)
{
    echo "<pre>";
    die(var_dump($value));
    echo "</pre>";
}
