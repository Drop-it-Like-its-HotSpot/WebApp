<?php
    error_reporting(0);
    ini_set('display_errors',0); 
    // Connecting, selecting database
    $dbconn = pg_connect("host=hotspotdb.cwboxzguz674.us-east-1.rds.amazonaws.com dbname=mobile user=gators password=hotspotuf")
        or die('Could not connect: ' . pg_last_error());
    
    // CREATING SQL TABLE
    //$query = 'DROP TABLE subscriptions';
    //$query = 'CREATE TABLE subscriptions (email varchar(50) primary key)';
    $query = 'INSERT INTO subscriptions (email) VALUES (\''.strtolower($_POST["email"]).'\')';
    $result = pg_query($query) or die('You are already subscribed!');
    if(!$result){
        echo('You\'ve already subscribed!');
        return false;
    }else{
        $to = $_POST["email"];
        $subject = 'Thanks for subscribing!';
        
        $headers = "From: " . strip_tags("TEAM HOTSPOT") . "\r\n";
        $headers .= "Reply-To: ". strip_tags("TEAM HOTSPOT") . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message .= "<center><img src='http://54.172.35.180/img/logo.png'><br />";
        $message ="<h1 style=\"text-align: left;\"><strong>WELCOME TO HOTSPOT!</strong></h1><p style=\"text-align: left;\">We appreciate your desire to follow our development progress and have added you to our mailing list. We will send out periodic emails regarding any updates or news relating to our application.&nbsp;</p><p style=\"text-align: left;\">We thank you for your support!</p><p style=\"text-align: left;\">&nbsp;</p><p style=\"text-align: left;\">~Hotspot Team</p>";
        mail($to, $subject, $message, $headers);
        echo('Thanks for Subscribing, you will recieve a confirmation email soon.');
    }
?>