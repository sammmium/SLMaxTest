<?php

namespace src\Models;

use src\Models\Base;

require_once __DIR__ .'/../Models/Base.php';

class Person extends Base
{
	protected static string $table = 'person';

	private int $id;

	private string $firstname;

	private string $lastname;

	private string $birthdate;

	private int $sex;

	private string $city;

	protected static array $sexList = [
		'женщина',
		'мужчина'
	];

	protected array $availableColumns = [
		'firstname',
		'lastname',
		'birthdate',
		'sex',
		'city'
	];

	/**
	 * Конструктор класса либо создает человека в БД с заданной информацией,
	 * либо берет информацию из БД по id (предусмотреть валидацию данных)
	 *
	 * @param array $params
	 */
	public function __construct(array $params = [])
	{
		if (count($params)) {
			$this->parseParams($params);

			if (!$this->hasPerson()) {
				$this->createPerson();
			}

			return $this->getPerson();
		}

		return $this;
	}

	/**
	 * Параметры, переданные в конструктор переносим в свойства объекта
	 *
	 * @param array $params
	 * @return void
	 */
	private function parseParams(array $params): void
	{
		$this->id = empty($params['id']) ?: $params['id'];
		$this->firstname = empty($params['firstname']) ?: $params['firstname'];
		$this->lastname = empty($params['lastname']) ?: $params['lastname'];
		$this->birthdate = empty($params['birthdate']) ?: $params['birthdate'];
		$this->sex = empty($params['sex']) ?: $params['sex'];
		$this->city = empty($params['city']) ?: $params['city'];
	}

	/**
	 * Сохранение полей экземпляра класса в БД
	 * Возвращает идентификатор последней созданной записи
	 *
	 * @return int
	 */
	public function createPerson(): int
	{
		$input = [
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'birthdate' => $this->getTransformedDate($this->birthdate, 'en'),
			'sex' => $this->sex,
			'city' => $this->city,
		];
		$columns = [];
		$values = [];
		foreach ($input as $key => $value) {
			if ($this->isAvailableColumn($key)) {
				$columns[] = $key;
				$values[] = $value;
			}
		}
		$query = "insert into " . self::$table . "(" . implode(', ', $columns) . ") values('" . implode("', '", $values) . "');";

		return $this->set($query);
	}

	/**
	 * Форматирование человека с преобразованием возраста и (или) пола (п.3 и п.4)
	 * в зависимости от параметров (возвращает новый экземпляр stdClass со всеми полями изначального класса)
	 *
	 * Возвращает отформатированный массив данных о персоне
	 *
	 * @return array
	 */
	public function getPerson(): array
	{
		return [
			'id' => $this->id,
			'firstname' => $this->firstname,
			'lastname' => $this->lastname,
			'birthdate' => $this->getTransformedDate($this->birthdate, 'ru'),
			'age' => !empty($this->birthdate) ? self::getAge($this->birthdate) : '',
			'sex' => self::getSex($this->sex),
			'city' => $this->city
		];
	}

	/**
	 * Проверка на наличие в БД искомой персоны
	 *
	 * @return bool
	 */
	public function hasPerson(): bool
	{
		$where = [];
		if (!empty($this->firstname)) $where[] = "firstname = '" . $this->firstname . "'";
		if (!empty($this->lastname)) $where[] = "lastname = '" . $this->lastname . "'";
		if (!empty($this->birthdate)) $where[] = "birthdate = '" . $this->getTransformedDate($this->birthdate, 'en') . "'";
		if (!empty($this->sex)) $where[] = "sex = '" . $this->sex . "'";
		if (!empty($this->city)) $where[] = "city = '" . $this->city . "'";

		$query = "select * from " . self::$table . " where " . implode(' and ', $where);
		$result = $this->get($query);

		return count($result);
	}

	/**
	 * Удаление человека из БД в соответствии с id объекта
	 *
	 * @param int $id
	 * @return void
	 */
	public function deletePerson(int $id): void
	{
		$query = "delete from " . self::$table . " where id = $id";
		$this->delete($query);
	}

	/**
	 * static преобразование даты рождения в возраст (полных лет)
	 *
	 * @param string $birthdate
	 */
	public static function getAge(string $birthdate)
	{
		$past = new \DateTimeImmutable($birthdate);
		$today = new \DateTimeImmutable('now');
		$bdate = $past->diff($today);
		return $bdate->y;
	}

	/**
	 * static преобразование пола из двоичной системы в текстовую (муж, жен)
	 *
	 * @param int $key
	 * @return string
	 */
	public static function getSex(int $key): string
	{
		return self::$sexList[$key];
	}
}
