<?php 

session_start();

$_SESSION = []; 

// para cerrar sesion lo reescribimos como arreglo vacio

header('Location:/');