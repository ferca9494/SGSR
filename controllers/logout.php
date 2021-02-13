<?php

require'../framework/fw.php';
if(isset($_SESSION['ID']))
{
unset($_SESSION['ID']);
header("Location: login.php");
}