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
        <style>#myMap {width: 600px ; height: 600px ;}</style>
        <script src="js/jquery-2.1.3.min.js"></script>
        <script src="//maps.googleapis.com/maps/api/js?key=''"></script>
    </head>
    <body>
        <header>
            <a href="photoshare.php">カメラ／写真選択</a></li>
            <a href="photo_list.php">画像一覧</a>
        </header>

            <div id="myMap"></div>

        <script>
            var mapPos = document.getElementById( "myMap" );
            var G = {
                point: new Array(<?=$view?>),
                map: null,
                zoom: 14,
                latitude: "35.654660",
                longitude: "139.778634"
            };

            function LoadMap() {
                G.map = new google.maps.Map(mapPos, {
                  center: new google.maps.LatLng( G.latitude, G.longitude ) ,
                  zoom: G.zoom ,
                });

                var marker_count = G.point.length;
                for (var i=0; i<marker_count; i++) {
                    var locations = G.point[i];
                    var gpoint = locations.split(",");

                    console.log(gpoint);

                    var marker = new google.maps.Marker( {
  	                    map: G.map ,
  	                    position: new google.maps.LatLng( gpoint[1], gpoint[2]) ,
                        icon: {
  		                      url: gpoint[0] ,
  		                      scaledSize: new google.maps.Size( 70, 70 ) ,
  	                    } ,
                        label: gpoint[3],
                    });
                }
            }
        LoadMap();
        </script>
    </body>
</html>
