<?php

include('../config/db.php');

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM member WHERE member_id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$message = "";

if(isset($_POST['update'])){

    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid Email!";
    }

    else{

        $stmt = $conn->prepare("UPDATE member SET first_name=?, last_name=?, birthday=?, email=? WHERE member_id=?");
        $stmt->bind_param("sssss", $firstname, $lastname, $birthday, $email, $id);
        if($stmt->execute()){
            header("Location: members.php");
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Member</title>

<link rel="stylesheet" href="../assets/css/css/bootstrap.min.css">
</head>

<body>

<div class="container mt-5">

<h2>Edit Member</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<div class="mb-3">
<label>Firstname</label>
<input type="text" name="first_name" class="form-control"
value="<?php echo $row['first_name']; ?>" required>
</div>

<div class="mb-3">
<label>Lastname</label>
<input type="text" name="last_name" class="form-control"
value="<?php echo $row['last_name']; ?>" required>
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