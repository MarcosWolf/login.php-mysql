<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <section class="area-home">
        <div class="home">
            <h2>Home</h2>
            <p>Welcome, <b><?php echo $_SESSION['username']; ?></b>.</p>
            <a href="logout.php">Logout</a>
        </div>
    </section>

    <script src="js/boostrap.min.js"></script>
</body>
</html>