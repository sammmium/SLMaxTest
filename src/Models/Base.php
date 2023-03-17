<?php

namespace src\Models;

class Base
{
	protected static $mysqli = null;

	protected array $availableColumns = [];

	public static function connect(): void
	{
		if (is_null(self::$mysqli)) {
			$config = require __DIR__ . '/../config.php';
			self::$mysqli = mysqli_connect(
				$config['db']['host'],
				$config['db']['user'],
				$config['db']['password'],
				$config['db']['name']
			);
			self::execute("SET NAMES utf8");
		}
	}

	public static function disconnect(): void
	{
		self::$mysqli = null;
	}

	protected function get(string $query): array
	{
		self::connect();
		$result = [];
		$queryResult = self::execute($query);
		while ($row = mysqli_fetch_assoc($queryResult)) {
			$result[] = $row;
		}
		self::disconnect();
		return $result;
	}

	protected function set(string $query): int
	{
		self::connect();
		self::execute($query);
		$last_insert_id = mysqli_insert_id(self::$mysqli);
		self::disconnect();
		return $last_insert_id;
	}

	protected function update(string $query): void
	{
		self::connect();
		self::execute($query);
		self::disconnect();
	}

	protected function delete(string $query): void
	{
		self::connect();
		self::execute($query);
		self::disconnect();
	}

	protected function isAvailableColumn(string $column): bool
	{
		foreach ($this->availableColumns as $item) {
			if ($item === $column) {
				return true;
			}
		}
		return false;
	}

	protected static function execute(string $query)
	{
		return mysqli_query(self::$mysqli, $query);
	}

	protected function getTransformedDate(string $date, string $toLocale = 'en'): string
	{
		if (strpos($date, '.') !== false) {
			if ($toLocale == 'en') {
				list($d, $m, $y) = explode('.', $date);
				return $y . '-' . $m . '-' . $d;
			}
			return $date;
		}

		if ($toLocale == 'ru') {
			list($y, $m, $d) = explode('-', $date);
			return $d . '.' . $m . '.' . $y;
		}

		return $date;
	}

	protected function getPreparedConditions(array $filter = []): string
	{
		$result = '';
		$conditions = [];

		if (count($filter)) {
			foreach ($filter as $key => $value) {
				if (!is_null($value)) {
					$value = (strpos($key, 'date') !== false) ? $this->getTransformedDate($value) : $value;

					if (is_array($value)) {
						$conditions[] = $key . " in ('" . implode("', '", $value) ."')";
					} else {
						$conditions[] = $key . " = '" . $value ."'";
					}
				}
			}
		}

		if (count($conditions)) {
			$result = implode(' and ', $conditions);
		}

		return $result;
	}

	protected static function itemsToString(array $params): string
	{
		return implode(', ', $params);
	}
}
