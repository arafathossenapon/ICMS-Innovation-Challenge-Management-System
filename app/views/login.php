<?php
// $error is passed in from LoginController.php
if (!isset($error)) {
    $error = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ICMS</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>

<body>

    <div class="login-wrap">

        <div class="login-box">

            <h1>ICMS</h1>
            <p class="subtitle">Innovation Challenge Management System</p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="LoginController.php">

                <div class="form-group">
                    <label>Username</label>
                    <input
                        type="text"
                        name="username"
                        required
                        autofocus
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="btn-primary">Login</button>

            </form>

        </div>

    </div>

</body>

</html>
