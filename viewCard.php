<?php

if (!isset($_GET["code"]) || !is_numeric($_GET["code"]))
{
    echo "<script>window.location = \"\";</script>";
die();
}

if (isset($_GET["en"]))
{
    include 'viewCard-enus.php';
}
else
{
    include 'viewCard-zhhk.php';
}

?>