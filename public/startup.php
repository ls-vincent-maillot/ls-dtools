<?php

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
