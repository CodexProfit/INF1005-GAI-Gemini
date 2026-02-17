<?php
session_start();
include 'header.php';

if (!isset($_GET['username'])) {
    header('Location: profile.php');
    exit();
}

$username = $_GET['username'];
$user = null;
$file = fopen('users.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
    if ($line[0] === $username) {
        $user = $line;
        break;
    }
}
fclose($file);

if ($user === null) {
    echo '<div class="alert alert-danger">User not found.</div>';
    include 'footer.php';
    exit();
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title"><?php echo htmlspecialchars($user[2]); ?>'s Profile</h2>
        <a href="profile.php" class="btn btn-secondary float-right">Back to Your Profile</a>
        <img src="<?php echo htmlspecialchars($user[4]); ?>" alt="Profile Photo" width="150" class="img-thumbnail mb-3">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user[0]); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($user[3]); ?></p>
        <hr>
        <h3>Pet Info</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user[5]); ?></p>
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($user[6]); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($user[7]); ?></p>
        <?php if (isset($user[8])): ?>
            <img src="<?php echo htmlspecialchars($user[8]); ?>" alt="Pet Photo" width="150" class="img-thumbnail">
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
