<?php
    require('inc/CAuth.inc.php');
    require('inc/CRedirect.inc.php');

    CAuth::Logout ();
    CRedirect::To ('prihlasit.php');
?>
