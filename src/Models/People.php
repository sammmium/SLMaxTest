<?php

namespace src\Models;

use src\Models\Person;

require_once __DIR__ . '/../Models/Person.php';

class People extends Person
{
	/*
	 * Массив с id людей
	 */
	private static array $idList;

	/*
	 * Конструктор ведет поиск id людей по всем полям БД (поддержка выражений больше, меньше, не равно)
	 *
	 * Параметры проверены в конструкторе
	 */
	public function __construct(array $params = [])
	{
		self::$idList = $params;
	}

	/*
	 * Получение массива экземпляров класса 1 из массива с id людей полученного в конструкторе
	 */
	public function getPeople(): array
	{
		$condition = empty($_COOKIE['condition']) ? 'in' : $_COOKIE['condition'];
		$query = "select * from " . self::$table;
		if (count(self::$idList)) {
			$query .= " where id " . $condition . " (" . self::itemsToString(self::$idList) . ")";
		}
		$result = [];
		$people = $this->get($query);
		foreach ($people as $item) {
			$person = new Person($item);
			$result[] = $person->getPerson();
		}

		return $result;
	}

	/*
	 * Удаление людей из БД с помощью экземпляров класса 1 в соответствии с массивом, полученным в конструкторе
	 */
	public function deletePeople()
	{
		$query = "delete from " . self::$table . " where id in (" . implode(', ', self::$idList) . ")";
		$this->delete($query);
	}
}
