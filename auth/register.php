<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    // Prepared statement for checking existing email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            $message = "Registered successfully! <a href='index.php?page=login'>Login</a>";
        } else {
            $message = "Registration failed.";
        }
        $stmt->close();
    }
    $check->close();
}
?>

<link rel="stylesheet" href="public/css/style.css">

<div class="form-container">
    <h2>Register</h2>
    <p><?= $message ?></p>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="index.php?page=login">Login here</a></p>
</div>