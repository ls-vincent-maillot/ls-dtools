<?php


class ImportGenerator
{
	const TYPE_EMPTY = 'empty';
	const TYPE_STRING = 'string';
	const TYPE_STRING_LIMIT = 'stringLimit';
	const TYPE_STRING_UNIQUE = 'string_uq';
	const TYPE_STRING_IDENTIFIER = 'stringIdentifier';
	const TYPE_MONEY = 'money';
	const TYPE_UPC = 'upc';
	const TYPE_NUMBER = 'number';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_DEFAULT = 'default';
	const TYPE_TEXT = 'text';
	const TYPE_COMMA = 'commaSeparated';
	const TYPE_ATTRIBUTE_SET = 'commaSeparated';
	const TYPE_SHOP_NUMBER = 'shopNumber';

	const headers = [
		'Description'          => self::TYPE_STRING,
		'UPC'                  => self::TYPE_UPC,
		'EAN'                  => self::TYPE_EMPTY,
		'Custom SKU'           => self::TYPE_STRING_IDENTIFIER,
		'Manufacturer SKU'     => self::TYPE_STRING,
		'Brand'                => self::TYPE_STRING_LIMIT,
		'Matrix Description'   => self::TYPE_STRING,
		'Matrix Attribute Set' => self::TYPE_ATTRIBUTE_SET,
		'Attribute 1'          => self::TYPE_STRING,
		'Attribute 2'          => self::TYPE_STRING,
		'Attribute 3'          => self::TYPE_STRING,
		'Price'                => self::TYPE_MONEY,
		'MSRP'                 => self::TYPE_MONEY,
		'Default Cost'         => self::TYPE_MONEY,
		'Add Tags'             => self::TYPE_COMMA,
		'Discountable'         => self::TYPE_BOOLEAN,
		'Taxable'              => self::TYPE_BOOLEAN,
		'Vendor'               => self::TYPE_STRING,
		'Vendor ID'            => self::TYPE_STRING,
		'Item Type'            => self::TYPE_DEFAULT,
		'Note'                 => self::TYPE_TEXT,
		'Display Note'         => self::TYPE_BOOLEAN,
		'Category'             => self::TYPE_STRING_LIMIT,
	];

	public static $ignore_single = [
		'Matrix Description',
		'Matrix Attribute Set',
		'Attribute 1',
		'Attribute 2',
		'Attribute 3',
	];

	public static $ignore_matrix = [
		'Description',
	];

	protected $description = 'Generate Import File';

	protected $counters = [];
	private $limits;
	private $config;

	public function __construct(FileConfig $config)
	{
		$this->config = $config;

		$this->limits = [
			'Category' => [],
			'Brand'    => [],
		];
		for ($i = 0; $i < $config->categories_count; $i++)
		{
			$this->limits['Category'][] = "Category" . $i;
		}

		for ($i = 0; $i < $config->brands_count; $i++)
		{
			$this->limits['Brand'][] = "Brand" . $i;
		}
	}

	public function generate()
	{
		$lines = [];

		$headers = self::headers;
		$inventory_keys = [
			'Quantity on Hand',
			'Unit Cost',
			'Reorder Point',
			'Reorder Level',
		];

		foreach ($inventory_keys as $key)
		{
			$headers["Shop " . $key] = self::TYPE_NUMBER;
		}

		$lines[] = implode(',', array_keys($headers));
		$total_index = 0;
		for ($i = 0; $i < $this->config->single_count; $i++)
		{
			$lines[] = implode(',', $this->createSingleRow($headers));
			$total_index++;
		}
		
		for ($i = 0; $i < $this->config->matrix_count; $i++)
		{
			list($matrix_row, $attributes) = $this->createMatrixRow($headers);
			
			$variant_count = rand(1, $this->config->variants_count);
			$variant_rand = floor($variant_count / $attributes);
			
			$attributes_collection = [];

			for ($j = 1; $j <= $attributes; $j++)
			{
				$attributes_collection[] = $this->string("Attribute {$j}");
			}
			
			$att_keys = [
				'Attribute 1',
				'Attribute 2',
				'Attribute 3',
			];
			
			switch ($attributes)
			{
				case 1 :
					
					for ($j = 0; $j < $variant_rand; $j++)
					{
						$matrix_row[$att_keys[0]] = $attributes_collection[0]." ".$j;
						$matrix_row['Custom SKU'] = $this->stringIdentifier('Custom SKU');
						$lines[] = implode(',', $matrix_row);
						$i++;
					}
					break;
				case 2 :
					for ($j = 0; $j < $variant_rand; $j++)
					{
						foreach ($attributes_collection as $attx_index => $attx)
						{
							$attx_value = sprintf("%s %s", $attx, $attx_index);
							$matrix_row[$att_keys[0]] = $attx_value;
							foreach ($attributes_collection as $atty_index => $atty)
							{
								$atty_value = sprintf("%s %s", $atty, $atty_index);
								$matrix_row[$att_keys[1]] = $atty_value;
								$matrix_row['Custom SKU'] = $this->stringIdentifier('Custom SKU');
								$lines[] = implode(',', $matrix_row);
								$i++;
							}
						}
					}
					break;
				case 3 :
					for ($j = 0; $j < $variant_rand; $j++)
					{
						foreach ($attributes_collection as $attx_index => $attx)
						{
							$attx_value = sprintf("%s %s", $attx, $attx_index);
							$matrix_row[$att_keys[0]] = $attx_value;
							foreach ($attributes_collection as $atty_index => $atty)
							{
								$atty_value = sprintf("%s %s", $atty, $atty_index);
								$matrix_row[$att_keys[1]] = $atty_value;
								foreach ($attributes_collection as $attz_index => $attz)
								{
									$attz_value = sprintf("%s %s", $attz, $attz_index);
									$matrix_row[$att_keys[2]] = $attz_value;
									$matrix_row['Custom SKU'] = $this->stringIdentifier('Custom SKU');
									$lines[] = implode(',', $matrix_row);
									$i++;
								}
							}
						}
					}
					break;
			}
		}
		$lines = array_slice($lines, 0, $this->config->matrix_count + $this->config->single_count);
		$data = implode(PHP_EOL, $lines);

		return $data;
	}

	private function createSingleRow(array $headers): array
	{
		$line = [];
		foreach ($headers as $key => $type)
		{
			if (in_array($key, self::$ignore_single))
			{
				$line[] = "";
				continue;
			}

			$line[] = $this->$type($key);
		}

		return $line;
	}

	private function createMatrixRow(array $headers): array
	{
		$line = [];

		$sets = [
			[
				'label' => "Color",
				'count' => 1,
			],
			[
				'label' => "Size",
				'count' => 1,
			],
			[
				'label' => "Color/Size",
				'count' => 2,
			],
			[
				'label' => "3 Attributes",
				'count' => 3,
			],
		];
		$set = $sets[0];
		$att_count = 0;
		foreach ($headers as $key => $type)
		{
			if (in_array($key, self::$ignore_matrix))
			{
				$line[$key] = "";
				continue;
			}

			switch ($key)
			{
				case "Matrix Attribute Set" :
					$set_index = rand(0, 3);
					$set = $sets[$set_index];
					$line[$key] = $set['label'];
					break;
				case "Attribute 1" :
				case "Attribute 2" :
				case "Attribute 3" :
					if ($att_count < $set['count'])
					{
						$att_count++;
						$line[$key] = $this->string($key);
					}
					else
					{
						$line[$key] = "";
					}
					break;
				default :
					$line[$key] = $this->$type($key);
					break;
			}
		}

		return [$line, $att_count];
	}

	private function upc(string $key)
	{
		return "";
	}

	private function string(string $key)
	{
		return uniqid($key . " ");
	}

	private function stringLimit(string $key)
	{
		switch ($key)
		{
			case 'Brand' :
				$limit = $this->config->brands_count;
				break;
			case 'Category' :
				$limit = $this->config->categories_count;
				break;
			default :
				$limit = 50;
				break;
		}

		$rand = rand(1, $limit);

		return $this->limits[$key][$rand];
	}

	private function string_uq(string $key)
	{
		return $key;
	}

	private function commaSeparated(string $key)
	{
		$count = rand(1, 5);
		$attributes = [];
		for ($i = 0; $i < $count; $i++)
		{
			$attributes[] = $this->string($key);
		}

		return sprintf('"%s"', implode(',', $attributes));
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

class FileConfig
{
	public $single_count = 0;
	public $matrix_count = 0;
	public $variants_count = 0;
	public $brands_count = 0;
	public $categories_count = 0;
	public $filename = "import.csv";

	private $allowed_keys = [
		'single_count',
		'matrix_count',
		'categories_count',
		'brands_count',
		'variants_count',
		'filename',
	];

	public function __construct(array $data)
	{
		foreach ($this->allowed_keys as $key)
		{
			if (isset($data[$key]))
			{
				$this->$key = $data[$key];
			}
		}
	}
}
	
