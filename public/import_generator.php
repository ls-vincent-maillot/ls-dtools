<?php
$rows = intval($_POST['rows']);

class ImportGen
{
	const TYPE_EMPTY = 'empty';
	const TYPE_STRING = 'string';
	const TYPE_STRING_UNIQUE = 'string_uq';
	const TYPE_STRING_IDENTIFIER = 'stringIdentifier';
	const TYPE_MONEY = 'money';
	const TYPE_UPC = 'upc';
	const TYPE_NUMBER = 'number';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_DEFAULT = 'default';
	const TYPE_TEXT = 'text';
	const TYPE_COMMA = 'commaSeparated';

	const headers = [
		'Description'               => self::TYPE_STRING,
		'UPC'                       => self::TYPE_UPC,
		'EAN'                       => self::TYPE_EMPTY,
		'Custom SKU'                => self::TYPE_STRING_IDENTIFIER,
		'Manufacturer SKU'          => self::TYPE_STRING,
		'Brand'                     => self::TYPE_STRING,
		'Matrix Description'        => self::TYPE_STRING,
		'Matrix Attribute Set'      => self::TYPE_STRING,
		'Attribute 1'               => self::TYPE_STRING,
		'Attribute 2'               => self::TYPE_STRING,
		'Price'                     => self::TYPE_MONEY,
		'MSRP'                      => self::TYPE_MONEY,
		'Default Cost'              => self::TYPE_MONEY,
		'Add Tags'                  => self::TYPE_STRING,
		'Discountable'              => self::TYPE_BOOLEAN,
		'Taxable'                   => self::TYPE_BOOLEAN,
		'Vendor'                    => self::TYPE_STRING,
		'Vendor ID'                 => self::TYPE_STRING,
		'Item Type'                 => self::TYPE_DEFAULT,
		'Note'                      => self::TYPE_TEXT,
		'Display Note'              => self::TYPE_BOOLEAN,
		'Category'                  => self::TYPE_STRING,
		'Subcategory 1'             => self::TYPE_STRING,
		'Subcategory 2'             => self::TYPE_STRING,
		'Subcategory 3'             => self::TYPE_STRING,
		'Shop A - Quantity on Hand' => self::TYPE_NUMBER,
		'Shop A - Unit Cost'        => self::TYPE_NUMBER,
		'Shop A - Reorder Point'    => self::TYPE_NUMBER,
		'Shop A - Reorder Level'    => self::TYPE_NUMBER,
		'Shop B - Quantity on Hand' => self::TYPE_NUMBER,
		'Shop B - Unit Cost'        => self::TYPE_NUMBER,
		'Shop B - Reorder Point'    => self::TYPE_NUMBER,
		'Shop B - Reorder Level'    => self::TYPE_NUMBER,
	];

	protected $description = 'Generate Import File';

	protected $counters = [];

	public function __construct()
	{

	}

	public function generate(int $rows = 1)
	{
		$ignore_single_keys = [
			'Matrix Description',
			'Matrix Attribute Set',
			'Attribute 1',
			'Attribute 2',
		];

		$ignore_matrix_keys = [
			'Description',
		];

		$matrix_from_index = $rows / 2;

		$lines = [];

		$lines[] = implode(',', array_keys(self::headers));
		for ($i = 0; $i < $rows; $i++)
		{
			$line = [];
			if ($i >= $matrix_from_index)
			{
				foreach (self::headers as $key => $type)
				{
					if (in_array($key, $ignore_matrix_keys))
					{
						$line[$key] = "";
						continue;
					}

					$line[$key] = $this->$type($key);
				}
				$lines[] = implode(',', $line);
			}
			else
			{
				foreach (self::headers as $key => $type)
				{
					if (in_array($key, $ignore_single_keys))
					{
						$line[] = "";
						continue;
					}

					$line[] = $this->$type($key);
				}
				$lines[] = implode(',', $line);
			}
		}

		$data = implode(PHP_EOL, $lines);

		return $data;

		$this->counters = [];
	}

	private function upc(string $key)
	{
		return "";
	}

	private function string(string $key)
	{
		$value = "";
		switch ($key)
		{
			case 'Matrix Attribute Set' :
				$value = "Color/Size";
				break;
			default:
				$value = uniqid($key . " ");
				break;
		}

		return $value;
	}

	private function string_uq(string $key)
	{
		return $key;
	}

	private function commaSeparated(string $key)
	{
		return '"' . implode(
				',',
				[
					uniqid($key),
					uniqid($key),
					uniqid($key),
				]
			) . '"';
	}

	private function text(string $key)
	{
		return uniqid($key);
	}

	private function money(string $key)
	{
		$value = rand(100, 5000) / 100;

		return $value;
	}

	private function boolean(string $key)
	{
		return rand(0, 1) ? "Yes" : "No";
	}

	private function number(string $key, $min = 1, $max = 100)
	{
		return rand($min, $max);
	}

	private function stringIdentifier(string $key)
	{
		if (!array_key_exists($key, $this->counters))
		{
			$this->counters[$key] = 0;
		}
		$this->counters[$key]++;
		$counter = $this->counters[$key];

		return "{$key} {$counter}";
	}

	private function empty()
	{
		return "";
	}

	private function default()
	{
		return "default";
	}
}


$generator = new ImportGen();
$data = $generator->generate(intval($_POST['rows']));

function write_file(string $filename, string $data)
{
	$directory = "./files/";
	$handle = fopen($directory . $filename, 'w+');
	fputs($handle, $data);
	fclose($handle);
}

$filename = uniqid()."_import_data_{$rows}_rows.csv";

write_file($filename, $data);
header('Content-type: text/csv');
header("Content-Disposition: attachment; filename={$filename}");

echo $data;
