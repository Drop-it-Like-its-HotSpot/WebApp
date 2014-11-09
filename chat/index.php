<?php
    //if the cookies are set, the user is logged in.
    if(isset($_COOKIE['ID'])){
        header( 'Location: profile.php' );
    }else {
        include('util/functions.php');
        
        if(isset($_POST['username']) && isset($_POST['password'])){
            $results = login($_POST['username'], $_POST['password']);
            if($results['success']){
                setmycookies($results);
                header( 'Location: profile.php' );
            }else{
                $displayerror = "Your Email and Password doesn't match our information.";
            }
        }
    }
?>
<html>
<body>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
<link href="css/login.css" rel="stylesheet">
<div class="parent">
    <img src="img/logo.png"><br />
    <img class="titleImg" src="../img/Title.png">
    <div id="login">
      <form method="post">
        <input class="topInput" type="" name="username" placeholder="Email" value=""><br/>
        <input type="password" name="password" placeholder="Password" value=""><br/>
        <button type="submit" onclick= >Sign In</button>
  
      </form>
    </div>
    <div id="register">
      <button class="register" type="submit" onclick="javascript:location.href='register.php'" >Register</button>
    </div>
</div>
<center>
<?php
//if the login failed, display error
    if(isset($displayerror)){
        echo('<div class="parent" id="login">');
        echo($displayerror);
        echo('</div>');
    }
?>
</center>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>

</script>
</body>
</html>