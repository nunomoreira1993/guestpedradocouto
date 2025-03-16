<?php

/**
 * PHP <-> `mysqli` Connector
 *
 * This class provides support for the PHP <> mysqli connector
 *
 * @package Seara Site Server
 * @subpackage mysqli connector
 * @version 5.5
 * @author Seara
 * @copyright 2004 - 2015 Seara.com
 *
 */

class db
{

  /////////////////////////////////////////////////
  // CLASS PROPERTIES
  /////////////////////////////////////////////////

	/**
	 * Where query data is collected if debug is enabled
	 * @var string[]
	 */
	public $arrDebug = array();

	/**
	 * Show connection and query errors to all users
	 * This option is always true for 'admin_su'
	 * @var bool
	 */
	public $show_errors = true;

	/**
	 * Stop processing the script if an error occur
	 * This option is always true for 'admin_su'
	 * @var bool
	 */
	private $halt_on_errors = false;

	/**
	 * Set debug level and display
	 * 0 = do nothing;
	 * 1 = collect data in array $this->arrDebug;
	 * 2 = display message in runtime (hidden html)
	 * @var int
	 */
	private $debug_mode = 0;

	/**
	 * Enable or disable query cache system
	 * @var bool
	 */
	private $cache_enabled = false;

	/**
	 * Set cache mode
	 * 1 = cache all queries;
	 * 2 = cache explicit queries only
	 * @var int
	 */
	private $cache_mode = 2;

	/**
	 * Set cache TTL, in seconds
	 * @var int
	 */
	private $cache_expires = 3600;

	/**
	 * Where to store cache files, relative to document root
	 * @var string
	 */
	private $cache_dir = "/fotos/cache";

	/**
	 * Exclude automatically from cache SQL queries
	 * that match this regular expression
	 * @var string
	 */
	private $cache_exclude_sql = "/(\bis_|\badministradores_|\bstaff)/i";

	/**
	 * Disable cache altogether in URL addresses
	 * that match this regular expression
	 * @var string
	 */
	private $cache_exclude_url = "/(\/administradores\/|\/staff\/)/i";

	/**
	 * This is the mysqli connection object
	 * @var object
	 */
	private $mysqli = false;

  /////////////////////////////////////////////////
  // CLASS METHODS
  /////////////////////////////////////////////////

	function __construct()
	{
		$this->cache_dir = $_SERVER['DOCUMENT_ROOT'] . $this->cache_dir;
	}

	function open($db, $login, $password, $odbc, $driver, $server)
	{		
		set_error_handler('mysqli_error_handler');
		
		$this->mysqli = mysqli_connect($server, $login, $password);
		if (!$this->mysqli->select_db($db)) {
			mysqli_error_handler($errno = "", $errstr = "mysqli_select_db(): Can't select database [" . $db . "]	", $errfile = "", $errline = "");
		}
		$this->mysqli->set_charset("utf8");
		restore_error_handler();
		$this->cache_set();
	}

	function query($sql, $return_type = "array", $cache = false)
	{
		$result = false;
		$this->test_connection();
		$this->start = $this->microtime_float();
		# detect query type
		$sql_type = $this->get_sql_type($sql);
		# SELECT statements
		if (in_array($sql_type, array("SELECT", "SHOW"))) {
			if ($this->cache_verify($sql, $cache)) {
				if ($this->cache_valid($sql)) {
					$result = $this->cache_read($sql);
					$this->debug($sql, count($result), 1);
					return $result;
				}
			}
			$query_result = $this->mysqli->query($sql);
			if ($this->has_errors()) {
				$this->debug_error($this->mysqli->errno, $this->mysqli->error, $sql);
				$result = false;
			}
			# result as object()
			else if ($return_type == "object") {
				return $query_result;
			}
			# result as array()
			else {
				$number_rows = intval($query_result->num_rows);
				if ($number_rows > 0) {
					$result = array();
					while ($row = $query_result->fetch_assoc()) {
						$result[] = $row;
					}
					$this->free_result($query_result);
				} else {
					$result = false;
				}
				$this->debug($sql, $number_rows);
			}
			if ($this->cache_verify($sql, $cache)) {
				$this->cache_write($sql, $result);
			}
		}
		# OTHER statements (INSERT/UPDATE/DELETE)
		else if (in_array($sql_type, array("INSERT", "REPLACE", "UPDATE", "DELETE", "TRUNCATE", "OPTIMIZE"))) {
			$this->mysqli->query($sql);
			if ($this->has_errors()) {
				$this->debug_error(mysqli_errno(), mysqli_error($this->mysqli), $sql);
			} else {
				# result as insert_id
				if ($sql_type == "INSERT") {
					$result = $this->mysqlid = $this->mysqli->insert_id;
				}
				# result as affected_rows
				else {
					$result = $this->mysqli->affected_rows;
				}
				$this->end = $this->microtime_float();
				$this->debug($sql);
			}
		} else {
			$this->debug_error(9999, 'Invalid SQL statement', $sql);
		}
		return $result;
	}

	/**
	 * * Compiles an insert string and runs the query
	 * @param string $table
	 * @param array $data - An associative array of update values
	 * @param bool $escape
	 * @return db::query() result
	 */
	function insert($table, $data = array(), $escape = true)
	{
		if ($escape == true) {
			$data = array_map_recursive(array($this, "escape_string"), array_map_recursive('stripslashes', $data));
		}
		$query = "INSERT INTO `" . $table . "` (`" . implode("`, `", array_keys($data)) . "`) VALUES ('" . implode("', '", array_values($data)) . "')";
		return $this->query($query);
	}

	/**
	 * Compiles an update string and runs the query
	 * @param string $table
	 * @param array $data - An associative array of update values
	 * @param mixed (string / array) $where - When string values are not escaped if $escape = true
	 * @param int $limit
	 * @param bool $escape
	 * @return db::query() result
	 */
	function update($table, $data = array(), $where = array(), $limit = null, $escape = true)
	{
		if ($escape == true) {
			$data = array_map_recursive(array($this, "escape_string"), array_map_recursive('stripslashes', $data));
			if (is_array($where) && !empty($where)) {
				$where = array_map_recursive(array($this, "escape_string"), array_map_recursive('stripslashes', $where));
			}
		}

		$i = 1;
		$fieldsSize = count($data);

		$query = "UPDATE `" . $table . "` SET ";
		foreach ($data as $field => $value) {
			$query .= "`" . $field . "` = '" . $value . "'";
			if ($i < $fieldsSize) {
				$query .= ", ";
			}
			$i++;
		}

		if (is_array($where) && !empty($where)) {
			$i = 1;
			$fieldsSize = count($where);
			$query .= " WHERE ";
			foreach ($where as $field => $value) {
				$query .= "`" . $field . "` = '" . $value . "'";
				if ($i < $fieldsSize) {
					$query .= "AND ";
				}
				$i++;
			}
		} elseif (strlen($where)) {
			$query .= " WHERE " . $where;
		}

		if (!is_null($limit)) {
			$query .= " LIMIT " . (int)$limit;
		}
		return $this->query($query);
	}

	function found_rows()
	{
		$result = $this->query("SELECT FOUND_ROWS() AS found_rows");
		return (isset($result[0]["found_rows"])) ? (int)$result[0]["found_rows"] : false;
	}

	function mysqlid($conn = false)
	{
		if (!$this->mysqlid) {
			$this->mysqlid = $this->mysqli->insert_id;
		}
		return $this->mysqlid;
	}

	function has_errors()
	{
		if (strlen(trim(mysqli_error($this->mysqli))) > 0) {
			return true;
		} else {
			return false;
		}
	}

	function test_connection()
	{
		if (is_object($this->mysqli)) {
			return true;
		} else {
			fatalError("Not connected to database.");
			return false;
		}
	}

	function get_sql_type($sql)
	{
		preg_match("/^(\w+)/", strtoupper(trim($sql)), $sql_type);
		if (isset($sql_type[1])) {
			return $sql_type[1];
		}
		return false;
	}

	function escape_string($s)
	{
		return $this->mysqli->escape_string($s);
	}

	function free_result($query)
	{
		if (is_object($query)) {
			mysqli_free_result($query);
		}
	}

	function close()
	{
		if (is_object($this->mysqli)) {
			$this->mysqli->close();
		}
	}

	// --- Start Debug --- //

	function debug($sql, $rows = 0, $cached = false)
	{
		if (intval($this->debug_mode) > 0) {
			if ($this->start) {
				$this->end = $this->microtime_float();
				$spent = " in " . number_format(($this->end - $this->start), 4) . "s";
				unset($this->start);
				unset($this->end);
			}
			if ($cached) {
				$cached = "[CACHED]";
			}
			# detect query type
			$sql_type = $this->get_sql_type($sql);
			if ($sql_type == "SELECT") {
				$extra = $rows . " row(s) retrieved";
			} else if ($sql_type == "INSERT") {
				$extra = "inserted";
			} else {
				$extra = $rows . " row(s) affected";
			}
			if ($this->debug_mode == 2) {
				echo "<!-- [MYSQL.DEBUG] " . $sql . " [" . $extra . $spent . "]" . $cached . " -->\n";
			} else {
				$this->arrDebug[] = "[MYSQL.DEBUG] " . $sql . " [" . $extra . $spent . "]" . $cached;
			}
		}
	}

	function debug_error($errno, $error, $sql)
	{
		if ($this->show_errors || (isset($_SESSION["admin_su"]) && $_SESSION["admin_su"] == 1)) {
			echo "<div style=\"background-color:steelblue; color:white; padding: 5px;\"><tt>[MYSQL.ERROR#$errno] " . $error . "</tt></div>";
			if (isset($_SESSION["admin_su"]) && $_SESSION["admin_su"] == 1) {
				echo "<div style=\"background-color:whitesmoke; color:black; padding: 5px; border: 1px solid steelblue; margin-bottom: 10px;\"><tt>" . $sql . "</tt></div>";
				$arrDebugBacktrace = debug_backtrace();
				echo "<div style=\"background-color:steelblue; color:white; padding: 5px;\"><tt>debug_backtrace();</tt></div>";
				echo "<textarea style=\"border:1px solid steelblue; width:100%; height:450px; margin:0px 0; padding:3px;\">";
				for ($i = count($arrDebugBacktrace); $i > 0; $i--) {
					unset($arrDebugBacktrace[$i]["object"]);
					unset($arrDebugBacktrace[$i]["type"]);
					print_r($arrDebugBacktrace[$i]);
				}
				echo "</textarea>";
			}
		}
		if ($this->halt_on_errors || (isset($_SESSION["admin_su"]) && $_SESSION["admin_su"] == 1)) {
			die();
		}
	}

	// --- End Debug --- //

	// --- Start Query Cache --- //

	function cache_set()
	{
		if ($this->cache_enabled) {
			if ((!defined('BACKOFFICE') || BACKOFFICE == false) && (!isset($_SESSION['LoginState'])) && (!preg_match($this->cache_exclude_url, $_SERVER["REQUEST_URI"]))) {
				if (!is_dir($this->cache_dir)) {
					if (is_writable($_SERVER['DOCUMENT_ROOT'] . "/fotos")) {
						$oldumask = umask(0);
						mkdir($this->cache_dir, 0777, true);
						umask($oldumask);
						chmod($this->cache_dir, 0777);
					}
				}
				if (is_dir($this->cache_dir) && is_writable($this->cache_dir)) {
						//echo "<!-- [DEBUG] Query Cache is enabled -->\n";
					return true;
				}
			}
		}
		# Disable cache
		$this->cache_enabled = false;
		$this->cache_mode = false;
		//echo "<!-- [DEBUG] Query Cache is disabled -->\n";
	}

	function cache_verify($sql, $cache)
	{
		if (($this->cache_mode == 1) || ($this->cache_mode == 2 && $cache)) {
			return true;
		}
		return false;
	}

	function cache_valid($sql)
	{
		$cache_file = $this->cache_dir . "/query_" . md5($sql) . ".cache";
		if (file_exists($cache_file)) {
			$diff = time() - (filemtime($cache_file));
			if ($diff <= $this->cache_expires) {
				return true;
			} else {
				unlink($cache_file);
			}
		}
		return false;
	}

	function cache_read($sql)
	{
		$cache_file = $this->cache_dir . "/query_" . md5($sql) . ".cache";
		if (file_exists($cache_file)) {
			$contents = file_get_contents($cache_file);
			$contents = $this->base64_unserialize($contents);
			return $contents;
		} else {
			return false;
		}
	}

	function cache_write($sql, $result)
	{
		$cache_file = $this->cache_dir . "/query_" . md5($sql) . ".cache";
		if (!file_exists($cache_file)) {
			$contents = $this->base64_serialize($result);
			file_put_contents($cache_file, $contents);
			chmod($cache_file, 0666);
		}
	}

	function cache_purge()
	{
		if (is_dir($this->cache_dir)) {
			$handler = opendir($this->cache_dir);
			while ($cache_file = readdir($handler)) {
				if ($cache_file != "." && $cache_file != ".." && $cache_file != ".htaccess" && preg_match("/.*\.cache/", $cache_file)) {
					$diff = time() - (filemtime($this->cache_dir . "/" . $cache_file));
					if ($diff > $this->cache_expires) {
						@unlink($this->cache_dir . "/" . $cache_file);
						// echo "<tt>Purged cache file: ".$cache_file."</tt><br>";
					}
				}
			}
			closedir($handler);
		}
	}

	function base64_serialize($arr)
	{
		if (is_array($arr)) {
			foreach ($arr as $k => $v) {
				if (is_array($v)) {
					$ret[$k] = $this->base64_serialize($v);
				} else {
					$ret[$k] = base64_encode($v);
				}
			}
		} else {
			return false;
		}
		return serialize($ret);
	}

	function base64_unserialize($str)
	{
		$arr = unserialize($str);
		if (is_array($arr)) {
			foreach ($arr as $k => $v) {
				if (is_array(unserialize($v))) {
					$ret[$k] = $this->base64_unserialize($v);
				} else {
					$ret[$k] = base64_decode($v);
				}
			}
		} else {
			return false;
		}
		return $ret;
	}

	// --- End Query Cache --- //

	// --- Start Auxiliary Functions --- //

	function getLimit($sql)
	{
		$matches = array();
		preg_match('/limit\s\d*[\,\s|\s\,\s|\s\,\d]*[\s]?$/i', $sql, $matches);
		if (!empty($matches) && isset($matches[0]) && strlen($matches[0])) {
			return $matches[0];
		}
		return false;
	}

	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	// --- End Auxiliary Functions --- //

}

// --- Custom mysqli Error Handler --- //
function mysqli_error_handler($errno = "", $errstr = "", $errfile = "", $errline = "")
{
	pr($errstr);
	global $db;
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	header('Retry-After: 3600');

	$error_html = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
	<html>
	<head>
		<title>503 Service Temporarily Unavailable</title>
	</head>
	<body>
		<h1>Service Temporarily Unavailable</h1>
		<p>The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.</p>
		{ERROR_503}
	</body>
	</html>';

	if ($db->show_errors) {
		$errstr = "<p><span style=\"background-color: yellow;\"><tt>" . $errstr . "</tt></span></p>";
	} else {
		$errstr = '';
	}
	exit(str_replace("{ERROR_503}", $errstr, $error_html));
}