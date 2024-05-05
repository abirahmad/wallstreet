<?php
session_start();
require('./include/config.php');
class User extends Dbconfig
{
	protected $hostName;
	protected $userName;
	protected $password;
	protected $dbName;
	private $userTable = 'user';
	private $dbConnect = true;

	public function __construct()
	{
		if ($this->dbConnect) {
			$database = new dbConfig();
			$this->hostName = 'localhost';
			$this->userName = 'root';
			$this->password = 'root';
			$this->dbName = 'wsd';
			$conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
			if ($conn->connect_error) {
				die("Error failed to connect to MySQL: " . $conn->connect_error);
			} else {
				$this->dbConnect = $conn;
			}
		}
	}
	public function adminLogin()
	{
		$errorMessage = '';
		if (!empty($_POST["login"]) && $_POST["email"] != '' && $_POST["password"] != '') {
			$email = $_POST['email'];
			$password = $_POST['password'];
			$sqlQuery = "SELECT * FROM " . $this->userTable . " 
				WHERE email='" . $email . "' AND password='" . md5($password) . "' AND status = 'active' AND type = 'administrator'";
			$resultSet = mysqli_query($this->dbConnect, $sqlQuery);
			$isValidLogin = mysqli_num_rows($resultSet);
			if ($isValidLogin) {
				$userDetails = mysqli_fetch_assoc($resultSet);
				$_SESSION["adminUserid"] = $userDetails['id'];
				$_SESSION["admin"] = $userDetails['first_name'] . " " . $userDetails['last_name'];
				header("location: dashboard.php");
			} else {
				$errorMessage = "Invalid login!";
			}
		} else if (!empty($_POST["login"])) {
			$errorMessage = "Enter Both user and password!";
		}
		return $errorMessage;
	}
	public function register()
	{
		$message = '';
		if (!empty($_POST["register"]) && $_POST["email"] != '') {
			$sqlQuery = "SELECT * FROM " . $this->userTable . " 
				WHERE email='" . $_POST["email"] . "'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);
			$isUserExist = mysqli_num_rows($result);
			if ($isUserExist) {
				$message = "User already exist with this email address.";
			} else {
				$authtoken = $this->getAuthtoken($_POST["email"]);
				$insertQuery = "INSERT INTO " . $this->userTable . "(first_name, last_name, email, password, authtoken) 
				VALUES ('" . $_POST["firstname"] . "', '" . $_POST["lastname"] . "', '" . $_POST["email"] . "', '" . md5($_POST["passwd"]) . "', '" . $authtoken . "')";
				$userSaved = mysqli_query($this->dbConnect, $insertQuery);
				$message = "User Created Successfully.";
			}
		}
		return $message;
	}

	public function adminLoginStatus()
	{
		if (empty($_SESSION["adminUserid"])) {
			header("Location: index.php");
		}
	}

	public function totalUsers($status)
	{
		$query = '';
		if ($status) {
			$query = " AND status = '" . $status . "'";
		}
		$sqlQuery = "SELECT * FROM " . $this->userTable . " 
		WHERE id !='" . $_SESSION["adminUserid"] . "' $query";
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}

	public function loginStatus()
	{
		if (empty($_SESSION["userid"])) {
			header("Location: login.php");
		}
	}

	public function login()
	{
		$errorMessage = '';
		if (!empty($_POST["login"]) && $_POST["loginId"] != '' && $_POST["loginPass"] != '') {
			$loginId = $_POST['loginId'];
			$password = $_POST['loginPass'];
			if (isset($_COOKIE["loginPass"]) && $_COOKIE["loginPass"] == $password) {
				$password = $_COOKIE["loginPass"];
			} else {
				$password = md5($password);
			}
			$sqlQuery = "SELECT * FROM " . $this->userTable . " 
				WHERE email='" . $loginId . "' AND password='" . $password . "' AND status = 'active'";

			$resultSet = mysqli_query($this->dbConnect, $sqlQuery);

			$isValidLogin = mysqli_num_rows($resultSet);

			if ($isValidLogin) {
				if (!empty($_POST["remember"]) && $_POST["remember"] != '') {
					setcookie("loginId", $loginId, time() + (10 * 365 * 24 * 60 * 60));
					setcookie("loginPass",	$password,	time() + (10 * 365 * 24 * 60 * 60));
				} else {
					$_COOKIE['loginId'] = '';
					$_COOKIE['loginPass'] = '';
				}
				$userDetails = mysqli_fetch_assoc($resultSet);
				$_SESSION["userid"] = $userDetails['id'];
				$_SESSION["name"] = $userDetails['first_name'] . " " . $userDetails['last_name'];
				header("location: index.php");
			} else {
				$errorMessage = "Invalid login! Or might be pending";
			}
		} else if (!empty($_POST["loginId"])) {
			$errorMessage = "Enter Both user and password!";
		}
		return $errorMessage;
	}

	public function getAuthtoken($email)
	{
		$code = md5(889966);
		$authtoken = $code . "" . md5($email);
		return $authtoken;
	}

	public function getUserList()
	{
		$sqlQuery = "SELECT * FROM " . $this->userTable . " WHERE id != '" . $_SESSION['adminUserid'] . "' ";

		if (!empty($_POST["search"]["value"])) {
			$sqlQuery .= 'AND (id LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= 'OR first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= 'OR last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= 'OR designation LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= 'OR status LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= 'OR mobile LIKE "%' . $_POST["search"]["value"] . '%") ';
		}

		if (!empty($_POST["order"])) {
			$sqlQuery .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
		} else {
			$sqlQuery .= 'ORDER BY id DESC ';
		}

		if ($_POST["length"] != -1) {
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$result = mysqli_query($this->dbConnect, $sqlQuery);

		$numRows = mysqli_num_rows($result);

		$userData = array();
		while ($users = mysqli_fetch_assoc($result)) {
			$userRows = array();
			$status = '';
			if ($users['status'] == 'active') {
				$status = '<span class="label label-success">Active</span>';
			} else if ($users['status'] == 'pending') {
				$status = '<span class="label label-warning">Inactive</span>';
			} else if ($users['status'] == 'deleted') {
				$status = '<span class="label label-danger">Deleted</span>';
			}
			$userRows[] = $users['id'];
			$userRows[] = ucfirst($users['first_name'] . " " . $users['last_name']);
			$userRows[] = $users['gender'];
			$userRows[] = $users['email'];
			$userRows[] = $users['mobile'];
			$userRows[] = $users['type'];
			$userRows[] = $status;
			$userRows[] = '<button type="button" name="update" id="' . $users["id"] . '" class="btn btn-warning btn-xs update">Update</button>';
			$userRows[] = '<button type="button" name="delete" id="' . $users["id"] . '" class="btn btn-danger btn-xs delete">Delete</button>';
			$userData[] = $userRows;
		}

		$output = array(
			"draw"              =>  intval($_POST["draw"]),
			"recordsTotal"      =>  $numRows,
			"recordsFiltered"   =>  $numRows,
			"data"              =>  $userData
		);
		echo json_encode($output);
	}
}
