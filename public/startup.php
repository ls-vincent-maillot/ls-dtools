<?php
require_once ('services/AccountService.php');
require_once ('services/import/ImportGenerator.php');
require_once ('services/import/ImportService.php');
require_once ('database/NotORM.php');

function get_files()
{
	$filelist = [];
	if ($handle = opendir("./files"))
	{
		while ($entry = readdir($handle))
		{
			if (strpos($entry, "csv") !== false)
			{
				$filelist[] = $entry;
			}
		}
		closedir($handle);
	}

	return $filelist;
}

function write_file(string $filename, string $data)
{
	$directory = "../../files/";
	$handle = fopen($directory . $filename, 'w+');
	fputs($handle, $data);
	fclose($handle);
}

/*
 * 

$pdo = new PDO('mysql:host=rad.localdev;dbname=customer', "root", "root");
$structure = new NotORM_Structure_Convention(
	$primary = "%s_id", // $table_id
	$foreign = "%s_id", // $table_id
	$table = "%s", // {$table}
	$prefix = "" // 
);
$customer_db = new NotORM($pdo, $structure);
$accountService = new AccountService($customer_db);
 */
