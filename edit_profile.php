<?php
session_start();
include 'header.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$users = [];
$currentUserIndex = -1;

$file = fopen('users.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
    $users[] = $line;
    if ($line[0] === $username) {
        $currentUserIndex = count($users) - 1;
    }
}
fclose($file);

$currentUser = $users[$currentUserIndex];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Remove the user from the array
        unset($users[$currentUserIndex]);

        // Write the updated array back to the CSV file
        $file = fopen('users.csv', 'w');
        foreach ($users as $user) {
            fputcsv($file, $user);
        }
        fclose($file);

        // Logout and redirect
        session_unset();
        session_destroy();
        header('Location: login.php?message=deleted');
        exit();
    }

    // Update user data
    $users[$currentUserIndex][2] = $_POST['name'];
    $users[$currentUserIndex][3] = $_POST['contact'];

    if (!empty($_POST['password'])) {
        $users[$currentUserIndex][1] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_photo']['name']);
        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
            $users[$currentUserIndex][4] = $uploadFile;
        }
    }

    $users[$currentUserIndex][5] = $_POST['pet_name'];
    $users[$currentUserIndex][6] = $_POST['pet_breed'];
    $users[$currentUserIndex][7] = $_POST['pet_age'];

    if (isset($_FILES['pet_photo']) && $_FILES['pet_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['pet_photo']['name']);
        if (move_uploaded_file($_FILES['pet_photo']['tmp_name'], $uploadFile)) {
            $users[$currentUserIndex][8] = $uploadFile;
        }
    }

    // Write the updated array back to the CSV file
    $file = fopen('users.csv', 'w');
    foreach ($users as $user) {
        fputcsv($file, $user);
    }
    fclose($file);

    header('Location: profile.php?message=updated');
    exit();
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Edit Profile</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($currentUser[2]); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($currentUser[3]); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (leave blank to keep current password)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="profile_photo">Profile Photo</label>
                <input type="file" class="form-control-file" id="profile_photo" name="profile_photo">
                <img src="<?php echo htmlspecialchars($currentUser[4]); ?>" alt="Profile Photo" width="100" class="mt-2">
            </div>
            <hr>
            <h3>Pet Info</h3>
            <div class="form-group">
                <label for="pet_name">Pet Name</label>
                <input type="text" class="form-control" id="pet_name" name="pet_name" value="<?php echo htmlspecialchars($currentUser[5]); ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_breed">Pet Breed</label>
                <input type="text" class="form-control" id="pet_breed" name="pet_breed" value="<?php echo htmlspecialchars($currentUser[6]); ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_age">Pet Age</label>
                <input type="number" class="form-control" id="pet_age" name="pet_age" value="<?php echo htmlspecialchars($currentUser[7]); ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_photo">Pet Photo</label>
                <input type="file" class="form-control-file" id="pet_photo" name="pet_photo">
                <?php if (isset($currentUser[8])): ?>
                    <img src="<?php echo htmlspecialchars($currentUser[8]); ?>" alt="Pet Photo" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile? This action cannot be undone.')">Delete Profile</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
