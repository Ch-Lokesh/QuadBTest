<?php
    session_start();

    $php_errormsg = "";

    if(array_key_exists("logout", $_GET)){
        unset($_SESSION);
        setcookie("id", "", time()- 60*60);
        $_COOKIE["id"] = "";
    }

   else if((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE["id"])){
       header("Location:loggedin.php");
   }

    if(array_key_exists("submit" , $_POST)){

       include("connection.php");

        if(!$_POST['email']){
            $php_errormsg .= "Please Enter your mail id <br>";
        }

        if(!$_POST['password']){
            $php_errormsg .= "Please Enter your password <br>";
        }

        if($php_errormsg != ""){
            $php_errormsg = "<p>There are error(s) in your form</p>".$php_errormsg;
        }

        else{

            if($_POST['signup'] == '1'){
            
                $mail = htmlentities(mysqli_real_escape_string($link, $_POST['email']));
    
                $query = "SELECT * FROM users WHERE email = '$mail'";
                $result = mysqli_query($link, $query);

                if(mysqli_num_rows($result) > 0){
                    $php_errormsg .= "That email adress already exists";
                }
                else{
                    $pass = htmlentities(mysqli_real_escape_string($link, $_POST['password']));
                    $query = "INSERT INTO users (email, password) values ('$mail', '$pass')";

                    if(!mysqli_query($link, $query)){
                        $php_errormsg = "<p>error not able to sign you up</p>";
                    }
                    else{
                        $user_id = mysqli_insert_id($link);
                        $hash = md5(md5($user_id).$_POST['password']);
                        $query = "UPDATE users SET password = '$hash' WHERE id = '$user_id' LIMIT 1";

                        mysqli_query($link, $query);
                        $_SESSION['id'] = $user_id;

                        if($_POST['stayLoggedIn'] == 1){
                            setcookie("id", $user_id, time()+60*60*24*365);
                        }

                        header("Location:loggedin.php");
                    }
                }
            }

            else{
                $mail = htmlentities(mysqli_real_escape_string($link, $_POST['email']));
                $pass = htmlentities(mysqli_real_escape_string($link, $_POST['password']));

                $query = "SELECT * FROM users WHERE email = '$mail'";
                $result = mysqli_query($link, $query);
                $row = mysqli_fetch_array($result);
                
                if(isset($row)){

                    $hash = md5(md5($row["id"]).$pass);
                    if($hash == $row['password']){
                        $_SESSION['id'] = $row['id'];

                        if($_POST['stayLoggedIn'] == 1){
                            setcookie("id", $row['id'], time()+60*60*24*365);
                        }

                        header("Location:loggedin.php");
                    }
                    else{
                        $php_errormsg = "User credentials not found, kindly check password and id";
                    }
                }
                
                else{
                    $php_errormsg = "User credentials not found, kindly check password and id";

                }
            }
        }
    }

?>

    <?php include("header.php") ?>    

    <div id = "homePageCont" class="container">

    <h1>Secret Dairy</h1>

        <p><strong>Save your thoughts permanently and securely</strong></p>

        <div id="error"><?php if($php_errormsg !=""){
            echo '<div class ="alert alert-danger" role="alert">' . $php_errormsg . '</div>';
        }  ?></div>
        <form method="post" id='signup_form'>
            <p>Sign up with just with an email address</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Enter Email">
            </fieldset>

            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Enter Password">
            </fieldset>

            <div class="checkbox">
                <label style="color:white" >
                    <input type="checkbox" name="stayLoggedIn" value=1>
                    Stay Logged In
                </label>
                <input class="form-control" type="hidden" name="signup" value="1">
            </div>

            <fieldset class="form-group">
                <input class="btn btn-success"  type="submit" name="submit" value="Sign Up">
            </fieldset>
            <p class = "ch"><a  class='toggleForm' id="showLoginInForm">Have account? Login</a> </p>
        </form>

        

        <form method="post" id = 'login_form'>
            <p>Log in using your username and password</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Enter Email">
            </fieldset>

            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Enter Password">
            </fieldset>

            <div class="checkbox">
                <label style="color:white">
                    <input type="checkbox" name="stayLoggedIn" value=1>
                    Stay Logged In
                </label>
                <input class="form-control" type="hidden" name="signup" value="0">
            </div>

            <fieldset class="form-group">
                <input class="btn btn-success" type="submit" name="submit" value="Log In">
            </fieldset>
            <p class = "ch"><a  class='toggleForm' id="showLoginInForm">New Here? Sign Up</a></p>
        </form>
    </div>
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <?php include("footer.php"); ?>

    

   