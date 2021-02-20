<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>DRIP - Admin panel</title>
    <link rel="stylesheet" href="admin.css">
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

        if(isset($_COOKIE['login_key'])) {
            $hashed = $_COOKIE['login_key'];
            $sql = 'SELECT remember_token FROM users WHERE role = "admin"';
            $result = $conn->query($sql); 
            $test = 0;
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    if ($row["remember_token"] == $hashed){
                        $test = 1;
                    }
                }
                if ($test == 0){
                    header("Location: ../login.php");
                }
            }
        }
        else {
            header("Location: ../login.php");
        }

        function console_log($output, $with_script_tags = true) {
            $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
        ');';
            if ($with_script_tags) {
                $js_code = '<script>' . $js_code . '</script>';
            }
            echo $js_code;
        }
    ?>
</head>

<body>
    <script src="js/scripts.js"></script>
    <div class="main-container">
        <div class="sidebar">
            <div class="logo">
                <a href="">DRIP</a>
            </div>
            <div class="filler1">
            </div>
            <a class="button" href="admin.php">
                Home
            </a>
            <a class="button" href="bestellingen.php">
                Bestellingen
            </a>
            <a class="button" href="producten.php">
                Producten
            </a>
            <div class="filler2">
            </div>
            <a class="button logout" href="logout.php">
                Log uit
            </a>
        </div>
        <div class="splitter"></div>
        <div class="mainbar table">
            <div class="row">
                <div class="cell odd">
                    Datum
                </div>
                <div class="cell even">
                    Status
                </div>
                <div class="cell odd">
                    User nr
                </div>
                <div class="cell even">
                    Bestel nr
                </div>
            </div>
            <?php
            $sql = "SELECT id, time_of_order, status, user_id FROM orders ORDER BY id DESC";
            $result = $conn->query($sql);
            console_log($result);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    console_log($row);
                    switch ($row["status"]){
                        case 1:
                            $d = "Nieuw";
                            break;
                        case 2:
                            $d = "In verwerking";
                            break;
                        case 3:
                            $d = "Verzonden";
                            break;
                        case 4:
                            $d = "Gereed";
                            break;
                        }
                    echo '<a class="row" href="bestelling.php?a='.$row["id"].'">
                    <div class="cell odd click">'. 
                    $row["time_of_order"].
                    '</div>
                    <div class="cell even click">'.
                    $d.
                    '</div>
                    <div class="cell odd click">'.
                    $row["user_id"].
                    '</div>
                    <div class="cell even click">'.
                    $row["id"].
                    '</div>
                </a>';
                }
            } else {
                echo "0 resultaten";
            }
    
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>