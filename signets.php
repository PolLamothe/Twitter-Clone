<?php
    require './php/function.php';
    error_reporting(1);
    if(!isset($_SESSION)){
        session_start();
    }
    include 'template/signets.html';
?>