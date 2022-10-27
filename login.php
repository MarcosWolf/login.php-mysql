<?php
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: home.php");
        exit;
    }

    require_once "conn.php";

    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter username.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter your password.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                $param_username = $username;
                
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){                    
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                session_start();
                                
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                
                                header("location: home.php");
                            } else{
                                $login_err = "Invalid username or password";
                            }
                        }
                    } else{
                        $login_err = "Invalid username or password";
                    }
                } else{
                    echo "Try again later";
                }
    
                mysqli_stmt_close($stmt);
            }
        }
        
        mysqli_close($link);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.style.css" rel="stylesheet">
</head>
<body>
    <section class="area-login">
        <div class="login">
            <div>
                <img src="img/logo.png">
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <?php 
                    if(!empty($login_err)){
                        echo '<div class="error alert alert-danger">' . $login_err . '</div>';
                    }        
                ?>
                
                <div>
                    <input type="text" name="username" required="required" autocomplete="off">
                    <span>Username</span>
                </div>
                <div>
                    <input type="password" name="password" required="required">
                    <span>Password</span>
                </div>
                <div>
                    <input type="submit" value="Login">
                </div>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </section>

    <script src="js/boostrap.min.js"></script>
</body>
</html>
