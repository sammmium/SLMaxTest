<?php

namespace src\Controllers;

use src\Models\People;
use src\Models\Person;

require_once 'MainController.php';

require_once __DIR__ .'/../Models/Person.php';
require_once __DIR__ .'/../Models/People.php';

class DashboardController extends MainController
{
	protected array $config;

	private array $lastnameList = [
		0 => [
			'Петрова',
			'Иванова',
			'Сидорова',
			'Семенова',
			'Бекмамбетова',
			'Ломоносова'
		],
		1 => [
			'Петров',
			'Иванов',
			'Сидоров',
			'Семенов',
			'Бекмамбетов',
			'Ломоносов'
		]
	];

	private array $firstnameList = [
		0 => [
			'Евгения',
			'Ирина',
			'Светлана',
			'Валентина',
			'Глория',
			'Валерия'
		],
		1 => [
			'Евгений',
			'Игорь',
			'Сергей',
			'Валентин',
			'Георгий',
			'Севостьян'
		],
	];

	private array $birthdateYears = [
		1980,
		1981,
		1982,
		1983,
		1984,
		1985
	];

	private static array $sexList = [
		'Женский',
		'Мужской'
	];

	private static array $cities = [
		'Москва',
		'Минск',
		'Владивосток',
		'Астрахань',
		'Пермь',
		'Полоцк',
		'Молодечно',
	];

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	/**
	 * Точка входа
	 *
	 * @return array
	 */
	public function index()
	{
		$content = [];
		$content['errors'] = (!empty($this->errors)) ? $this->errors : [];
		$people = new People();
		$content['people'] = $people->getPeople();

		return $this->getContent($content);
	}

	/**
	 * Генератор персоны
	 *
	 * @return void
	 */
	public function person_generate()
	{
		$sex = $this->generateSex();
		$person = [
			'firstname' => $this->generateFirstname($sex),
			'lastname' => $this->generateLastname($sex),
			'birthdate' => $this->generateBirthdate(),
			'sex' => $sex,
			'city' => $this->generateCity()
		];
		$people = new Person($person);

		header('Location: /');
		exit;
	}

	/**
	 * Генератор даты рождения
	 *
	 * @return string
	 */
	private function generateBirthdate(): string
	{
		$separator = '-';
		$year = $this->birthdateYears[rand(0, count($this->birthdateYears) - 1)];
		$month_number = rand(1, 12);
		$month = strlen($month_number) == 1 ? '0' . $month_number : $month_number;
		$day_number = ($month == 2) ? rand(1, 28) : rand(1, 30);
		$day = strlen($day_number) == 1 ? '0' . $day_number : $day_number;
		return $year . $separator . $month . $separator . $day;
	}

	/**
	 * Генератор имени
	 *
	 * @param $sex
	 * @return string
	 */
	private function generateFirstname($sex): string
	{
		return $this->generateItem($this->firstnameList, $sex);
	}

	/**
	 * Генератор фамилии
	 *
	 * @param $sex
	 * @return string
	 */
	private function generateLastname($sex): string
	{
		return $this->generateItem($this->lastnameList, $sex);
	}

	/**
	 * Генератор пола
	 *
	 * @return string
	 */
	private function generateSex(): string
	{
		return rand(0, 1);
	}

	/**
	 * Генератор населенного пункта
	 *
	 * @return string
	 */
	private function generateCity(): string
	{
		return $this->generateItem(self::$cities);
	}

	/**
	 * Генератор общий
	 *
	 * @param array $items
	 * @param $sex
	 * @return string
	 */
	private function generateItem(array $items, $sex = null): string
	{
		if (is_null($sex)) {
			return $items[rand(0, count($items) - 1)];
		}
		return $items[$sex][rand(0, count($items[$sex]) - 1)];
	}

	/**
	 * Удаление массива персон
	 *
	 * @return void
	 */
	public function people_selected_delete()
	{
		$personIdList = [];
		foreach ($_POST['person'] as $key => $value) {
			$personIdList[] = $key;
		}
		$people = new People($personIdList);
		$people->deletePeople();

		header('Location: /');
		exit;
	}

	/**
	 * Удаление персоны
	 *
	 * @param int $id
	 * @return void
	 */
	public function person_delete(int $id)
	{
		$person = new Person();
		$person->deletePerson($id);

		header('Location: /');
		exit;
	}
}
