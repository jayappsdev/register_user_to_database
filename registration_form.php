<?php
session_start();
if (isset($_SESSION["user"]))
{
    header("Location: main_form.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) 
        {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];
                
            $fileName = $_FILES["exampleFormControlFile1"]["name"];
            $tmpName = $_FILES["exampleFormControlFile1"]["tmp_name"];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;

            move_uploaded_file($tmpName, 'img/' . $newImageName);
                
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat))
            {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password)<8)
            {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password!=$passwordRepeat)
            {
                array_push($errors, "Password does not match");
            }

            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($dbConnection, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0)
            {
                array_push($errors, "Email already exist");
            }

            if (count($errors)>0)
            {
                foreach ($errors as $error)
                {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            else
            {
                $sql = "INSERT INTO users (full_name, email, password, image) VALUES ( ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($dbConnection);
                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                if ($prepareStmt)
                {
                    mysqli_stmt_bind_param($stmt,"ssss", $fullName, $email, $passwordHash, $newImageName);
                    mysqli_stmt_execute($stmt);
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: main_form.php");
                    die();   
                }
                else
                {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="registration_form.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
            </div>
            <div class="form-group">
                <label>Upload image</label>
                <div class="form-control">
                <input type="file" class="form-control-file" id="exampleFormControlFile1" accept=".png">
                </div>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <p>Already registered. Login <a href="login_form.php">here.</a></p>
        </div>
    </div>
</body>
</html>