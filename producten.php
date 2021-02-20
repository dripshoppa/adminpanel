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
                    Prod nr.
                </div>
                <div class="cell even">
                    Naam
                </div>
                <div class="cell odd">
                    Prijs
                </div>
                <div class="cell even">
                    Categorie
                </div>
                <div class="cell odd">
                    Aantal
                </div>
            </div>
            <?php
            $sql = "SELECT id, title, price, category_id, availability, slug FROM products";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<a class="row" href="/informatica/leerlingenwebsites/IN2021/Projectwebsites/Drip/product/'.$row["slug"].'">
                    <div class="cell odd click">'. 
                    $row["id"].
                    '</div>
                    <div class="cell even click">'.
                    $row["title"].
                    '</div>
                    <div class="cell odd click">'.
                    $row["price"].
                    '</div>
                    <div class="cell even click">'.
                    $row["category_id"].
                    '</div>
                    <div class="cell odd click">'.
                    $row["availability"].
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