<?php
    #Iniciamos sesion
    //echo session_id();
    session_start();

    
    $usuario = $_POST["f_usuario"];
    $password = md5($_POST["f_contraseña"]);
    

    include_once('dbconect.php');
    $database = new Connection();
	$db = $database->open(); 
    
    $db = new PDO("mysql:host=127.0.0.1;dbname=dudas","root","");
    $sql = $db->query("Select * from registros where registros.Num_Doc = '".$usuario."' and registros.contra_usuario = '".$password."'");
    /*
    $sql = $db->query("Select * from users inner join item ON users.name_user = '".$usuario."' and users.passw_user = '".$password."' and users.rol_user = item.id_rol'");
    /*SELECT * FROM users INNER JOIN item ON users.rol_user = item.id_rol */
    /*Almacenamos el resultado de fetchAll en una variable*/
    $arrDatos = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    /* 
    $nombre = $arrDatos['nonmbre'];
    $apellido= $arrDatos['apellido']; */
    //print_r($arrDatos);

/* Este sera el archivo que recibira los datos de autenticacion del Login.php
    los procesasra y validara la informacion recuibida para proceder a cargar
    la pagina del Portafolio. */
    
/*  Para leer los datos que fueron enviados al formulario, accedemos al arreglo 
    superglobal llamado $_POST en PHP, y para obtener un valor accedemos 
    a $_POST["clave"] en donde clave es el "name" que le dimos al input */

    # Comprobamos que esten llegando los datos
    
    echo $_SESSION["user"] . "<br>";
    echo $usuario . "<br>";
    echo $password . "<br>";

     /*Recorremos todos los resultados, ya no hace falta invocar más a fetchAll como si fuera fetch...*/
    if(!empty($arrDatos)){
        foreach ($arrDatos as $datos) {
            $nombre = $datos['Nombre'];
            $apellido = $datos['Apellido'];
            $roles = $datos['Cargo'];
            $userName = $nombre ." ". $apellido;
            //echo $datos['name_user'] . " - " . $datos['rol_user'] . "<br>";
     
            if(($datos['Num_Doc']==$usuario) && ($datos['contra_usuario']==$password)){
                //Cerrar la Conexion
                //$database->close();
                 if($datos['Cargo']=="Admin"){
                    header("Location: index_admin.php");
                 }elseif($datos['Cargo']=="Vigilante"){
                     header("Location: index.php");
                 }else{
                     $mensaje = "Rol no válido o no registrado..";
                     header('Location: alert.php?mensaje='. $mensaje);
                 }
            }
         }
    }else{
       $mensaje = "Usuario o contraseña Incorrecto";
       header('Location: alert.php?mensaje='.urlencode( $mensaje));
    }
    
    //$query=mysqli_query($conn,"Select * from users where users.name_user = '".$usuario."' and users.passw_user = '".$password."'");
     
    //session_destroy();
    $_SESSION["user"] = $userName;
    $_SESSION["rol"] = $roles;
?>
