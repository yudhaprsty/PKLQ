<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico">
    <title>Sistem Monitoring Data Antariksa</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href= "{{ asset('assets/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Styles -->
    <style>
      html, body {
          background-color: #fff;
          color: #636b6f;
          font-family: 'Raleway', sans-serif;
          font-weight: 100;
          height: 100vh;
          margin: 0;
      }
      .full-height {
          height: 100vh;
      }
      .flex-center {
          align-items: center;
          display: flex;
          justify-content: center;
      }
      .position-ref {
          position: relative;
      }
      .top-right {
          position: absolute;
          right: 10px;
          top: 18px;
      }
      .content {
          text-align: center;
      }
      .title {
          font-size: 84px;
      }
      .links > a {
          color: #636b6f;
          padding: 0 25px;
          font-size: 12px;
          font-weight: 600;
          letter-spacing: .1rem;
          text-decoration: none;
          text-transform: uppercase;
      }
      .m-b-md {
          margin-bottom: 30px;
      }
    </style>

    <?php
      // $admins = App\User::find($id);
      $IP     = App\Cabang::orderBy('id_cabang')->get();
      $i = 0;
      $cabang = array();
      $output = array();
      $longlat = array();

      foreach ($IP as $IPs) {
        $id = $IPs->getAttribute('id_cabang');
        $ping = $IPs->getAttribute('ip_server');
        $name = $IPs->getAttribute('nama_cabang');
        $longitude = $IPs->getAttribute('longitude');
        $latitude = $IPs->getAttribute('latitude');

        $cabang[$i] = shell_exec('ping -n 1 '. $ping);  //kalo ga sekali compiling time nya besar
        $output[$i] = strpos($cabang[$i], 'Reply');

        $longlat[$i] = [$name, (float)$latitude, (float)$longitude, (int)$id];
        $i++;
      }
     ?>

    <style>
      #map-canvas {
        width: 1000px;
        height: 500px;
      }
    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbJw9v5SiIYxzP5Z3gHHpVwlahVkWapk0&callback=initialize"></script>
    <script type="text/javascript">
      var markers = <?php echo json_encode($longlat); ?>;
        function initialize() {
          var mapCanvas = document.getElementById('map-canvas');
          var mapOptions = {
            scrollwheel: false,
            navigationControl: false,
            mapTypeControl: false,
            scaleControl: false,
            draggable: true,
            minZoom: 5,
            maxZoom: 7,
            mapTypeId: google.maps.MapTypeId.ROADMAP
          }
          var map = new google.maps.Map(mapCanvas, mapOptions)

          var infowindow = new google.maps.InfoWindow(), marker, i;
          var bounds = new google.maps.LatLngBounds(); // diluar looping
          var daerah = <?php echo json_encode($output);?>;

          for (i = 0; i < markers.length; i++) {
            pos = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(pos); // di dalam looping
            if(daerah[i] > 0){
              marker = new google.maps.Marker({
                  position: pos,
                  map: map,
                  animation:google.maps.Animation.BOUNCE,
                  icon:'biru2.png',
                });
              }
              else {
                marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon:'merah2.png',
                });
              }

              google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
              return function() {
                  infowindow.setContent(markers[i][0]);
                  infowindow.open(map, marker);
              }
              })
              (marker, i));

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(markers[i][0]);
                infowindow.open(map, marker);
                // window.location.href = `admin/lihatCabang/${markers[i][3]}`;
            }
            })
            (marker, i));
            map.fitBounds(bounds); // setelah looping
          }
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>

  <body>
    <div class="container-login100" style="background-image: url('page/login/images/welcome1.jpg');">
    <!-- <link rel="shortcut icon" href="favicon.ico" type="image/ico"> -->
    <div class="flex-center position-ref full-height">
      @if (Route::has('login'))
        <div class="top-right links">
          @auth
            <a href="{{ url('/home') }}">Home</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
            <!-- <a href="{{ route('register') }}">Register</a> -->
          @endauth
        </div>
      @endif
      <div class="content">
        <img src="{{ asset('assets/img/simoda.png') }}" alt="..." style="width:800px; height:150px;">
        <br><br>
        <div id="map-canvas"></div>
        <div class="cleafix"></div>
      </div>
    </div>
  </body>
</html>
