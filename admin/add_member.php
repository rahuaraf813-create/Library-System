<?php
include("../includes/db.php");

$message = "";

if(isset($_POST['submit'])){

    $member_id = $_POST['member_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    if(!preg_match("/^M[0-9]{3}$/", $member_id)){
        $message = "Invalid Member ID!";
    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Invalid Email!";
    }

    else{

        $sql = "INSERT INTO members(member_id, firstname, lastname, birthday, email)
                VALUES('$member_id','$firstname','$lastname','$birthday','$email')";

        if(mysqli_query($conn,$sql)){
            $message = "Member Added Successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Add Member</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>Add Member</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<div class="mb-3">
<label>Member ID</label>
<input type="text" name="member_id" class="form-control" placeholder="M001" required>
</div>

<div class="mb-3">
<label>Firstname</label>
<input type="text" name="firstname" class="form-control" required>
</div>

<div class="mb-3">
<label>Lastname</label>
<input type="text" name="lastname" class="form-control" required>
</div>

<div class="mb-3">
<label>Birthday</label>
<input type="date" name="birthday" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="text" name="email" class="form-control" required>
</div>

<button type="submit" name="submit" class="btn btn-primary">
Add Member
</button>

</form>

</div>

</body>
</html>