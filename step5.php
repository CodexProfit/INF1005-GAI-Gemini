<?php
session_start();
include 'header.php';

if (!isset($_SESSION['onboarding_step']) || $_SESSION['onboarding_step'] < 5) {
    header('Location: step4.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        $_SESSION['onboarding_step'] = 4;
        header('Location: step4.php');
        exit();
    }

    if (isset($_POST['save'])) {
        $file = fopen('users.csv', 'a');
        fputcsv($file, $_SESSION['onboarding_data']);
        fclose($file);

        // Log the user in and redirect to profile
        $_SESSION['username'] = $_SESSION['onboarding_data']['username'];
        unset($_SESSION['onboarding_data']);
        unset($_SESSION['onboarding_step']);
        header('Location: profile.php');
        exit();
    }
}

$data = $_SESSION['onboarding_data'];
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Step 5: Confirmation and Save</h2>
        <p>Please review your information before saving.</p>
        <ul>
            <li><strong>Username:</strong> <?php echo htmlspecialchars($data['username']); ?></li>
            <li><strong>Name:</strong> <?php echo htmlspecialchars($data['name']); ?></li>
            <li><strong>Contact:</strong> <?php echo htmlspecialchars($data['contact']); ?></li>
            <li><strong>Profile Photo:</strong> <img src="<?php echo htmlspecialchars($data['profile_photo']); ?>" alt="Profile Photo" width="100"></li>
            <li><strong>Pet Name:</strong> <?php echo htmlspecialchars($data['pet_name']); ?></li>
            <li><strong>Pet Breed:</strong> <?php echo htmlspecialchars($data['pet_breed']); ?></li>
            <li><strong>Pet Age:</strong> <?php echo htmlspecialchars($data['pet_age']); ?></li>
            <?php if (isset($data['pet_photo'])): ?>
                <li><strong>Pet Photo:</strong> <img src="<?php echo htmlspecialchars($data['pet_photo']); ?>" alt="Pet Photo" width="100"></li>
            <?php endif; ?>
        </ul>
        <form method="post">
            <button type="submit" name="prev" class="btn btn-secondary">Previous</button>
            <button type="submit" name="save" class="btn btn-primary">Save and Continue</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
