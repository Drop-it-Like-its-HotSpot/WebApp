<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <link href="css/styles.css" rel="stylesheet">
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDf-eVvtFwdenWfpUTHJQdUyk6Z_9vURRA">
    </script>
    <script type="text/javascript">
        function success(position) {

          var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
          var myOptions = {
            zoom: 15,
            center: latlng,
          };
          var map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
          
          var marker = new google.maps.Marker({
              position: latlng, 
              map: map, 
              title:"You are here! (at least within a "+position.coords.accuracy+" meter radius)"
          });
        }
        google.maps.event.addDomListener(window, 'load', navigator.geolocation.getCurrentPosition(success));

    </script> 
  </head>
  <body>
    
    <div class="parent">
        <div id="list-view">
            <?php 
            
            ?>
 

            LOGIN INFO
            <br />CHAT LIST
            <br />DETAILS
            
            
            
            
            
            
            
        </div>
        
        <div id="map-canvas"></div>
      </div>
        
        
    </div>
  </body>
</html>