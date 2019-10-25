<?php

class ImportService
{
	public function generateFile(
		array $shops,
		int $single_count,
		int $matrix_count,
		string $filename
	) {
		$generator = new ImportGenerator();
		$data = $generator->generate($shops, $single_count, $matrix_count);
		
		if($filename == "")
		{
			$filename = sprintf(
				"%s_import_data_%d_%d_rows.csv",
				uniqid(),
				$single_count,
				$matrix_count
			);	
		}
		else
		{
			$filename = sprintf(
				"%s.csv",
				$filename
			);
		}
		

		write_file($filename, $data);
		header('Content-type: text/csv');
		header("Content-Disposition: attachment; filename={$filename}");

		echo $data;
	}
}
