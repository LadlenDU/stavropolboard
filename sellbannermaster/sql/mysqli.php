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
			$this->handler = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Could not connect to database');
			mysqli_set_charset($this->handler, "utf8");
		}

		/*
		 * Подключение к базе данных
		 */
		function close() {
			mysqli_close($this->handler);
		}
		/*
		 * Защита от не корректных данных
		 * $arg mixed
		 * return Безопасные данные
		 */
		function safe(&$arg) {
			if(is_array($arg)) {
				foreach($arg as &$a) {
					$a = mysqli_real_escape_string($this->handler, $a);
				}
			} else {
				return mysqli_real_escape_string($this->handler, $arg);
			}
		}

		/*
		 * Чтение строки с базы данных
		 * $query String
		 * return Array
		 */
		function query_fetch_row($query) {
			$result = $this->query($query);
			return $result ? $this->fetch($result) : $result;
		}

		/*
		 * Выполнение запроса базы данных
		 * $query String
		 * return Array
		 */
		function query($query) {
			$this->count++;
			//$callers=debug_backtrace();
			//error_log($callers[1]['function']);
			//error_log($this->count." ".$query);
			//$tt = microtime(true);
			$result = mysqli_query($this->handler, $query);
			//error_log(sprintf("%.6f", microtime(true) - $tt));
			return $result;
		}

		/*
		 * Чтение строки
		 * $result Object
		 * return Array
		 */
		function fetch(&$result) {
			return $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : $result;
		}

	}

	// Создаем объект базы данных
	$db = new CSQL();

?>
