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

        $sql = "SELECT title, brand, price, category_id, description, slug, id FROM products WHERE id = '". $_GET["a"]. "'";
        console_log($sql);
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                console_log($row);
                $data = array(
                    array("Titel", $row["title"], "title"),
                    array("Merk:", $row["brand"], "brand"),
                    array("Prijs:", $row["price"], "price"),
                    array("Categorie:", $row["category_id"], "category_id"),
                    array("Beschrijving:", $row["description"], "description"),
                    array("Slug:",$row["slug"], "slug")
                );
            }
        }
        
        for ($x = 0; $x <= 5; $x++) {
            console_log($x);
            if (isset($_GET[$data[$x][2]])){
                if ($_GET[$data[$x][2]] != ""){
                    if ($_GET[$data[$x][2]] != $data[$x][1]){
                        $sql = 'UPDATE products SET ' .$data[$x][2]. '="' .$_GET[$data[$x][2]]. '" WHERE id ="'. $_GET["a"]. '"';
                        console_log($sql);
                        if ($conn->query($sql) === TRUE) {
                            done;
                        } 
                        else {
                            echo "Probleem met updaten: " . $conn->error;
                        }
                    }
                }
            }
        }
        for ($g = 0; $g <= 5; $g++) {
            if ($_GET[$data[$g][2]] != ""){
                if ($_GET[$data[$g][2]] != $data[$g][1]){
                    console_log($_GET[$data[$g][2]] .' - '. $data[$g][1]);
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            }
        }

        $sql = 'SELECT id, image_url FROM images WHERE product_id='.$_GET["a"].'';
        $images = array();
        $result = $conn->query($sql);
        console_log($result);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                console_log($row);
                array_push($images, array($row["id"], $row["image_url"]));
            }
        }
        console_log($images);

        $sql = 'SELECT id, display_name FROM categories';
        $categories = array();
        $result = $conn->query($sql);
        console_log($result);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                console_log($row);
                array_push($categories, array($row["id"], $row["display_name"]));
            }
        }
        console_log($categories);

        $sql = 'SELECT '

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
                        <form style="display: contents;" method="get" action="product.php">
                        <?php
                        echo '<input type="hidden" name="a" value="'. $_GET["a"].'">';
                        for ($x = 0; $x <= 5; $x++) {
                            if ($data[$x][2] != "category_id"){
                                echo '<div class="row">
                                <div class="cell odd">
                                    '.$data[$x][0].'
                                </div>
                                <div class="cell even">
                                <input type="text" value="'.htmlentities($data[$x][1], ENT_QUOTES).'" name="'.$data[$x][2].'">
                                </div>
                                </div>';
                            }
                            else {
                                console_log("TEST");
                                $sql = 'SELECT display_name FROM categories WHERE id="'.$data[$x][2].'"';
                                $opties = "";
                                for ($w = 0; $w < count($categories); $w++){
                                    console_log($w .'- test');
                                    if ($categories[$w][0] == $data[$x][1]){
                                        $opties = $opties.'<option value="'.$categories[$w][0].'" selected="selected">'.$categories[$w][1].'</option>';
                                    }
                                    else{
                                        $opties = $opties.'<option value="'.$categories[$w][0].'">'.$categories[$w][1].'</option>';
                                    }
                                }
                                console_log($opties);
                                echo '<div class="row">
                                <div class="cell odd">
                                    '.$data[$x][0].'
                                </div>
                                <div class="cell even">
                                <select type="select" name="'.$data[$x][2].'">'.
                                    $opties.'
                                </select>
                                </div>
                                </div>';
                            }
                        }
                        ?>
                        <div class="cell bewaar">
                            <input type="submit" value="Bewaar">
                        </div>
                        </form>
                    </div>  
                        <?php
                        $conn->close();
                        ?>
                </div>

                <h2>Foto's</h2>
                    <div class="table fill">
                        <form style="display: contents;" method="get" action="product.php">
                        <div class="row">
                        <div class="cell odd">
                            Preview
                        </div>
                        <div class="cell even">
                            ID
                        </div>
                        <div class="cell odd">
                            URL
                        </div>
                        <div class="cell even">
                            Verwijder
                        </div>
                    </div>
                    <?php
                        #$sql = "SELECT id, title, price, category_id, availability, slug FROM products WHERE id in (SELECT product_id FROM ordered_items WHERE order_id ='". $_GET["a"]. "')";
                        for ($k = 0; $k < count($images); $k++) {
                            echo '<div class="row">
                            <div class="cell odd click">
                            <div style="text-align-last: center;">
                            <img src="'.$images[$k][1].'" style="height: 2rem;">
                            </div>
                            </div>
                            <div class="cell even click">'.
                            $images[$k][0].
                            '</div>
                            <div class="cell odd click">
                            <input name="url-'.$images[$k][0].'" type="text" value="'.
                            $images[$k][1].
                            '"></div>
                            <div class="cell even click">
                            <input type="checkbox" name="delete-'.$images[$k][0].'">
                            </div>
                            </div>';
                            }
                    
                        $conn->close();
                        ?>
                        <div class="cell bewaar">
                            <input type="submit" value="Bewaar">
                        </div>
                        </form>
                    </div>  
                        <?php
                        $conn->close();
                        ?>
                </div>
        </div>
    </div>
</body>
</html>