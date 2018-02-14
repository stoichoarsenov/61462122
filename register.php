<?php
//require_once 'header.php';
require 'header.php';
require 'database.php';
?>

<?php



// Create table
$sqlUserTable = "CREATE TABLE IF NOT EXISTS User (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(30) NOT NULL,
password VARCHAR(30) NOT NULL,
email VARCHAR(30) NOT NULL)";

//Izpulnqvane na zaqvka za syzdavane na tablica
mysqli_query($conn, $sqlUserTable);

if (!mysqli_query($conn, $sqlUserTable)) {
    echo "Error creating table: " . mysqli_error($conn);
}



// define variables and set to empty values
$nameErr = $emailErr = $pwdErr = $pwdCheckError = "";
$name = $email = $pwdErr = $pwdCheckError = $pwdDontMatch = "";
$error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])){
        $nameErr = "Name is required";
        $error = true;
    }elseif (check_name($name,$conn)){
        $nameErr = "Name is in use";
        $error = true;
    }
    else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $error = true;
    } else {
        $email = test_input($_POST["email"]);
    }
    if (!check_password($_POST["pwd"], $_POST["pwdCheck"])) {
        $pwdDontMatch = "Password don't match";
        $error = true;
    }
        if (empty($_POST["pwd"]) || empty($_POST["pwdCheck"])) {
        $pwdErr = "Password is required";
        $error = true;
    }
    if ($error === false)
    {
        save_reg($_POST['name'],$_POST['email'],$_POST['pwd'],$conn);

    }

}



function check_name($name, $conn)
{
    $sqlUsernameCheck = "SELECT 'username' FROM User Where username = '".$name."'";
    $result = mysqli_query($conn, $sqlUsernameCheck);
    if (mysqli_num_rows($result) > 0)
        while($row = mysqli_fetch_assoc($result)) {
            if ($row["username"] === $name)
                return false;
            else
                return true;
    }
}
function save_reg($name, $email, $password, $conn)
{

    $sqlUsernameInsert = "INSERT INTO `User` (`username`, `password`, `email`)VALUES('".$name."', '".$password."', '".$email."')";
    if (mysqli_query($conn, $sqlUsernameInsert)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

function check_password($password1, $password2)
{
    if ($password1 === $password2)
        return true;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

mysqli_close($conn);

?>

<h2>Registration</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Name: <input type="text" name="name">
    <span class="error">* <?php echo $nameErr; ?></span>
    <br>
    <br>
    E-mail: <input type="text" name="email">
    <span class="error">* <?php echo $emailErr; ?></span>
    <br>
    <br>
    Password: <input type="password" name="pwd">
    <span class="error">* <?php echo $pwdErr; ?></span>
    <br>
    <br>
    Repeat password: <input type="password" name="pwdCheck">
    <span class="error">* <?php echo $pwdDontMatch; ?></span>
    <br>
    <br>
    <input type="submit" name="submit" value="Register">
    <a href='login.php'>
        <input class="button" type="button" value="Login" />
    </a>
</form>

</body>


</html>


