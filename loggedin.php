<?php
    session_start();
    
    if(array_key_exists("id", $_COOKIE)){
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(array_key_exists("id", $_SESSION)){
      
    }

    else{
        header("Location: index.php");
    }

    include("header.php");
    include("connection.php");

    $user_id= $_SESSION['id'];

    $query = "SELECT dairy from users WHERE id='$user_id'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);

    if(isset($row['dairy'])){
        $val = $row['dairy'];
    }
    else{
        $val="";
    }
?>
    <a  href="index.php?logout=1"><button style="margin-top:10px; margin-left:30px;" class="btn btn-outline">Logout?</button></a>

    <form class="form-group" method="POST">
        <input style="width:500px; margin-left:30%; margin-bottom:5px;" type="submit" name="update" class="btn btn-success" value='SAVE'>
        <div class="container-fluid">
            <textarea name="content" id="dairy" class="form-control" rows=40, cols=20><?php echo $val ?></textarea>
        </div>
    </form>

<?php
    
    
    if(isset($_POST['update'])){
        
        $cont = $_POST['content'];
        $user_id = $_SESSION['id'];
        $query = "UPDATE users SET dairy='$cont' WHERE id='$user_id' LIMIT 1";
        if(mysqli_query($link, $query)){
            header("Location:loggedin.php");
        }
        else{
            echo "something wrong";
        }
    }
    include("footer.php");
  
   
?>