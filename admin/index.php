<?php
require '../includes/funciones.php';

$auth = estaAutenticado();
if(!$auth){
    header('Location:/');
}

// improtar la conexion 



require '../includes/config/database.php';
$db = conectarDB();
// escrbir el query 

$query = "SELECT * FROM propiedades";

$resultadoConsulta = mysqli_query($db,$query); /// resultado de la consulta 

//Consultar la BASE DE DATOS
// Muestra mensaje condicional 

$resultado = $_GET['resultado'] ?? null; 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $id = filter_var($id,FILTER_VALIDATE_INT);

    if($id){
        // ELiminar archivo 

        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";

        $resultadoimagen = mysqli_query($db,$query) ;
        $propiedad = mysqli_fetch_assoc($resultadoimagen);
        unlink('../imagenes/' . $propiedad['imagen']);

        // var_dump($propiedad);


        //ELimina la propiedad 
        $query = "DELETE FROM propiedades WHERE id = ${id} ";  
        echo $query ;
        $resultado = mysqli_query($db,$query);

        if($resultado){
            header('Location: /admin?resultado=3');
        }
    }
}
// echo "<pre>";
// var_dump($resultado);
// echo "</pre>" ;
// $resultado = $_GET('mensaje');
// incluye un template 

incluirTemplate('header'); ?>

    <main class="contenedor seccion">
        <h1> Administrador de Bienes raices </h1>
        <?php if(intval($resultado) === 1):?>
          <p class="alerta exito"> Anuncio creado correctamente </p>
        <?php elseif(intval($resultado) === 2):?> 
          <p class="alerta exito"> Anuncio Actualizado Correctamente </p> 
        <?php elseif(intval($resultado) === 3):?> 
          <p class="alerta exito"> Anuncio Eliminado Correctamente </p> 
        <?php endif; ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde"> Crear </a>

        <table class="propiedades">
            <thead> 
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>

                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                    
                </tr>
            </thead>
            <!-- MOSTRAR LA BASE DE DATOS  -->
            <tbody> 
                <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)) : ?> 

                <tr> 
                    <td><?php echo $propiedad['id'] ?> </td>
                    <td><?php echo $propiedad['titulo'] ?></td>
                    <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?> " class="imagen-tabla"></td>
                    <td>$ <?php echo $propiedad['precio'] ?></td>
                    <td>
                        <form method="POST" class="w-100"> 
                        <!--  INPUT HIDDEN -->
                        <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>">
                        <input type="submit" value="Eliminar" class="boton-rojo">                     
                        </form>
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id'];?>"
                         class="boton-amarillo-block">Actualizar </a>
                    </td>
                </tr>
                <?php endwhile ?> 
            </tbody>
        </table>
    </main>



<?php 
// Cerrar la conexión 
                    mysqli_close($db);

incluirTemplate('footer');
?>
