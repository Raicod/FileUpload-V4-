<?php
    require_once("database.php");
    if(!$_SESSION['role']){
        header('Location: login.php');
    }
    
    $query = "SELECT * FROM dumps";
    $result = mysqli_query($conn, $query);

    if(isset($_POST['delete'])){
        $iddelete = $_POST['iddelete'];
        $querydelete = "DELETE FROM dumps WHERE id= ?;";
        $stmt = mysqli_prepare($conn, $querydelete);
        mysqli_stmt_bind_param($stmt, "s", $iddelete);
        mysqli_stmt_execute($stmt);
        header('Location: gallery.php');
    }

    if(isset($_POST['edit'])){
        $emailedit = $_POST['emailedit'];
        $iddelete = $_POST['iddelete'];
        $queryedit = "UPDATE dumps SET email = ? WHERE id = ?;";
        $stmt = mysqli_prepare($conn, $queryedit);
        mysqli_stmt_bind_param($stmt, "ss", $emailedit, $iddelete);
        mysqli_stmt_execute($stmt);
        header('Location: gallery.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
</head>
<body>
    <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor'){ ?>
    <a href="home.php">Back To Home</a>
    <br>
    <?php } ?>
    <a href="Logout.php">Logout</a>
    <br>
    <h1>Uploaded Image:</h1>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <p>Uploader:<?php echo $row['email']; ?> <br> </p>
        <?php if($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor'){ ?>
        <form action="" method="POST">
            <input type="hidden" name="iddelete" value="<?php echo $row['id']; ?>">
            <input type="text" name="emailedit" placeholder="Edit Uploader Name">
            <button name="edit">Edit</button> <br>
        </form>
        <?php } ?>
        <img src="<?php echo $row['filename']; ?>" alt="" srcset="">
        <?php if($_SESSION['role'] === 'admin'){ ?>
        <form action="" method="POST">
            <input type="hidden" name="iddelete" value="<?php echo $row['id']; ?>">
            <button name="delete">Delete Photo</button>
        </form>
        <?php } ?>
    <?php endwhile; ?>
</body>
</html>