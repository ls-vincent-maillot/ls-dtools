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
		'Description'               => self::TYPE_STRING,
		'UPC'                       => self::TYPE_UPC,
		'EAN'                       => self::TYPE_EMPTY,
		'Custom SKU'                => self::TYPE_STRING_IDENTIFIER,
		'Manufacturer SKU'          => self::TYPE_STRING,
		'Brand'                     => self::TYPE_STRING,
		'Matrix Description'        => self::TYPE_STRING,
		'Matrix Attribute Set'      => self::TYPE_ATTRIBUTE_SET,
		'Attribute 1'               => self::TYPE_STRING,
		'Attribute 2'               => self::TYPE_STRING,
		'Attribute 3'               => self::TYPE_STRING,
		'Price'                     => self::TYPE_MONEY,
		'MSRP'                      => self::TYPE_MONEY,
		'Default Cost'              => self::TYPE_MONEY,
		'Add Tags'                  => self::TYPE_COMMA,
		'Discountable'              => self::TYPE_BOOLEAN,
		'Taxable'                   => self::TYPE_BOOLEAN,
		'Vendor'                    => self::TYPE_STRING,
		'Vendor ID'                 => self::TYPE_STRING,
		'Item Type'                 => self::TYPE_DEFAULT,
		'Note'                      => self::TYPE_TEXT,
		'Display Note'              => self::TYPE_BOOLEAN,
		'Category'                  => self::TYPE_STRING_LIMIT,
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
	public function __construct()
	{
		$this->limits = [
			'Category' => []
		];
		for ($i = 0; $i < 50; $i++)
		{
			$this->limits['Category'][] = "Category".$i;
		}
	}

	public function generate(array $shops, $single_count, $matrix_count)
	{
		$lines = [];
		
		$headers = self::headers;
		$inventory_keys = [
			'Quantity on Hand',
			'Unit Cost',
			'Reorder Point',
			'Reorder Level',
		];
		if (count($shops) > 0)
		{
			foreach ($shops as $shop)
			{
				foreach ($inventory_keys as $key)
				{
					$header_key = sprintf("%s - %s", $shop['name'], $key);
					$headers[$header_key] = self::TYPE_NUMBER;
				}
			}
		}
		else 
		{
			foreach ($inventory_keys as $key)
			{
				$headers[$key] = self::TYPE_NUMBER;
			}
		}
		
		$lines[] = implode(',', array_keys($headers));

		for ($i = 0; $i < $single_count; $i++)
		{
			$lines[] = implode(',', $this->createSingleRow($headers));
		}

		for ($i = 0; $i < $matrix_count; $i++)
		{
			$lines[] = implode(',', $this->createMatrixRow($headers));
		}

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
					$set = $sets[rand(0, 3)];
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

		return $line;
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
		return $this->limits['Category'][rand(0, 49)];
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
