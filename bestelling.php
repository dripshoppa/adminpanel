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
        if (isset($_GET["status"]) or isset($_GET["track"])){
            $sql = "SELECT status, track FROM orders WHERE id='". $_GET["a"]. "'";
            console_log($sql);
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    if ($row["status"] != $_GET["status"]){
                        $sql = "UPDATE orders SET status='". $_GET["status"]. "' WHERE id ='". $_GET["a"]. "'";
                        if ($conn->query($sql) === TRUE) {
                            done;
                          } else {
                            echo "Probleem met updaten: " . $conn->error;
                          }
                    }
                    if ($row["track"] != $_GET["track"]){
                        $sql = "UPDATE orders SET track='". $_GET["track"]. "' WHERE id ='". $_GET["a"]. "'";
                        if ($conn->query($sql) === TRUE) {
                            done;
                          } else {
                            echo "Probleem met updaten: " . $conn->error;
                          }
                    }
                }
            }
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
        <div class="mainbar">
            <div class="colummain">
                <h2>Informatie</h2>
                <div class="rowmain">
                    <div class="table fill">
                        <div class="row">
                            <div class="cell odd">
                                Besteld op:
                            </div>
                            <div class="cell even">
                                Waarde:
                            </div>                            
                            <div class="cell odd">
                                Status:
                            </div>
                            <div class="cell even">
                                Tracking ID:
                            </div>
                        </div>
                        <?php
                            $sql = "SELECT time_of_order, status, track FROM orders WHERE id='". $_GET["a"]. "'";
                            $sql2 = "SELECT SUM(price) FROM ordered_items, products WHERE ordered_items.product_id = products.id and ordered_items.order_id ='". $_GET["a"]. "'";

                            console_log($sql);
                            $result = $conn->query($sql);
                            $result2 = $conn->query($sql2);
                            if ($result2->num_rows > 0) {
                                // output data of each row
                                while($row2 = $result2->fetch_assoc()) {
                                    $worth = $row2["SUM(price)"];
                                }
                            }
                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    switch ($row["status"]){
                                        case 1:
                                            $b = '<option value="1" selected="selected">Nieuw</option>
                                            <option value="2">In verwerking</option>
                                            <option value="3">Verzonden</option>
                                            <option value="4">Gereed</option>';
                                            break;
                                        case 2:
                                            $b = '<option value="1">Nieuw</option>
                                            <option value="2" selected="selected">In verwerking</option>
                                            <option value="3">Verzonden</option>
                                            <option value="4">Gereed</option>';
                                            break;
                                        case 3:
                                            $b = '<option value="1">Nieuw</option>
                                            <option value="2">In verwerking</option>
                                            <option value="3" selected="selected">Verzonden</option>
                                            <option value="4">Gereed</option>';
                                            break;
                                        case 4:
                                            $b = '<option value="1">Nieuw</option>
                                            <option value="2">In verwerking</option>
                                            <option value="3">Verzonden</option>
                                            <option value="4" selected="selected">Gereed</option>';
                                            break;
                                        
                                    }


                                    echo '<div class="row">
                                    <div class="cell odd click">'. 
                                    $row["time_of_order"].
                                    '</div>
                                    <div class="cell even click">'.
                                    $worth.
                                    '</div>
                                    <div class="cell odd ">
                                    <form action = "bestelling.php? method="get">
                                    <input type="hidden" name="a" value="'. $_GET["a"].'">
                                    <select name="status" id="status">'.
                                        $b.
                                    '</select>
                                    </div>
                                    <div class="cell even">
                                    <input type="text" value="'.$row["track"].'" name="track">
                                    </div>
                                    <div class="cell bewaar">
                                    <input type="submit" value="Bewaar">
                                    </div>
                                    </form>
                                </div>';
                                }
                            } else {
                                echo "0 resultaten";
                            }
                            ?>
                        <div class="row">
                            <div class="cell odd">
                                Naam:
                            </div>
                            <div class="cell even">
                                Adres:
                            </div>
                            <div class="cell odd">
                                Postcode:
                            </div>                            
                            <div class="cell even">
                                Stad:
                            </div>
                        </div>
                        <?php
                            $sql = "SELECT name, address, zip_code, city FROM users WHERE id=(SELECT user_id FROM orders WHERE id='". $_GET["a"]. "')";
                            console_log($sql);
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo '<div class="row">
                                    <div class="cell odd click">'. 
                                    $row["name"].
                                    '</div>
                                    <div class="cell even click">'. 
                                    $row["address"].
                                    '</div>
                                    <div class="cell odd click">'.
                                    $row["zip_code"].
                                    '</div>
                                    <div class="cell even click">'.
                                    $row["city"].
                                    '</div>
                                </div>';
                                }
                            } else {
                                echo "0 resultaten";
                            }
                        
                            ?>
                    </div>
                    
                    </div>
                </div>
                <h2>Producten</h2>
                <div class="table fill">
                    <div class="row">
                        <div class="cell odd">
                            Prod nr.
                        </div>
                        <div class="cell even">
                            Naam
                        </div>
                        <div class="cell odd">
                            Categorie
                        </div>
                        <div class="cell even">
                            Besteld
                        </div>
                        <div class="cell odd">
                            Voorraad
                        </div>
                    </div>
                    <?php
                        #$sql = "SELECT id, title, price, category_id, availability, slug FROM products WHERE id in (SELECT product_id FROM ordered_items WHERE order_id ='". $_GET["a"]. "')";
                        $sql = "SELECT * FROM ordered_items, products WHERE ordered_items.product_id = products.id and ordered_items.order_id ='". $_GET["a"]. "'";
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
                                $row["category_id"].
                                '</div>
                                <div class="cell odd click">'.
                                $row["amount"].
                                '</div>
                                <div class="cell even click">'.
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
        </div>
    </div>
</body>
</html>