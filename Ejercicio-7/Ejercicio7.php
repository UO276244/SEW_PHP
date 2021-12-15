<?php



class EditorialManager{

    protected $lastDone;
    protected $currentMenu;
    protected $dbName;
    protected $connection;

    public function __construct(){

        $this->lastDone ="";
        $this->currentResult = "";
        $this->dbName = "db_sew_php_ej7";
    }

    public function crearConexion(){

        $db = new mysqli("localhost","DBUSER2021","DBPSWD2021","db_sew_php_ej7");

        if($db->connect_errno){
            $this->lastDone =  "<p>Error de conexion: " . $db->connect_error . "</p>";
           return null;
        }else{
            
            $this->lastDone = "<p>Conexion establecida con exito</p>";
            $this->connection = $db;
            return $db;
            
            
        }

    }

    private function printPeliculas($result){

        $count = 0;

        while($row = $result->fetch_array()){

            $queryForPublicado = $this->connection->prepare("SELECT Pais from Publicadoen where Libro_ISBN = ?");
            $queryForPublicado->bind_param('s',$row['ISBN']);
            $resultPubl = $queryForPublicado->execute();
            $resultPubl = $queryForPublicado->get_result();

            $count = $count + 1;

            $imgFileName = 'multimedia/img' .  $row['ISBN'] . '.jpg';
            
            $this->currentResult .= "<section><h3>Resultado ". $count . "</h3><img src='".$imgFileName ."' alt='imagen" . $row['Titulo'] . "'/>";
            $this->currentResult .= "<ul> <li>Titulo: " .  $row['Titulo'] ."</li> <li>Autor: " .  $row['Nombre_autor'] ." " . $row['Apellidos'] . "</li>" ;
            $this->currentResult .= "<li>Editorial: " .  $row['Empresa']. "</li>";
            $this->currentResult .= "<li>Año publicacion: " .  $row['Año_publicacion']. "</li>";
            $this->currentResult .= "<li>Publicado en: " ;
           
            while($pais = $resultPubl->fetch_array()){$this->currentResult .=  $pais['Pais'] . " - ";}

            $this->currentResult .=  "</li>";

            $agotado = 'SI';
            if($row['Agotado'] == '0'){
                $agotado = 'NO';
            }

            $this->currentResult .= "<li>Agotado: " .  $agotado . "</li>";



            $this->currentResult .= " </ul></section>";

           

        }

        return $count;

    }

    public function ejecutarBusquedaLibro($claves){

        $this->connection =  $this->crearConexion();
        $this->connection->select_db($this->dbName);

        if($this->connection == null){
            $this->currentResult = 'Error en la conexion';
            return;
        }

        $claves = '%' . $claves . '%';
       
        $query = $this->connection->prepare("SELECT l.*, a.Nombre_autor,a.Apellidos, e.Empresa FROM Libro as l, Autor as a, Editorial as e where Titulo like ? and l.Autor = a.AutorId and l.Editorial = e.EditorialId;");

        if($query == false){
            $this->currentResult = 'Error en la busqueda';
            $this->connection->close();
            return;
        }

        
        $query->bind_param('s',$claves);
        $result = $query->execute();

       
        if($result == false)
        {
            $this->currentResult = 'Sin resultados';
            $this->connection->close();
            return;
        }

        $result = $query->get_result();
        $count = 0;
        $this->currentResult = '';
        $count = $this->printPeliculas($result);

        if($count == 0){
            $this->currentResult = 'Sin resultados';
        }

        $this->connection->close();

    }

    public function ejecutarBusquedaEditorial($claves){

        $this->connection =  $this->crearConexion();
        $this->connection->select_db($this->dbName);

        if($this->connection == null){
            $this->currentResult = 'Error en la conexion';
            return;
        }

        $claves = '%' . $claves . '%';
       
        $query = $this->connection->prepare("SELECT * FROM Editorial  where Empresa like ? ;");

        if($query == false){
            $this->currentResult = 'Error en la busqueda';
            $this->connection->close();
            return;
        }

        
        $query->bind_param('s',$claves);
        $result = $query->execute();

       
        if($result == false)
        {
            $this->currentResult = 'Sin resultados';
            $this->connection->close();
            return;
        }

        $result = $query->get_result();
        $count = 0;
        $this->currentResult = '';
        while($row = $result->fetch_array()){

            $count = $count + 1;
            $imgFileName = 'multimedia/imgEd' .  $row['EditorialId'] . '.jpg';
            
            $this->currentResult .= "<section><h3>Resultado ". $count . "</h3><img src='".$imgFileName ."' alt='imagen " . $row['Empresa'] . "'/>";
            $this->currentResult .= "<ul> <li>Nombre: " .  $row['Empresa'] ."</li><li>Director: " . $row['Director'] . "</li>" ;

            $this->currentResult .= " </ul></section>";

           

        }

        if($count == 0){
            $this->currentResult = 'Sin resultados';
        }

        $this->connection->close();



    }


    public function ejecutarBusquedaAutor($claves){

        $this->connection =  $this->crearConexion();
        $this->connection->select_db($this->dbName);

        if($this->connection == null){
            $this->currentResult = 'Error en la conexion';
            return;
        }

        


        
       
        $query = $this->connection->prepare("SELECT * FROM Autor  where Nombre_autor like ? and Apellidos like ?;");

        if($query == false){
            $this->currentResult = 'Error en la busqueda';
            $this->connection->close();
            return;
        }

        
        if(strpos($claves," ") == false){ //Si hay espacios en la busqueda (Nombre apellido)

            $query->bind_param('ss',$claves,$claves);
            
        }else{

            $clavesArray = explode(" ", $claves);
            $nombre = '%'.$clavesArray[0].'%';
            $apellido = '%'.$clavesArray[1].'%';
            $query->bind_param('ss',$nombre,$apellido);
            
           
        }

        
        
        $result = $query->execute();

       
        if($result == false)
        {
            $this->currentResult = 'Sin resultados';
            $this->connection->close();
            return;
        }

        $result = $query->get_result();
        $count = 0;
        $this->currentResult = '';
        while($row = $result->fetch_array()){
            $count = $count + 1;
           
            $imgFileName = 'multimedia/imgAutor' .  $row['AutorId'] . '.jpg';
            
            $this->currentResult .= "<section><h3>Resultado ". $count . "</h3><img src='".$imgFileName ."' alt='imagen" . $row['Nombre_autor'] . "'/>";
            $this->currentResult .= "<ul> <li>Nombre: " .  $row['Nombre_autor'] ."</li><li>Apellidos: " . $row['Apellidos'] . "</li>" ;
            $this->currentResult .= "<li>Edad: " .  $row['Edad']. "</li>";
           

            $this->currentResult .= " </ul></section>";

            

        }

        if($count == 0){
            $this->currentResult = 'Sin resultados';
        }

        $this->connection->close();

    }


    public function ejecutarBusquedaLibroPorEditorial($claves){

        $this->connection =  $this->crearConexion();
        $this->connection->select_db($this->dbName);

        if($this->connection == null){
            $this->currentResult = 'Error en la conexion';
            return;
        }

        $claves = '%' . $claves . '%';
       
        $query = $this->connection->prepare("SELECT l.*, a.Nombre_autor,a.Apellidos, e.Empresa FROM Libro as l, Autor as a, Editorial as e where ((e.Empresa like ?) and l.Autor = a.AutorId and l.Editorial = e.EditorialId);");

        if($query == false){
            $this->currentResult = 'Error en la busqueda';
            $this->connection->close();
            return;
        }

        
        $query->bind_param('s',$claves);
        $result = $query->execute();

       
        if($result == false)
        {
            $this->currentResult = 'Sin resultados';
            $this->connection->close();
            return;
        }

        $result = $query->get_result();
        $count = 0;
        $this->currentResult = '';
        $count = $this->printPeliculas($result);

        if($count == 0){
            $this->currentResult = 'Sin resultados';
        }

        $this->connection->close();



    }

    public function ejecutarBusquedaLibroPorAutor($claves){

        $this->connection =  $this->crearConexion();
        $this->connection->select_db($this->dbName);

        if($this->connection == null){
            $this->currentResult = 'Error en la conexion';
            return;
        }

        $claves = '%' . $claves . '%';
       
        $query = $this->connection->prepare("SELECT l.*, a.Nombre_autor,a.Apellidos, e.Empresa FROM Libro as l, Autor as a, Editorial as e where ((a.Nombre_autor like ? or a.Apellidos like ?) and l.Autor = a.AutorId and l.Editorial = e.EditorialId);");

        if($query == false){
            $this->currentResult = 'Error en la busqueda';
            $this->connection->close();
            return;
        }

        
        if(strpos($claves," ") == false){ //Si hay espacios en la busqueda (Nombre apellido)

            $query->bind_param('ss',$claves,$claves);
            
        }else{

            $clavesArray = explode(" ", $claves);
            $nombre = '%'.$clavesArray[0].'%';
            $apellido = '%'.$clavesArray[1].'%';
            $query->bind_param('ss',$nombre,$apellido);
            
           
        }

        
        $result = $query->execute();

       
        if($result == false)
        {
            $this->currentResult = 'Sin resultados';
            $this->connection->close();
            return;
        }

        $result = $query->get_result();
        
        $count = 0;
        $this->currentResult = '';
        $count = $this->printPeliculas($result);

        if($count == 0){
            $this->currentResult = 'Sin resultados';
        }

        $this->connection->close();



    }


    

    public function printCurrentResult(){
        return $this->currentResult;
    }

 

    public function getLastDone(){
        return $this->lastDone;
    }
}



if(!isset($_SESSION['editorial'])){
    

    $em = new EditorialManager();
    $em->crearConexion();
    $_SESSION['editorial'] = $em;
    
}

if(count($_POST)>0){

   

    $em = $_SESSION['editorial'];


 
    if(isset($_POST['barraBusqueda'])){

        if(isset($_POST['buscarLib']))  $em->ejecutarBusquedaLibro($_POST['barraBusqueda']);


        if(isset($_POST['buscarAu']))  $em->ejecutarBusquedaAutor($_POST['barraBusqueda']);
        if(isset($_POST['buscarLibPorAu']))  $em->ejecutarBusquedaLibroPorAutor($_POST['barraBusqueda']);


        if(isset($_POST['buscarEd']))  $em->ejecutarBusquedaEditorial($_POST['barraBusqueda']);
        if(isset($_POST['buscarLibPorEd']))  $em->ejecutarBusquedaLibroPorEditorial($_POST['barraBusqueda']);
            
            
        
    }
 
   
    
    

    
    $_SESSION['editorial'] = $em;

 
}




echo "<!DOCTYPE HTML>

<html lang='es'>

<head>
    <!-- Datos que describen el documento -->

    <meta charset='UTF-8' />
    <title>Ejercicio 7</title>
    <link rel='stylesheet' type='text/css' href='Ejercicio7.css' />
    

    <meta name='author' content='Martin Beltran' />
    <meta name='description' content='Ejercicio 7' />
    <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
    
    
       
</head>
<body>

    <h1>BIBLIOTECA MUNICIPAL DE LLANERA</h1>
    <form action='#' method='post' name='menuForm' enctype='multipart/form-data'>


        
        <label for= 'busqueda'> Busqueda: </label> 
        <input type='text' name='barraBusqueda' id='busqueda' />
        <input type='submit' value='Buscar Libro' name='buscarLib' />
        <input type='submit' value='Buscar Autor' name='buscarAu' />
        <input type='submit' value='Buscar Libro por Autor' name='buscarLibPorAu' />
        <input type='submit' value='Buscar Editorial' name='buscarEd' />
        <input type='submit' value='Buscar Libro por Editorial' name='buscarLibPorEd' />
        
       
        
    </form>
    <section>
    <h2>RESULTADO DE BUSQUEDA</h2>

    ";


    echo $_SESSION['editorial']->printCurrentResult();

    // echo "<h2>ESTADO ACTUAL</h2>"; 
        
        
    //     echo $_SESSION['editorial']->getLastDone();
    //     echo "
        
        

    echo "
    </section>


</body>

</html>";


?>