<?php   

function conectarDB():mysqli{
    // la otra opcion es PDO , CON POO. para inciar mejor mysqli 
    $db = mysqli_connect(
        $host='localhost',
        $user='root',
        $password='Naujdivad01',
        $database='Bienesracies_crud');
    
    if (!$db) {
        echo 'Erro en la conexion 404';
        exit ;
    } 
    return $db ;
}