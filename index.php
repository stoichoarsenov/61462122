<?php
/**
 * Created by PhpStorm.
 * User: stoicho
 * Date: 05.02.18
 * Time: 21:10
 */
require 'header.php';
require 'database.php';


// Create table
$sqlTaskTable = "CREATE TABLE IF NOT EXISTS `myDB`.`Task` ( 
`id` INT NOT NULL AUTO_INCREMENT , 
`task` VARCHAR(255) NOT NULL UNIQUE , 
`dateCreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`dueDate` DATE NOT NULL ,
`status` BOOLEAN NOT NULL , 
PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;

";

//Izpulnqvane na zaqvka za syzdavane na tablica
mysqli_query($conn, $sqlTaskTable);

if (!mysqli_query($conn, $sqlTaskTable)) {
    echo "Error creating table: " . mysqli_error($conn);
}

$currDate = date('Y-m-d');
$taskErr = $dueDateErr = '';
$error == false;

foreach ($_POST['users'] as $users){
    $user = $_POST['users'];
}
//    var_dump($user);

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (empty($_POST["tasks"])) {
        $taskErr = "Task is required";
        $error == true;
    }
    if (empty($_POST['dueDate'])) {
        $dueDateErr = "Date must be valid";
        $error == true;
    }
    if ($error == false) {
        save_reg($_POST['tasks'], $_POST['dueDate'], $_POST['status'], $user , $conn);
    }

}

function save_reg($task, $dueDate, $status, $user, $conn)
{
    $sqlTaskInsert = "INSERT INTO `Task` (`task`, `dueDate`, `status`) VALUES ('" . $task . "', '" . $dueDate . "', '" . $status . "')";
    if (mysqli_query($conn, $sqlTaskInsert)) {
        $taskId = mysqli_insert_id($conn);
    $sqlUserInsert = "INSERT INTO `TaskAssigment` (`taskId`, `user1`, `user2`, `user3`) VALUES ('" . $taskId . "', '" . $user[0] . "', '" . $user[1] . "', '" . $user[2] . "')";
        if (mysqli_query($conn, $sqlUserInsert)) {
            echo "New record created successfully";
            echo $taskId;
        }
        else{
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        $taskId = mysqli_insert_id($conn);
        echo $taskId;
        echo "Error: " . mysqli_error($conn);
    }
}

function print_res($conn)
{
    $sqlSelect = "SELECT `id`, `task`, `dateCreated`,`dueDate`,`status` FROM `Task` ORDER BY dueDate DESC";

    if (mysqli_query($conn, $sqlSelect)) {


        $result = mysqli_query($conn, $sqlSelect);
//    print_r($result);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo
                    "
                 <div class='row'>
                 <div class=\"col-sm-3\" style=\"background-color: aqua\"><br> Номер на задачата: " . $row["id"] . "<hr></div>
                 <div class=\"col-sm-3\" style=\"background-color: cadetblue\"><br> Задача: " . $row["task"] . "<hr></div>
                 <div class=\"col-sm-3\" style=\"background-color: red\"><br> Задачата е валидна до: " . $row["dueDate"] . "<hr></div>
                 <div class=\"col-sm-3\" style=\"background-color: deeppink\"><br> Задачата е създадена: " . $row["dateCreated"] . "<hr></div></div>";
            }
        }
    } else {
        echo 'Не мога да се свържа';
    }
}

function getAllUser($conn)
{

    $sqlSelect = "SELECT `id`, `username` FROM `User` ORDER BY `username` DESC";
    if (mysqli_query($conn, $sqlSelect)) {


        $result = mysqli_query($conn, $sqlSelect);
//    print_r($result);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo
                    "<option value=" . $row["username"] . ">" . $row["username"] . "</option>";
            }
        } else {
            echo 'Не мога да се свържа';
        }
    };
}

//var_dump($_POST['users[]']);
?>


<div class="container" style="padding: 80px">
    <div class="col-xs-3 col-md-12">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="text-center">
                    <h3>New Task</h3>
                </div>
            </div>
            <div class="row">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="col-sm-12">
                        Task: <input type="text" name="tasks">
                        <span class="error">* <?php echo $taskErr; ?></span>
                    </div>
                    <br>
                    <br>
                    <div class="col-sm-12">
                        Краен срок: <input type="date" name="dueDate" value="<?php echo $currDate; ?>"/>
                        <span class="error">* <?php echo $dueDateErr; ?></span>
                    </div>
                    <br>

                    <div class="col-sm-12">
                    <br>
                    Статус на задачата: <select name="status">
                        <option value="0">Completed</option>
                        <option value="1">In progress</option>
                    </select>
                    </div>
                    <br>
                    <br>
                    <div class="col-sm-12">
                         Задачата е насочена към:
                            <select name="users[]" multiple>
                                <?php getAllUser($conn) ?>
                            </select>
                        </div>
                    <input class="button" type="submit" name="submit" value="Create task">
                </form>

            </div>
        </div>
    </div>

    Таблица със служители 1 задача да се дава на повече от един служител
    diyandinev2@gmail.com

    <div style="text-align: center">Текущи задачи :</div>
    <br>
    <div class="expand">

        <div><?php print_res($conn); ?></div>
        <div style="text-align: center">
            <button class="button" id="hide">Hide</button>
        </div>

    </div>
    <div style="text-align: center">
        <button class="button" id="show">Show</button>
    </div>




