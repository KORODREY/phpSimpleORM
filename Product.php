<?php

require_once('D:\Content\chemistry\admin\system\SECURITY.php');
$security = new security;
$security->check_session();



require_once('D:/Content/chemistry/resource/library/SQL_DB.php');

class Product {
	
	static $mysqli = null;

	public static function initSQLConnection() {
		$data_base = new DB;
		self::$mysqli = $data_base->connect();
	}

	/*
	 * Удаление продукта по идентификатору
	 */

	public static function delete($aId) {
		$lId = (int) $aId;
		if ($lId < 0 || $lId > PHP_INT_MAX) {
			return false;
		}
		$result = self::$mysqli->query("DELETE FROM `products_tests_orm` WHERE `id`=" . $lId);
		return $result;
	}

	/*
	 * Получение продукта по идентификатору
	 */

	public static function newInstance($aId) {
		$lId = (int) $aId;
		if ($lId < 0 || $lId > PHP_INT_MAX) {
			return false;
		}
		$result = self::$mysqli->query("SELECT * FROM `products_tests_orm` WHERE `id`=" . $lId . " LIMIT 1");
		if ($result !== false) {
			$row = $result->fetch_assoc();
			$product = new self();
			$product->id = $row['id'];
			$product->title = $row['title'];
			$product->price = $row['price'];
			$product->discount = $row['discount'];
			$product->description = $row['description'];
			return $product;
		} else {
			return false;
		}
	}

	public static function newEmptyInstance() {
		return new self();
	}

	public static function find($aCount, $aOptFrom = null) {
		$lFrom = is_null($aOptFrom) ? '' : (int) $aOptFrom . ', ';
		$query = "SELECT `id` FROM `products_tests_orm` LIMIT {$lFrom}{$aCount}";
		$result = self::$mysqli->query($query);
		if ($result !== false) {
			$lReturnProducts = array();
			while ($row = $result->fetch_assoc()) {
				$lReturnProducts[] = self::newInstance($row['id']);
			}
			return $lReturnProducts;
		} else {
			return false;
		}
	}

	public static function count() {

		$result = self::$mysqli->query("SELECT COUNT(`id`) as `count` FROM `products_tests_orm`");
		$count = (int) $result->fetch_assoc()['count'];
		return $count;
	}

	private $id;
	private $title;
	private $price;
	private $discount;
	private $description;

	private function __construct() {
		
	}

	public function getId() {
		return $this->id;
	}

	public function setTitle($aTitle) {
		$this->title = $aTitle;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setPrice($aPrice) {
		$this->price = $aPrice;
	}

	public function getPrice() {
		return $this->price;
	}

	public function setDiscount($aDiscount) {
		$this->discount = $aDiscount;
	}

	public function getDiscount() {
		return $this->discount;
	}

	public function setDescription($aDescription) {
		$this->description = $aDescription;
	}

	public function getDescription() {
		return $this->description;
	}

	public function save() {
		if (isset($this->id)) {
			$this->_update();
		} else {
			$this->_insert();
		}
	}

	private function _update() {
		mysql_query("UPDATE `products_tests_orm` SET `title`='{$this->title}', "
				. "`price`='{$this->price}', `discount`='{$this->discount}', "
				. "`description`='{$this->description}' WHERE `id`={$this->id}");
	}

	private function _insert() {
		self::$mysqli->query("INSERT INTO `products_tests_orm` (`title`, `price`, `discount`, `description`)" . " VALUES ('{$this->title}', '{$this->price}', '{$this->discount}', '{$this->description}')");
		$new_id = mysqli_insert_id($mysqli);
		$this->id = $new_id;
	}

}
