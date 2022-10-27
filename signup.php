<?php
    require_once "conn.php";
 
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
 
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
            $username_err = "Username can only contain letters, numbers, and underscores.";
        } else{
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                $param_username = trim($_POST["username"]);
                
                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "This username is already taken.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Please try again later.";
                }

                mysqli_stmt_close($stmt);
            }
        }
    
        if(empty(trim($_POST["password"]))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST["password"]);
        }
        
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Please confirm password.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Password did not match.";
            }
        }
    
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                
                if(mysqli_stmt_execute($stmt)){
                    header("location: login.php");
                } else{
                    echo "Please try again later.";
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
    <title>Sign up</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signup.style.css" rel="stylesheet">
</head>
<body>
    <section class="area-signup">
        <div class="signup">
            <?php 
                if(!empty($username_err)) {
                    echo '<div class="error alert alert-danger">' . $username_err . '</div>';
                } else if(!empty($password_err)) {
                    echo '<div class="error alert alert-danger">' . $password_err . '</div>';
                } else if(!empty($confirm_password_err)) {
                    echo '<div class="error alert alert-danger">' . $confirm_password_err . '</div>';
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <h2>Sign up</h2>
                </div>
                <div>
                   <input type="text" name="username" required="required" autocomplete="off">
                    <span>Username</span>
                </div>
                <div>
                    <input type="password" name="password" required="required">
                    <span>Password</span>
                </div>
                <div>
                    <input type="password" name="confirm_password" required="required">
                    <span>Confirm password</span>
                </div>
                <div>
                    <input type="submit" value="Register">
                </div>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </section>    

    <script src="js/boostrap.min.js"></script>
</body>
</html>