<?php
    // Jalankan session
    session_start();
    
    // Hapus cookie di browser
    setcookie("PHPSESSID", null, time()-60, "/");

    // hapus file session di server
    session_destroy();

    // redirect ke halaman login 
    header("Location: ../login.php");
?> 