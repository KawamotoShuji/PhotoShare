<?php

session_start();

$img="";

// 改竄されたフォームからの複数ファイル配列送信対策
if (!isset($_FILES['upfile']['error']) || !is_int($_FILES['upfile']['error'])){
}else{
    $lat = $_POST["lat"];
    $lon = $_POST["lon"];
    $file_name = $_FILES["upfile"]["name"];
    $tmp_path  = $_FILES["upfile"]["tmp_name"];
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_dir_path = "images/";
    $uniq_name = date("YmdHis") . session_id() . "." . $extension;

    if ( is_uploaded_file( $tmp_path ) ) {
        if ( move_uploaded_file( $tmp_path, $file_dir_path . $uniq_name ) ) {
            chmod( $file_dir_path . $uniq_name, 0644 );
            //echo $uniq_name . "をアップロードしました。";
            $img = '<img src =' . "$file_dir_path" . "$uniq_name" . '>';
            //最終的にgit.ignoreに入れましょう
            $pdo = new PDO('mysql:dbname=photoshare_map;host=localhost', 'root', '');
            $stmt = $pdo->query('SET NAMES utf8');
            //INSET分のSQLインジェクション対策？
            $stmt = $pdo->prepare("INSERT INTO ps_info (id, lat, lon, img, input_date)VALUES(NULL, :lat, :lon, :img, sysdate())");
            $stmt->bindValue(':lat', $lat);
            $stmt->bindValue(':lon', $lon);
            $stmt->bindValue(':img', "images/" . $uniq_name);
            echo 'hoge';
            $status = $stmt->execute();

            if($status == false){
                echo "SQLERROR!";
                exit;
            }else{
                echo "登録完了！";
            }
        } else {
            echo "Error:アップロードできませんでした。";
        }
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
    </head>
    <body>
    <header>
        <a href="data_input.php">カメラ／写真選択</a></li>
        <a href="photo_list.php">画像一覧</a>
    </header>
        <div>
          <form method="post" action="photoshare.php" enctype="multipart/form-data">
            <p>
                <input type="file" accept = "image/#" capture = "camera" value = "" id="image_file" name="upfile">
            </p>
            <p>
                <input type="submit" class="btn btn-success btn-lg" value="Fileアップロード">
            </p>
            <input type="hidden" id="lat" name="lat">
            <input type="hidden" id="lon" name="lon">
          </form>
        </div>
        <div>
            <div id="photarea"><?=$img?></div>
        </div>
        <script src="js/jquery-2.1.3.min.js"></script>
        <script>
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    try {
                        var lat = position.coords.latitude;
                        var lon = position.coords.longitude;
                        $("#lat").val(lat);
                        $("#lon").val(lon);
                        console.log(lat);
                        console.log(lon);
                    } catch(error) {
                        console.log("getGeolocation: " + error);
                    }
                },

                function(error) {
                    var e =  "";
                    if(error.code = 1) {
                        e = "位置情報が許可されていません";
                    }
                    if(error.code = 2) {
                        e = "位置情報を特定できません";
                    }
                    if(error.code = 3) {
                        e = "位置情報を取得する前にタイムアウトになりました"
                    }
                    console.log("エラー: " + e);
                },

                {
                    enableHighAccuracy: true,  //より高精度な位置を求める
                    maximumAge: 20000,         //最後の現在地情報取得が20秒以内であればその情報を再利用する設定
                    timeout: 10000
                }
            );
        </script>
    </body>
</html>
