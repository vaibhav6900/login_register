<?php
// registration page
require_once 'config.php';
$username="";
$password="";
$confirm_password="";
$username_err="";
$password_err="";
$confirm_password_err="";
$email="";
$email_err="";

if($_SERVER['REQUEST_METHOD']=="POST")
{
    // CHECK IF USERNAME IS EMPTY
    if(empty(trim($_POST['username'])))
    {
        $username_err="Username cannot be empty";
    }
    else{
        $sql="SELECT id from user WHERE name = ?";
        // prepare a statement
        $stmt=mysqli_prepare($conn,$sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt,"s",$param_username);
            // setting the value of param username
            $param_username = trim($_POST['username']);
            // trying to execute this statement
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1)
                {
                    $username_err="This username is already taken";
                }
                else{
                    $username=trim($_POST['username']);
                }
            }
            // if statement does not execute
            else{
                echo "Something went wrong";
            }
        }

    }
    mysqli_stmt_close($stmt);
    // check for password
    if(empty(trim($_POST['password'])))
    {
        $password_err="Password cannot be empty";
    }
    elseif(strlen(trim($_POST['password'])) < 8){
        $password_err = "Password cannot be less than 8 characters";
    }
    else{
        $password = trim($_POST['password']);
    }
    // check for confirm password
    if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
        $password_err = "Password is not matching";
    }
    // check for email
    if(empty(trim($_POST['email'])))
    {
        $email_err="Email cannot be empty";
    }
    else{
        $email= trim($_POST['email']);
    }
    // if there are no errors
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err))
    {
        $sql = "INSERT INTO user (name, email, pass) VALUES (?, ?,?)";
        $stmt=mysqli_prepare($conn,$sql);
     
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt,"sss",$param_username,$param_email,$param_password);
            // setting these parameters
            $param_username=$username;
            $param_email=$email;
            $param_password=password_hash($password, PASSWORD_DEFAULT);
            // trying to execute the query
            if(mysqli_stmt_execute($stmt))
            {
                header("location: login.php");
            }
            else{
                echo "Something went wrong .... Can't redirect";
            }
        }
        mysqli_stmt_close($stmt);
    }
    else{
        if(!empty($username_err) ||!empty($email_err) ||!empty($password_err))
        {
            header("location: errors.php");
        }
    }
    mysqli_close($conn);
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

    <title>Registration Page</title>
</head>

<body>
    <!-- nav bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-primary mb-4">
        <a class="navbar-brand" href="#">Registration form</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
       
    </nav>
    <!-- form -->
    <div class="container ">
        <div class="container-header "><h1>Sign up<h1></div>
    <form action="" method="post">
    <div class="form-group">
            <label for="exampleInputName">Name</label>
            <input type="text" class="form-control" name="username" id="exampleInputName" placeholder="Enter Username">
            
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="text" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword2">Confirm Password</label>
            <input type="password" class="form-control" id="exampleInputPassword2"  name="confirm_password"placeholder="Confirm Password">
        </div>
      
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>