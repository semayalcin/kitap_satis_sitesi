<?php
    if(isset($_SESSION["message"])) {
        echo "<div class='alert alert-".$_SESSION["type"]." mb-0 text-center message'>".$_SESSION["message"]."</div>";
        unset($_SESSION["message"]);
        header("refresh:3;url=".$_SESSION["url"]);
    }
?>