<?php

class ImportService
{
	public function generateFile(
		FileConfig $config
	) {
		$generator = new ImportGenerator($config);
		$data = $generator->generate();
		
		if($config->filename == "")
		{
			$filename = sprintf(
				"%s_import_data_%d_%d_rows.csv",
				uniqid(),
				$config->single_count,
				$config->matrix_count
			);	
		}
		else
		{
			$filename = sprintf(
				"%s.csv",
				$config->filename
			);
		}
		

		write_file($filename, $data);
		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename={$filename}");

		echo $data;
	}
}
