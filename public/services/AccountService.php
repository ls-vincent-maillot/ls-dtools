<?php

class AccountService
{
	private $customer_db;
	private $account_db;

	public function __construct(NotORM $customer_db)
	{
		$this->customer_db = $customer_db;
	}

	public function setAccountDB(NotORM $account_db)
	{
		$this->account_db = $account_db;
	}

	public function getAccounts()
	{
		$results = $this->customer_db->cust_customer()
			->select('cust_customer_id, database_name, name')
			->fetchPairs('cust_customer_id');

		return $results;
	}

	public function getAccountDBName($account_id)
	{
		return $this->customer_db->cust_customer()
			->select('database_name')
			->where('cust_customer_id', $account_id)
			->fetch()['database_name'];
	}

	public function getShops()
	{
		$results = $this->account_db->shop()
			->select('shop_id, name')
			->fetchPairs('shop_id');

		return $results;
	}
}
