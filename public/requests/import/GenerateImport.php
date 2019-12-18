<?php

require_once("../../startup.php");

$config = new FileConfig($_POST);
$importService = new ImportService();
$importService->generateFile($config);
