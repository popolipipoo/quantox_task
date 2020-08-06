<?php

class DB
{
	private $db;

	public function __construct()
	{
		try {
			$this->db = new PDO(
				sprintf('mysql:host=%s;port=%s;dbname=%s',
					'localhost',
					'3306',
					'quantox_task'
				),
				'root',
				'',
				[
					PDO::ATTR_PERSISTENT => false,
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				]
			);
		} catch (PDOException $e) {
			die("PDOException: " . $e->getMessage());
		} catch (Exception $e) {
			die("Exception: " . $e->getMessage());
		}
		return $this->db;
	}

	public function get()
	{
		if (!empty($this->db)) {
			return $this->db;
		}
		$this->db = new DB();
		return $this->db;
	}

	/**
	 * Execure a SQL query
	 *
	 * @return PDO result|null
	 */
	public function query($sql)
	{
		if ($this->get()) {
			try {
				$result = $this->db->query($sql);
				return $result;
			} catch (PDOException $e) {
				die('PDOException: ' . $e->getMessage());
			} catch (Exception $e) {
				die('Exception: ' . $e->getMessage());
			}
		} else {
			die('ERROR: Not connected to database.');
		}
		return null;
	}

	/**
	 * Fetch one row
	 *
	 * @return array|null
	 */
	public function fetch($sql)
	{
		$_result = $this->query($sql);
		$result = empty($_result) ? null : $_result->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * Fetch all rows
	 *
	 * @return array
	 */
	public function fetchAll($sql)
	{
		$_result = $this->query($sql);
		$result = empty($_result) ? [] : $_result->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

}
