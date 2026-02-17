<?php
session_start();
include 'header.php';

if (!isset($_SESSION['onboarding_step']) || $_SESSION['onboarding_step'] < 4) {
    header('Location: step3.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        $_SESSION['onboarding_step'] = 3;
        header('Location: step3.php');
        exit();
    }

    $petName = $_POST['pet_name'];
    $petBreed = $_POST['pet_breed'];
    $petAge = $_POST['pet_age'];

    if (empty($petName) || empty($petBreed) || empty($petAge)) {
        $error = 'All pet details are required.';
    } else {
        $_SESSION['onboarding_data']['pet_name'] = $petName;
        $_SESSION['onboarding_data']['pet_breed'] = $petBreed;
        $_SESSION['onboarding_data']['pet_age'] = $petAge;

        if (isset($_FILES['pet_photo']) && $_FILES['pet_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['pet_photo']['name']);

            if (move_uploaded_file($_FILES['pet_photo']['tmp_name'], $uploadFile)) {
                $_SESSION['onboarding_data']['pet_photo'] = $uploadFile;
            } else {
                $error = 'Failed to upload pet photo.';
            }
        }

        if (empty($error)) {
            $_SESSION['onboarding_step'] = 5;
            header('Location: step5.php');
            exit();
        }
    }
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Step 4: Pet Info</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pet_name">Pet Name</label>
                <input type="text" class="form-control" id="pet_name" name="pet_name" value="<?php echo isset($_SESSION['onboarding_data']['pet_name']) ? htmlspecialchars($_SESSION['onboarding_data']['pet_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_breed">Pet Breed</label>
                <input type="text" class="form-control" id="pet_breed" name="pet_breed" value="<?php echo isset($_SESSION['onboarding_data']['pet_breed']) ? htmlspecialchars($_SESSION['onboarding_data']['pet_breed']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_age">Pet Age</label>
                <input type="number" class="form-control" id="pet_age" name="pet_age" value="<?php echo isset($_SESSION['onboarding_data']['pet_age']) ? htmlspecialchars($_SESSION['onboarding_data']['pet_age']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="pet_photo">Pet Photo</label>
                <input type="file" class="form-control-file" id="pet_photo" name="pet_photo">
            </div>
            <button type="submit" name="prev" class="btn btn-secondary">Previous</button>
            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
