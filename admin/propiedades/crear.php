<?php
require '../../includes/funciones.php';

$auth = estaAutenticado();
if(!$auth){
    header('Location:/');
}
// Base de datos 
  require '../../includes/config/database.php';
  $db = conectarDB();
//   var_dump($db); 


// CONSULTAR PARA OBTENER LOS VENDEDORES 

$consulta  = "SELECT * FROM vendedores";
$resultado = mysqli_query($db,$consulta);

// Array con mensajes de erroes 
$errores = [];
// inicializar las variables con un string vacio 
$titulo =""; 
$precio ="";
$descripcion = "";
$habitaciones = "";
$wc = "";
$estacionamiento = "";
$vendedor = "";

// Ejectuar el  codigo despues de que el usuario envia el formulario 

if($_SERVER['REQUEST_METHOD'] == 'POST'){
 /// SANITIZAR MI SITIO WEB , limpiar las variables que no son necesarios 

    echo "<pre>" ;
    var_dump($_FILES);
      echo "</pre>"; 

      
    $titulo = mysqli_real_escape_string($db, $_POST['titulo']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);
    $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
    $habitaciones = mysqli_real_escape_string($db, $_POST['habitaciones']);
    $wc = mysqli_real_escape_string( $db,$_POST['wc']);
    $estacionamiento = mysqli_real_escape_string($db, $_POST['parqueadero']);
    $vendedor = mysqli_real_escape_string($db,$_POST['vendedorid']);
    $creado = date('Y/m/d');


    $imagen = $_FILES['imagen'];





    if (!$titulo) {
        $errores[] = 'Debes añadir un Titulo';
    }
    if (!$precio) {
        $errores[] = 'Debes añadir un precio';
    }
    if (strlen($descripcion)<= 50 ) {
        $errores[] = 'Debes añadir una descrición con un minimo de  50 caracteres ';
    }
    if (!$habitaciones) {
        $errores[] = 'Debes añadir un número de habitaciones ';
    }    
    if (!$wc) {
        $errores[] = 'Debes añadir un número de baños';
    }
    if (!$estacionamiento) {
        $errores[] = 'Debes añadir cantidad de estacionamientos';
    }
    if (!$vendedor) {
        $errores[] = 'Debes elegir un vendedor ';
        echo $vendedor ;
    }
    if (!$imagen['name'] || $imagen['error']) {
        $errores[]= 'La imagen es Obligatoria'; 
    }
    /// Validar por tamaño (100kb max) 
    $medida = 1000*500 ; // convertir de bytes a kilibyte 
    if($imagen['size'] > $medida){
        $errores[] = 'La Imagen pesa por encima de los 100kbytes '; 
    }

    // var_dump($errores);
    // exit;

    /// insertar a la base de datos  mientras no halla errores 
    if(empty($errores)) {
        // crear Carpeta 
        $carpetaImagenes = '../../imagenes/'; 
        

        if(!is_dir($carptetaImagenes)){
            mkdir($carpetaImagenes); 
        }
     
        // generar un nombre unico 
        $nombreImagen = md5( uniqid( rand(),true) ) . ".jpg";

       
        // var_dump($nombeImagen);
        move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen  );
        
        


        $query = "INSERT INTO propiedades (titulo,precio,imagen,descripcion,habitaciones,
        wc,estacionamiento,creado,vendedorid) 
        VALUES ('$titulo','$precio','$nombreImagen','$descripcion','$habitaciones','$wc',
        '$estacionamiento','$creado','$vendedor')"; 
    
        $resultado = mysqli_query($db,$query) ;
        if($resultado){
            /// redireccionar al Usuario 
            header('Location: /admin?resultado=1');
        }
    } 
   
}


incluirTemplate('header'); ?>

<!--  POST PARA enviar datos de forma segura 
GET para enviarlos a traves de al URL  -->
    <main class="contenedor seccion"  >
        <h1> Crear </h1>
        <a href="/admin" class="boton boton-verde"> Volver </a> 

        <?php foreach ($errores as $error => $value):?>
            <div class="alerta error"> 
        <?php echo $value ; ?>
            </div>
        <?php endforeach ; ?> 
        <form  class="formulario" method="POST" 
        action="/admin/propiedades/crear.php" enctype="multipart/form-data" >
            <fieldset>
                <legend> Información General  </legend>
                <label for="titulo"> Titulo</label>
                <input type="text" id="titulo"  name="titulo"  placeholder="Titulo Propiedad" value="<?php echo $titulo; ?>">

                <label for="precio"> Precio</label>
                <input   type="number" id="precio" name="precio" placeholder="Precio Propiedad" value="<?php echo $precio; ?>">

                <label for="imagen"> Imagen</label>
                <input  type="file"  id="imagen" accept="image/jpg,image/png" name="imagen">

                <label for="descripcion"> Descripcion: </label>
                <textarea name="descripcion" id="descripcion"  >  <?php echo $descripcion; ?> </textarea>
                
            </fieldset>
            <fieldset>
                <legend> Información de la Propiedad </legend>
                <label for="habitaciones"> Habitaciones:</label>
                <input type="number" name="habitaciones" id="habitaciones" placeholder="Número de Habitaciones" value="<?php echo $habitaciones; ?>" >


                <label for="wc"> Baños:</label>
                <input type="number"   name="wc" id="wc" placeholder="Número de Baños" min="1" max="9" value="<?php echo $wc; ?>" >

                <label for="parqueadero"> Estacionamiento:</label>
                <input type="number" name="parqueadero" id="parqueadero" placeholder="Número de estacionamientos" value="<?php echo $estacionamiento; ?>"  >

            </fieldset>
            <fieldset> 
                <legend> Vendedor </legend>
                <select name="vendedorid" >
                    <option value=""> >--Seleccione--<</option>
                    <?php while($row = mysqli_fetch_assoc($resultado)): ?> 
                    <option  <?php echo $vendedor === $row['id'] ? 'selected' : ''; ?> 
                    value="<?php echo $row['id'];?>">
                    <?php echo $row['nombre'] .  $row['apellido'] ; ?> </option>
                    <?php endwhile; ?>     
                    
                </select>
            </fieldset>
            <input type="submit"  value="Crear Propiedad" class=" boton-verde boton">  
        </form>
         
    </main>

<?php 
incluirTemplate('footer');
?>
