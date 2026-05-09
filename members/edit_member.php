<?php

include("../includes/db.php");

$id = $_GET['id'];

$sql = "SELECT * FROM members WHERE member_id='$id'";
$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

$message = "";

if(isset($_POST['update'])){

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid Email!";
    }

    else{

        $update = "UPDATE members 
                   SET firstname='$firstname',
                       lastname='$lastname',
                       birthday='$birthday',
                       email='$email'
                   WHERE member_id='$id'";

        if(mysqli_query($conn,$update)){
            header("Location:view_members.php");
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Member</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Edit Member</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<div class="mb-3">
<label>Firstname</label>
<input type="text" name="firstname" class="form-control"
value="<?php echo $row['firstname']; ?>" required>
</div>

<div class="mb-3">
<label>Lastname</label>
<input type="text" name="lastname" class="form-control"
value="<?php echo $row['lastname']; ?>" required>
</div>

<div class="mb-3">
<label>Birthday</label>
<input type="date" name="birthday" class="form-control"
value="<?php echo $row['birthday']; ?>" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="text" name="email" class="form-control"
value="<?php echo $row['email']; ?>" required>
</div>

<button type="submit" name="update" class="btn btn-success">
Update
</button>

</form>

</div>

</body>
</html>