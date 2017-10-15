<?php

import('plugins.generic.externalFeed.simplepie.SimplePie');

class Newsletter {
	var $_conn;

	/**
	 * Constructor.
	 */
	function __construct() {
		$servername = "localhost";
		$username = "sympa";
		$password = "CHL-sympa007";
		$dbname = "sympa";
		$this->_conn = new mysqli($servername, $username, $password, $dbname);
		if ($this->_conn->connect_error)
			$this->_conn = null;
	}

	function __destruct() {
		if ($this->_conn)
			$this->_conn->close();
	}

	function numberOfSubscribers($short = true) {
		if (!$this->_conn) return 0;
		$sql = 'SELECT COUNT(user_subscriber)
		               FROM subscriber_table
	        	WHERE robot_subscriber="annales.lebesgue.fr"
	        	  AND list_subscriber="news"';
		$result = $this->_conn->query($sql);
		if ($result->num_rows > 0)
			$numberOfSubscribers = array_values($result->fetch_assoc())[0];
		else
			$numberOfSubscribers = 0;
		if ($short) {
			if ($numberOfSubscribers >= 10000000) {
				$numberOfSubscribers = floor($numberOfSubscribers/1000000);
				$numberOfSubscribers .= '<span class="smallspace"></span>M';
			} else if ($numberOfSubscribers >= 1000000) {
				$numberOfSubscribers = floor($numberOfSubscribers/100000) / 10;
				$numberOfSubscribers .= '<span class="smallspace"></span>M';
			} else if ($numberOfSubscribers >= 10000) {
				$numberOfSubscribers = floor($numberOfSubscribers/1000);
				$numberOfSubscribers .= '<span class="smallspace"></span>K';
			} else if ($numberOfSubscribers >= 1000) {
				$numberOfSubscribers = floor($numberOfSubscribers/100) / 10;
				$numberOfSubscribers .= '<span class="smallspace"></span>K';
			}
		}
		return $numberOfSubscribers;
	}

	function isSubscriber($email) {
		if (!$this->_conn) return false;
		$sql = 'SELECT COUNT(user_subscriber)
		               FROM subscriber_table
	        	WHERE user_subscriber=?
			  AND robot_subscriber="annales.lebesgue.fr"
		          AND list_subscriber="news"';
		$stmt = $this->_conn->prepare($sql);
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		return $result > 0;
	}

}

?>
