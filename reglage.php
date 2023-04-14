<?php
    require './php/function.php';
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    require './template/reglage.html';

?>