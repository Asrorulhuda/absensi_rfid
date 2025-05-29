<?php
session_start();
session_destroy();
header('location:../../');//goto login page
?>