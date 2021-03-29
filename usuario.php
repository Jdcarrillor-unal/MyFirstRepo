<?php 
 /// Improtar la conexion 

require 'includes/config/database.php'; 
$db = conectarDB(); 


 // Crear un email  y password 

 $email = "jdcarrillor@unal.edu.co";
 $password = "password";
$passwordHash = password_hash($password,PASSWORD_BCRYPT);
var_dump($passwordHash);
 /// query para crear la cuenta 

 $query ="INSERT INTO Usuarios(email,password) VALUES  ('${email}','${passwordHash}')";

 echo  $query;
 mysqli_query($db,$query);