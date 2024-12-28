<!DOCTYPE html>
<html lang="en">

<?php
if (!isset($_COOKIE['taikhoan'])) {
    header("Location: index.php");
}
?>

<head>
    <title>Trang chủ của Lam</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chèn link leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="L.Control.Locate.min.css" />
    <script src="L.Control.Locate.min.js"></script>

    <script src="L.Control.Opacity.js"></script>
    <link href="L.Control.Opacity.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <!-- thêm link liên kết file của php -->
    <?php require_once 'NguyenDucAnh_WebGIS.php' ?>


    <style>
        #alo {
            background-color: aquamarine;
        }

        .col-md-7 {
            text-align: justify;
        }

        label {
            font-weight: bold;
            color: red;
        }

        #map {
            height: 890px;
            position: relative;
            z-index: 900;
        }

        #bar {
            position: absolute;
            z-index: 1000;
            top: 130px;
            border: 1px solid gray;
        }

        #demo {
            width: 560px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">

        <div class="container-fluid">
            <a class="navbar-brand" href="javascript:void(0)">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">Map</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">Quy hoạch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">Thống kê</a>
                    </li>
                </ul>
                


                <span class="text-white">
                    <?php 
                    echo $_COOKIE['taikhoan'];
                    ?>
                </span>
                <a class="text-white ms-5" href = 'http://localhost/22_12_2024/logout.php'>Đăng xuất</a>
            </div>



        </div>
    </nav>


    <!-- offcanvas -->

    <div class="offcanvas offcanvas-start" id="demo">
        <div class="offcanvas-header bg-info ">
            <h3 class="offcanvas-title text-white">TRA CỨU THÔNG TIN</h3>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">

                <div class="col-md-6 mt-3">
                    <label for="">SỐ TỜ</label>
                    <input class="form-control mt-2 " placeholder="Nhập tờ bản đồ số ..." id="soto">
                </div>
                <div class="col-md-6 mt-3">
                    <label for="">SỐ THỬA</label>
                    <input class="form-control mt-2" placeholder="Nhập thửa đất số ..." id="sothua">
                </div>

                <div class="col-md-12 mt-3">
                    <label for="">LOẠI ĐẤT</label>
                    <input class="form-control mt-2" placeholder="Nhập loại đất" id="loaidat">
                </div>
                <div class="btn-group mt-5">
                    <button type="submit" class="btn btn-info" id="tracuu">Tra cứu</button>
                    <button type="reset" class="btn btn-secondary">Đặt lại</button>
                    <button type="button" class="btn" id="alo">Xuất Excel</button>
                </div>

                <div class="btn-group mt-2">
                    <button type="button" class="btn btn-secondary" id="HN">Hà Nội</button>
                    <button type="button" class="btn btn-primary" id="HCM">TP HCM</button>
                    <button type="button" class="btn btn-success" onclick="moveBD()">Bình Dương</button>
                </div>

                <div class="col-md-12" id="ketqua">
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12" id="map">
                <button class="btn btn-sm btn-light" id="bar" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</body>

<script>
    document.getElementById('tracuu').addEventListener('click', function() {
        var soto = document.getElementById('soto').value;
        var sothua = document.getElementById('sothua').value;
        var loaidat = document.getElementById('loaidat').value;
        if (soto == "" && sothua == "" && loaidat == "") {
            alert('Bạn cần nhập ít nhất một thông tin')
        } else {
            $.ajax({
                type: 'POST',
                url: 'xulytracuu.php',
                data: {
                    soto: soto,
                    sothua: sothua,
                    loaidat: loaidat,
                },
                success: function(data) {
                    // alert(data);
                    document.getElementById("ketqua").innerHTML = data;
                }
            })
        }

    })

    var map = L.map('map').setView([10.864797259038124, 106.62203007511172], 15);
    // L là viết tắt leaflet, ('map') là id tham chiếu cho bản đồ L.map
    // setview để mở vào sẽ để đặt tại đó, 16 là độ phóng to thu nhỏ: 13 có thể xem cả tp
    var googlemap = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}').addTo(map);
    var vetinh = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}').addTo(map);
    var tan_chanh_hiep = L.tileLayer.betterWms('http://localhost:8080/geoserver/WEBGIS_LAM/wms?', {
        // wms là viết tắt geoserver lấy đường dẫn
        layers: 'WEBGIS_LAM:tanchanhhiep', // layers là lớp dữ liệu cần lấy, bao gồm workspace(WEBGIS_LAM) và tên lớp dữ liệu (tanchanhhiep)
        format: 'image/png', //định dạng dữ liệu
        transparent: true, // điều chỉnh độ trong suốt
        zIndex: 100,
        maxZoom: 30,
    }).addTo(map);
    var giaothong = L.tileLayer.wms('http://localhost:8080/geoserver/WEBGIS_LAM/wms?', {
        layers: 'WEBGIS_LAM:giaothong',
        format: 'image/png',
        transparent: true,
        zIndex: 100,
        maxZoom: 30,
    }).addTo(map);



    var ban_do_nen = {
        "vệ tinh": vetinh,
        "Google map": googlemap,

    };

    var ban_do_chuyen_de = {
        "thửa đất": tan_chanh_hiep
    };




    var dieu_khien_layer = L.control.layers(ban_do_nen, ban_do_chuyen_de).addTo(map);

    var dinh_vi = L.control.locate({
        // strings: {title: "Bạn đang ở đây"}
    }).addTo(map);
    var opacity = L.control.opacity(ban_do_chuyen_de, {}).addTo(map);
    var opacity = L.control.opacity(ban_do_nen, {}).addTo(map);
    // map.on('click', function(e) {
    //     // console.log(e.latlng) => hiện trên trang nguồn console của trang web
    //     alert("Kinh độ: " + e.latlng.lng + "Vĩ độ: " + e.latlng.lat)
    // })

    var stt = 0;
    map.on('click', function(e) {
        stt++;
        var marker = L.marker([e.latlng.lat, e.latlng.lng], {
            draggable: true
        }).addTo(map);
        marker.bindPopup("Marker số " + stt).openPopup()
    })

    // dịch chuyển đến Hà Nội
    document.getElementById('HN').addEventListener('click', function() {
        map.flyTo([21.037133650611672, 105.8345775261539], 16);
    });
    // di chuyển đến TP.HCM
    document.getElementById('HCM').addEventListener('click', function() {
        map.setView([10.823113822852633, 106.62950054597036], 16);
    });
    // di chuyển đến Bình Dương
    function moveBD() {
        map.setView([10.993132, 106.655933], 16);
    }
    // dịch chuyển đến vị trí cần tìm bất kỳ


    function vitrithuadat(gid) {
        $.ajax({
            type: 'POST',
            url: 'vitrithuadat.php',
            data: {
                gid: gid
            },
            success: function(data) {
                var mangjson = JSON.parse(data)
                // console.log(mangjson);
                var data = JSON.parse(data);
                var lat = data[0].tam_y;
                var lng = data[0].tam_x;
                var to_so = data[0].to_so;
                var thua_so = data[0].thua_so;
                var loai_dat = data[0].loai_dat;
                var dien_tich = data[0].dien_tich;
                // Có 2 cách để coi data trả về
                // Cách 1; Là alert(data) --> Chỉ áp dụng khi trả về kiểu chữ (echo "Duc Anh");
                // Cách 2: Là console.log(data) -- > Chỉ áp dụng khi trả về dạng mảng hoặc json
                //Cần phân tích data ta. Nếu nằm trong dấu ngoặc vuông là mảng; nằm trong {} là json
                //Để truy cập mảng thì dùng [] ví dụ data là [0]
                //Để truy cập json thì dùng . ví dụ [0].tam_x   


                // đến thửa đất đó
                map.flyTo([lat, lng], 20);
                var marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("Tờ số " + to_so + ", Thửa số " + thua_so + ", Loại đất " + loai_dat + ", Diện tích " + dien_tich + " m<sup>2</sup>").openPopup()
            }
        })
    }

    document.getElementById('bar').addEventListener('click', function(e) {
        e.stopPropagation();
    })

    // nút đăng xuất
</script>

</html>