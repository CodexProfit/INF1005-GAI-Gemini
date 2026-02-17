<?php
session_start();
include 'header.php';

if (!isset($_SESSION['onboarding_step']) || $_SESSION['onboarding_step'] < 2) {
    header('Location: step1.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['prev'])) {
        $_SESSION['onboarding_step'] = 1;
        header('Location: step1.php');
        exit();
    }

    $name = $_POST['name'];
    $contact = $_POST['contact'];

    if (empty($name) || empty($contact)) {
        $error = 'Name and contact information are required.';
    } else {
        $_SESSION['onboarding_data']['name'] = $name;
        $_SESSION['onboarding_data']['contact'] = $contact;
        $_SESSION['onboarding_step'] = 3;
        header('Location: step3.php');
        exit();
    }
}
?>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">Step 2: Personal Info</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_SESSION['onboarding_data']['name']) ? htmlspecialchars($_SESSION['onboarding_data']['name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Information</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($_SESSION['onboarding_data']['contact']) ? htmlspecialchars($_SESSION['onboarding_data']['contact']) : ''; ?>" required>
            </div>
            <button type="submit" name="prev" class="btn btn-secondary">Previous</button>
            <button type="submit" class="btn btn-primary">Next</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
