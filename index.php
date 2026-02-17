<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: profile.php');
    exit();
}

if (!isset($_SESSION['onboarding_step'])) {
    $_SESSION['onboarding_step'] = 1;
}

$step = $_SESSION['onboarding_step'];

switch ($step) {
    case 1:
        header('Location: step1.php');
        exit();
    case 2:
        header('Location: step2.php');
        exit();
    case 3:
        header('Location: step3.php');
        exit();
    case 4:
        header('Location: step4.php');
        exit();
    case 5:
        header('Location: step5.php');
        exit();
    default:
        // If the step is not valid, reset to step 1
        $_SESSION['onboarding_step'] = 1;
        header('Location: step1.php');
        exit();
}
?>
