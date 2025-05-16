<?php
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];      // <== change here
            $_SESSION['user_name'] = $user['name'];

            //  Redirect to the shared dashboard
           header("Location: http://localhost/math_gineer/landing/dashboard.php");

            exit;
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User not found.";
    }
}
?>

<link rel="stylesheet" href="public/css/style.css">

<div class="form-container">
    <h2>Login</h2>
    <p style="color: red;"><?= $message ?></p>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>New user? <a href="index.php?page=register">Register here</a></p>
</div>
