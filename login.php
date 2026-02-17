<?php
session_start();
include 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $file = fopen('users.csv', 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
        if ($line[0] === $username && password_verify($password, $line[1])) {
            $_SESSION['username'] = $username;
            header('Location: profile.php');
            exit();
        }
    }
    fclose($file);

    $error = 'Invalid username or password.';
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="mt-3">
                Don't have an account? <a href="index.php">Sign up here</a>.
            </p>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
