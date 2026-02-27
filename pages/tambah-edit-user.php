<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $insertUser = mysqli_query($koneksi, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$pass')");
    if ($insertUser) {
        header("location:?page=user");
    } else {
        echo "Gagal menambahkan user!";
    }
}
if (isset($_GET['id'])) {
    $idEdit = base64_decode($_GET['id']);
    $selectUser = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$idEdit'");
    $row = mysqli_fetch_assoc($selectUser);

    if (isset($_POST['edit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        if ($pass != null) {
            $updateUser = mysqli_query($koneksi, "UPDATE users SET name='$name', email='$email', password='$pass', updated_at=now() WHERE id='$idEdit'");
        } else {
            $updateUser = mysqli_query($koneksi, "UPDATE users SET name='$name', email='$email', updated_at=now() WHERE id='$idEdit'");
        }
        if ($updateUser) {
            header("location:?page=user");
        }
    }
}
?>
<div class="card">
    <div class="card-header">
        <h1><?php echo (isset($_GET['id'])) ? 'Edit ' : 'Add ' ?>User</h1>
    </div>
    <div class="card-body">
        <form action="" method="post">
            <label for="" class="form-label">User Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo (isset($_GET['id'])) ? $row['name'] : '' ?>" required>
            <label for="" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo (isset($_GET['id'])) ? $row['email'] : '' ?>" required>
            <label for="" class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
            <button type="submit" class="btn btn-primary my-2" name="<?php echo (isset($_GET['id'])) ? 'edit' : 'add' ?>"><?php echo (isset($_GET['id'])) ? 'Save' : 'Add' ?></button>
        </form>
    </div>
</div>