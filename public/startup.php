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
