<?php

    require 'database.php';
        
            $_SESSION['connection']['state'] = false;
            echo '<div> '. $_SESSION['x'] .'/</div>';
            header('Location: compte.php');
 

?>