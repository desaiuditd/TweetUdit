<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/* Load and clear sessions */
session_start();
session_destroy();

/* Redirect to page with the connect to Twitter option. */
header('Location: index.html');
?>
