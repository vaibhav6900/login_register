<?php
session_start();
require_once 'config.php';
$email = "";
$password = "";
$err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['email'])) || empty(trim($_POST['password']))) {
        $err = "Please enter username + password";
    } else {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

    }

    // if there is no error
    if (empty($err)) {
        $sql = "SELECT id, email, pass,name FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;
        // executing statement

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {

                mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $name);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashed_password)) {

                        $_SESSION["email"] = $email;
                        $_SESSION["id"] = $id;
                        $_SESSION["password"] = $password;
                        $_SESSION["name"] = $name;
                        $_SESSION["loggedin"] = true;

                        header("location: displaytable.php");
                    }
                    // if password dont match
                     else {
                        header("location: errors.php");
                    }
                }
            }
        }
    }
    // if any field is empty
     else {
        header("location: errors.php");
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Login page</title>
</head>

<body>
    <!-- nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary mb-4">
        <a class="navbar-brand" href="#">Login page</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

    </nav>
    <!-- form -->
    <div class="container ">
        <div class="container-header ">
            <h1>Login<h1>
        </div>
        <form action="" method="post">

            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
            </div>


            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>