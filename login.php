<?php
    require_once("database.php");

    if(isset($_POST['login'])){
        $secret = "6LfaF9QnAAAAAMtRIrUQtuoVaX5oAYryY9cJjjK_";
        $response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
        $data = file_get_contents($url);
        $row = json_decode($data, true);

        if($row['success'] != true){
            header('Location: Logout.php');
            die();
        }

        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $hashedPass = SHA1($password);
        $query = "SELECT * FROM users WHERE email = ? AND password=?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if($result->num_rows > 0 ){
            $row = $result->fetch_assoc();

            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            
            if($_SESSION['role'] === 'viewer'){
                header('Location: gallery.php');
            }
            else if($_SESSION['role'] != 'viewer'){
                header('Location: home.php');
            }
            
        }else{
            echo '<script>
            alert("Login Failed");
            window.location.href = "login.php";
            </script>';
        }
    }
?>  


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <form method="POST">
        <h1>Login Page</h1>
        <label for="email">Email: </label>
        <input type="email" name="email"> <br>
        <label for="password">Password: </label>
        <input type="password" name="password"> <br>
        <div class="g-recaptcha" data-sitekey="6LfaF9QnAAAAAPWPYc_hA8fzSj7-TNKGkpljrWXY"></div>
        <input type="submit" name="login"> <br>
        Don't have account? <a href="register.php">Sign in</a>
    </form>
</body>
</html>