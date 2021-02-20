<html>
<head>
    <?php
        setcookie("login_key", "removed", time() - 3600, "/");
        header("Location: ../login.php");
    ?>
</head>
<body>
    <h1>Aan het uitloggen!</h1>
</body>
</html>