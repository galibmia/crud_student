<?php
require_once "function.php";
$info = '';
$task = $_GET['task'] ?? 'view';
$error = $_GET['error'] ?? '0';

if ('seed' == $task) {
    seed();
    $info = "Seeding Complete";
}

session_start();
if ('delete' == $task) {
    $id = filter_input(INPUT_GET, 'id');
    if ($id > 0) {
        deleteStudent($id);
        $_SESSION['delete_message'] = "Deleted successfully.";
        header('location: index.php?task=view');
        exit;
    }
}


$fname = '';
$lname = '';
$roll = '';

if (isset($_POST['submit'])) {
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname');
    $roll = filter_input(INPUT_POST, 'roll');
    $id = filter_input(INPUT_POST, 'id');
    if ($id) { //Update section
        if ($fname != '' && $lname != '' && $roll != '') {
            $result = updateStudent($id, $fname, $lname, $roll);
            if ($result) {
                header('location: index.php?task=view');
            } else {
                $error = '1';
            }
        }
    } else { //Add section
        if ($fname != '' && $lname != '' && $roll != '') {
            $result = addStudent($fname, $lname, $roll);
            if ($result) {
                header('location: index.php?task=view');
            } else {
                $error = '1';
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            margin-top: 50px;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: 900;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .danger {
            background-color: red;
            color: aliceblue;
            border-radius: 5px;
        }
        .success {
            background-color: green;
            color: aliceblue;
            border-radius: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-left: 10px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h1 class="text-center bold">Welcome to <br>Student Management System</h1>
                <p class="text-center bold">A simple projects to create, read, update and delete students data.</p>
                <!-- Nav Bar -->
                <nav class=" text-center">
                    <a href="/index.php?task=view">View Report | </a><a href="/index.php?task=add">Add New Student | </a><a href="/index.php?task=seed">Seeding</a>
                </nav>
                <!-- Seeding complete message -->
                <p><?php
                    if ('seed' == $task) {
                        echo $info;
                    } ?>
                </p>
                <!-- Error message -->
                <div>
                    <?php if ($error == 1) {
                        echo "<pre class='danger' ><p class='bold'> Duplicate Roll Found!</p></pre>";
                    }; ?>
                </div>
                <?php
                // Check if the delete message is set in the session
                if (isset($_SESSION['delete_message'])) {
                    // Display the message
                    echo '<pre class="success">'.'  '. $_SESSION['delete_message'] . '</pre>';
                    // Unset the session variable to clear the message after displaying it
                    unset($_SESSION['delete_message']);
                }
                ?>
            </div>
        </div>
    </div>
    <!-- 
                *******************************
                        View Section 
                ********************************
                -->
    <?php if ('view' == $task) : ?>
        <div class="container">
            <div class="row">
                <div class="column column-60 column-offset-20 text-center">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php displayData(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- 
                *******************************
                        Add Section 
                ********************************
                -->
    <?php if ('add' == $task) : ?>
        <div class="container">
            <div class="row">
                <div class="column column-60 column-offset-20">
                    <form method="post" action="/index.php?task=add">
                        <fieldset>
                            <label for="fname">First Name</label>
                            <input type="text" placeholder="Enter Your First Name" id="fname" name="fname">
                            <label for="lname">Last Name</label>
                            <input type="text" placeholder="Enter Your Last Name" id="lname" name="lname">
                            <label for="roll">Roll</label>
                            <input type="number" placeholder="Enter Your Roll" id="roll" name="roll">
                            <button class="button-primary" type="submit" name="submit">Save</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- 
                *******************************
                        Edit Section 
                ********************************
                -->
    <?php if ('edit' == $task) :
        $id = filter_input(INPUT_GET, 'id');
        $student = getStudentId($id);
        if ($student) : ?>

            <div class="container">
                <div class="row">
                    <div class="column column-60 column-offset-20">
                        <form method="post">
                            <fieldset>
                                <input type="hidden" value="<?php echo $id; ?>" name="id">
                                <label for="fname">First Name</label>
                                <input type="text" placeholder="Enter Your First Name" id="fname" name="fname" value="<?php echo $student['fname']; ?>">
                                <label for="lname">Last Name</label>
                                <input type="text" placeholder="Enter Your Last Name" id="lname" name="lname" value="<?php echo $student['lname']; ?>">
                                <label for="roll">Roll</label>
                                <input type="number" placeholder="Enter Your Roll" id="roll" name="roll" value="<?php echo $student['roll']; ?>">
                                <button class="button-primary" type="submit" name="submit">Update</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        endif;
    endif; ?>

</body>

</html>