<?php
session_start();
include 'header.php';

if (!isset($_SESSION['onboarding_step']) || $_SESSION['onboarding_step'] < 3) {
    header('Location: step2.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        $_SESSION['onboarding_step'] = 2;
        header('Location: step2.php');
        exit();
    }

    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_photo']['name']);

        if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadFile)) {
            $_SESSION['onboarding_data']['profile_photo'] = $uploadFile;
            $_SESSION['onboarding_step'] = 4;
            header('Location: step4.php');
            exit();
        } else {
            $error = 'Failed to upload profile photo.';
        }
    } else {
        $error = 'Please select a profile photo to upload.';
    }
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Step 3: Profile Photo</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_photo">Upload Profile Photo</label>
                <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" required>
            </div>
            <button type="submit" name="prev" class="btn btn-secondary">Previous</button>
            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
