<?php

    include('phpqrcode.php');
        
    $param = urldecode($_GET['url']); 
    
    ob_start("callback");
    
    $codeText = $param;
    
    ob_end_clean();
    
    QRcode::png($codeText);

?>