<?php 
require 'includes/config/database.php';
$db = conectarDB();



$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //  echo "<pre>" ;
    //  var_dump($_POST);
    // echo "</pre>";


    $email = mysqli_real_escape_string( $db, filter_var($_POST['email'],FILTER_VALIDATE_EMAIL));
    // var_dump($email);
    $password = mysqli_real_escape_string($db, $_POST['password']);
   

    if(!$email){
        $errores[] = 'Email es obligatorio';
    }
    if(!$password){
        $errores[] = 'password es obligatorio';
    }
    if(empty($errores)){
        /// Revisar si el usuario existe 

        $query = " SELECT * FROM Usuarios WHERE email = '${email}' ";
        $resultado = mysqli_query($db,$query);
        // var_dump($resultado);

        if($resultado->num_rows){
            $usuario  = mysqli_fetch_assoc($resultado) ;
                // Verificar el password 
         
            $auth = password_verify($password, $usuario['password']);

         

            
            // verificar la funcion password _verify 
            
          if($password == $usuario['password']){
              // El usario ha sido autenticado 
            session_start();

            $_SESSION['usuario'] = $usuario['email'];
            $_SESSION['login'] = true;
            

            header('Location:/admin');

          }else{
              $errores [] = 'Password Incorrecto';
          }

           
        }else{
            $errores[] = 'Usuario no exite';
        }



    }
}

// Incluye el header 
require 'includes/funciones.php' ;

incluirTemplate('header');
?>


    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesion</h1>
        <?php foreach ($errores as $error):?>
            <div class="alerta error">
                <?php echo $error  ;?>
            </div>
        <?php  endforeach; ?>

        <form method="POST" class="formulario ">
        <fieldset>
                <legend>Email y Password</legend>


                <label for="email">E-mail</label>
                <input type="email" placeholder="Tu Email" id="email" name="email" required >

                <label for="password">Contraseña </label>
                <input type="password" placeholder="Tu password" id="password" name="password" required >

            </fieldset>

            <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
            </form>
    </main>


 <?php 
incluirTemplate('footer');
?>


  