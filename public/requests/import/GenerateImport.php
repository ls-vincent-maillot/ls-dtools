<?php

require_once("../../startup.php");

$single_count = intval($_POST['single_count']);
$matrix_count = intval($_POST['matrix_count']);
$account = intval($_POST['account']);
/*
 * 

if ($account > 0)
{
	$name = $accountService->getAccountDBName($account);
	$pdo_str = sprintf("mysql:host=rad.localdev;dbname=%s", $name);
	$account_pdo = new PDO($pdo_str, "root", "root");
	$structure = new NotORM_Structure_Convention(
		$primary = "%s_id", // $table_id
		$foreign = "%s_id", // $table_id
		$table = "%s", // {$table}
		$prefix = "" // 
	);
	$account_db = new NotORM($account_pdo, $structure);
	$accountService->setAccountDB($account_db);
	
	$shops = $accountService->getShops();
}
else 
{
	$shops = [];
}
 */
$shops = [];
$importService = new ImportService();
$importService->generateFile($shops, $single_count, $matrix_count);
