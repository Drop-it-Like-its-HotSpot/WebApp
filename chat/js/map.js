function initialize() {
    var myCenter = new google.maps.LatLng(latitude, longitude);
    var zoom;
    if(radius <= .5){
        zoom = 18;
    }
    else if(radius <= 1){
        zoom = 16;
    }else if(radius <= 2){
        zoom = 15;
    }else if(radius <= 4){
        zoom = 14;
    }else if(radius <= 5){
        zoom = 13;
    }else if(radius <= 8) {
        zoom = 12;
    }else{
        zoom = 10;
    }
    var mapOptions = {
        zoom: zoom,
        center: myCenter,
        scrollwheel: false,
        navigationControl: false,
        mapTypeControl: false,
        scaleControl: false,
        draggable: false,
        disableDefaultUI: true
    };

    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);


    $.ajax({
        url: 'http://54.172.35.180:8080/api/chatroom/' + session_id,
        type: "GET",
        success:function(html) {

            placeNearbyMarkers(map, html);
        }
    });

}

function loadScript() {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
    'callback=initialize';
    document.body.appendChild(script);
}

function getMap(){
    if(mapC){
        return false;
    }
    mapC = true;
    var div = document.getElementById('chatlist');
    div.innerHTML = "";
    var mapdiv = document.getElementById('map-canvas');
    mapdiv.style.visibility='visible';
    mapdiv.style.minHeight = '400px';
    loadScript();
    return
}

function placeNearbyMarkers(map, rooms){
    var i = 0;
    var marker = new Array();
    var infoBox = new Array();
    var flag = new Array();
    var icon = {
        url: "img/DROPPIN.png", // url
        scaledSize: new google.maps.Size(50, 50), // size
        origin: new google.maps.Point(0,0) // origin
        //anchor: new google.maps.Point(anchor_left, anchor_top) // anchor
    };
    var shape = {
        coords: [25.6, 48.4375, 12.304, 15.23, 15.6, 3.90, 25, .4, 34.96, 4.49, 37.89, 13.28],
        type: 'poly'
    };

    ///{"chat_id":89,"Room_Admin":148,"Longitude":-82.3439366,"Latitude":29.6479852,"Chat_title":"Marston Science Library","Chat_Dscrpn":"Test"}

    for(var instance in rooms) {
        var info = rooms[instance];

        var room = info['Chat_title'];
        var desc = info['Chat_Dscrpn'];
        var admin = info['Room_Admin'];

        var myLatLng = new google.maps.LatLng(info['Latitude'], info['Longitude']);

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: icon,
            animation: google.maps.Animation.DROP,
            shape: shape,
            title: info['Chat_title']
        });
        //map.setCenter(marker.getPosition())

        var content = '<div class="dropInfoBox" id="hook"> <h4>' + '<a href="chatroom.php?rid=' + info['chat_id'] +'">' + room + '</a></h4>' + 'Info: ' + desc + '</div>';
        //var infowindow = new google.maps.InfoWindow();

        var infowindow = new google.maps.InfoWindow({
            content: '<div id="gm_content">'+content+'</div>'
        });

        google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
            return function() {
                infowindow.setContent(content);
                $('#hook').parent().parent().parent().siblings().addClass("class_name");
                infowindow.open(map,marker);
            };
        })(marker,content,infowindow));
    }

}

