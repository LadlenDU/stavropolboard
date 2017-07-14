<?php

	/*
	 * Класс базы данных
	 */
	class CSQL {

		var $handler = null;
		var $count = 0;

		/*
		 * Конструктор
		 */
		function __construct () {
			$this->connect();
		}

		/*
		 * Подключение к базе данных
		 */
		function connect() {
			$this->handler = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Could not connect: '.mysql_error());
			mysql_select_db(DB_NAME, $this->handler) or die('Could not select database');
			mysql_set_charset('utf8', $this->handler);
		}

		/*
		 * Подключение к базе данных
		 */
		function close() {
			mysql_close($this->handler);
		}
		/*
		 * Защита от не корректных данных
		 * $arg mixed
		 * return Безопасные данные
		 */
		function safe(&$arg) {
			if(is_array($arg)) {
				foreach($arg as &$a) {
					$a = mysql_real_escape_string($a, $this->handler);
				}
			} else {
				return mysql_real_escape_string($arg, $this->handler);
			}
		}

		/*
		 * Чтение строки с базы данных
		 * $query String
		 * return Array
		 */
		function query_fetch_row($query) {
			$result = $this->query($query);
			return $result ? $this->fetch($result, MYSQL_ASSOC) : $result;
		}

		/*
		 * Выполнение запроса базы данных
		 * $query String
		 * return Array
		 */
		function query($query) {
			$this->count++;
			$result = mysql_query($query, $this->handler);
			return $result;
		}

		/*
		 * Чтение строки
		 * $result Object
		 * return Array
		 */
		function fetch(&$result) {
			return $result ? mysql_fetch_array($result, MYSQL_ASSOC) : $result;
		}

	}

	// Создаем объект базы данных
	$db = new CSQL();

?>
