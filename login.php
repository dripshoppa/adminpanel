<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>DRIP - Admin panel</title>
    <link rel="stylesheet" href="admin/admin.css">
    <?php
        $servername = "localhost";
        $username = "21Drip";
        $password = "Hanglamp";
        $dbname = "21drip";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        function console_log($output, $with_script_tags = true) {
            $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
        ');';
            if ($with_script_tags) {
                $js_code = '<script>' . $js_code . '</script>';
            }
            echo $js_code;
        }

        if(isset($_POST["uname"])){
            $sql = 'SELECT password FROM users WHERE email = "'. $_POST["uname"].'"';
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $passhash = hash("ripemd160", "Beef". $_POST["pass"]);
                    if ($row["password"] == $passhash){
                        $hash = hash("tiger192,3", "Banaan". $_POST["pass"]. date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000)));
                        setcookie("login_key", $hash, time() + 600, "/");
                        $sql = "UPDATE users SET remember_token='". $hash. "' WHERE password ='". hash('ripemd160', 'Beef'. $_POST['pass']). "'";
                        if ($conn->query($sql) === TRUE) {
                            echo "Success";
                          } else {
                            echo "Probleem met inloggen: " . $conn->error;
                          }
                        header("Location: admin/admin.php");
                    }
                }
            }
        }

        if(isset($_COOKIE['login_key'])) {
            $hashed = $_COOKIE['login_key'];
            $sql = 'SELECT remember_token FROM users WHERE role = "admin"';
            $result = $conn->query($sql); 
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    if ($row["remember_token"] == $hashed){
                        header("Location: admin/admin.php");
                    }
                }
            }
        }
    ?>
</head>

<body style="display: flex;">
    <div class="loginmain">
        <div class="logo">
            <a href="">DRIP</a>
        </div>
        <div class="loginform">
            <form action="login.php" method="post">
                <label for="uname">Login</label><br>
                <input required type="text" id="uname" name="uname" placeholder="Login"><br>
                <label for="pass">Wachtwoord</label><br>
                <input required type="password" id="pass" name="pass" placeholder="Wachtwoord"><br><br>
                <input type="submit" value="Submit">
              </form> 
        </div>
    </div>
</body>
</html>