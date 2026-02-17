<?php
session_start();
include 'header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Check if username already exists
        $file = fopen('users.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($line[0] === $username) {
                $error = 'Username already taken.';
                break;
            }
        }
        fclose($file);

        if (empty($error)) {
            $_SESSION['onboarding_data']['username'] = $username;
            $_SESSION['onboarding_data']['password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['onboarding_step'] = 2;
            header('Location: step2.php');
            exit();
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Step 1: Username and Password</h2>
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
            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
