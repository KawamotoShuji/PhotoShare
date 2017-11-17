<?php

function view($val) {
    return htmlspecialchars($val, ENT_QUOTES, 'UtF-8');
}

$pdo = new PDO('mysql:dbname=photoshare_map;host=localhost', 'root', '');
$stmt = $pdo->query('SET NAMES utf8');
$stmt = $pdo->prepare("SELECT * FROM ps_info");
$flag = $stmt->execute();
$view="";
$i=0;
if($flag==false){
    $view = "SQLエラー";
}else{
    while( $res = $stmt->fetch(PDO::FETCH_ASSOC)){
        if($i==0){
            $view .= '"'.$res['img'].','.$res['lat'].','.$res['lon'].','.$res['input_date'].'"';
        }else{
          $view .=',"'.$res['img'].','.$res['lat'].','.$res['lon'].','.$res['input_date'].'"';
        }
        $i++;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "utf-8">
        <title>
            自作写真共有アプリ
        </title>
        <style>#map_area{position: relative;height:500px;padding:20px;}#myMap{width:95%;}#myMapimg{width:100%}</style>
        <script src="js/jquery-2.1.3.min.js"></script>
        <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol' async defer></script>
    </head>
    <body>
        <header>
            <a href="photoshare.php">カメラ／写真選択</a></li>
            <a href="photo_list.php">画像一覧</a>
        </header>
        <div id="map_area">
            <div id="myMap"></div>
        </div>
        <div>
            <input id="img_width_range" type="range" step="10" max="400" min="50" value="200">
        </div>

        <script>
            var G = {
                point: new Array(<?=$view?>),
                map: null,
                zoom: 14,
                latitude: "35.660056",
                longitude: "139.714546"
            };

            console.log(G);

            function LoadMap() {
                G.map = new Microsoft.Maps.Map($('#myMap'), {
                    credentials: "bingmapskey",
                    mapTypeId: Microsoft.Maps.MapTypeId.road, //.aerial, .birdseye[英語表記になる]
                    zoom: G.zoom,
                    center: new Microsoft.Maps.Location(G.latitude, G.longitude)
                });
            }
        LoadMap();
        </script>
    </body>
</html>
