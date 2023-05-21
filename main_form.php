<?php
session_start();
if (!isset($_SESSION["user"]))
{
    header("Location: login_form.php");
}

include("database.php");
$query = mysqli_query($dbConnection, "select * from users");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Screen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <td style="font-weight: bolder;">All registered users</td>
                </tr>
                <tr>
            </thead>
            <?php
            while($row = mysqli_fetch_array($query))
            {
            ?>
                <td><?php echo $row['full_name']; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
        <br>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>
</body>
</html>
