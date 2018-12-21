<?php 
    //buka session
    session_start();
    
    //hapus Cookie Yang dibuat oleh Server (Session)
    setcookie("PHPSESSID", null, time()-60, "/");

    //hapus fisik Session di server
    session_destroy();

    //redirect ke halaman login
    header("Location: index.php");
?>