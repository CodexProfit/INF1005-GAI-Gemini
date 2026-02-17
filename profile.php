<?php
session_start();
include 'header.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$users = [];
$file = fopen('users.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
    $users[] = $line;
    if ($line[0] === $username) {
        $currentUser = $line;
    }
}
fclose($file);

?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Welcome, <?php echo htmlspecialchars($currentUser[2]); ?>!</h2>
        <a href="logout.php" class="btn btn-danger float-right">Logout</a>
        <a href="edit_profile.php" class="btn btn-primary float-right mr-2">Edit Profile</a>
        <img src="<?php echo htmlspecialchars($currentUser[4]); ?>" alt="Profile Photo" width="150" class="img-thumbnail mb-3">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($currentUser[0]); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($currentUser[3]); ?></p>
        <hr>
        <h3>Pet Info</h3>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($currentUser[5]); ?></p>
        <p><strong>Breed:</strong> <?php echo htmlspecialchars($currentUser[6]); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($currentUser[7]); ?></p>
        <?php if (isset($currentUser[8])): ?>
            <img src="<?php echo htmlspecialchars($currentUser[8]); ?>" alt="Pet Photo" width="150" class="img-thumbnail">
        <?php endif; ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h3 class="card-title">Community Members</h3>
        <ul class="list-group">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item">
                    <a href="view_profile.php?username=<?php echo urlencode($user[0]); ?>"><?php echo htmlspecialchars($user[0]); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<?php include 'footer.php'; ?>
