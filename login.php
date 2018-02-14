<?php
//require_once 'header.php';
/**
 * Created by PhpStorm.
 * User: stoicho
 * Date: 01.02.18
 * Time: 20:36
 */

$user = $_POST['name'];
$pass = $_POST['pwd'];

$servername = "localhost";
$username = "root";
$password = "root";
$database = "myDB";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userId = "SELECT id FROM `User` WHERE username = '".$user."'";
$userIdConn = mysqli_query($conn, $userId);
$userIdConnRow = mysqli_fetch_array($userIdConn);
//echo $userIdConnRow[0];


$userDTB = "SELECT username FROM `User` WHERE username = '".$user."'";
$userDTBConn = mysqli_query($conn, $userDTB);
$userDTBConnRow = mysqli_fetch_array($userDTBConn);
//echo $userDTBConnRow[0];



$userPwdCheck = "SELECT password FROM `User` WHERE id = '".$userIdConnRow[0]."'";
$accPassResult = mysqli_query($conn, $userPwdCheck);
$accPassResultRow = mysqli_fetch_array($accPassResult);
//echo $accPassResultRow[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (($user === $userDTBConnRow[0]) && ($pass === $accPassResultRow[0]))
    {
        echo 'Вие сте логнат в системата';
       // setcookie('user', $userDTBConnRow[0], time() + (86400 * 30), "/"); // 86400 = 1 day
        header( "Location: index.php" ); die;
    }
    else {
        echo 'Грешно въведено потребителско име или парола';
    }

}

?>



<h2>Login</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
Name: <input type="text" name="name">
<br>
<br>
Password: <input type="password" name="pwd">
<br>
<br>
<input type="submit" name="submit" value="Login">
    <a href='register.php'>
        <input class="button" type="button" value="Register" />
    </a>
</form>