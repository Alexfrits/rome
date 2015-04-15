<?php

/*  HELPERS USED FOR DEVELOPMENT PURPOSES
        Put the line below in header.php.
        <?php include('dev-helpers.php'); ?>
*/


// print_r
    function a($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

/*  GET CURRENT .php FILE NAME
        Put the line in your body, e.g. after any get_header() call.
        <?php now_in(__FILE__) ?>
*/
    function now_in($file) {
        $file = explode('\\', $file);
        $n = count($file);

        echo '<p class="debug-msg">Now in: ';
        if($n > 1) // display parent directory if it exists
            echo $file[$n-2].'/';
        echo '<strong>'.$file[$n-1].'</strong></p>';
    }
?>
