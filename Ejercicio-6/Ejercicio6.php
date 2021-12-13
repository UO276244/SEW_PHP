

<?php 

session_start();

class BaseDatos{

    protected $db;
    protected $lastDone;
    protected $currentDB;
    protected $eliminarNotasNoAprobadas;

    public function __constructor(){

        
        
        $this->lastDone="Aun no se han realizado acciones.";
        

        $this->currentDB = '';

 

        
        $this->eliminarNotasNoAprobadas = "DELETE FROM PruebasUsabilidad WHERE Valoracion < 5;";

       

    }


    public function getLastDone(){
        return $this->lastDone;
    }


    public function crearConexion(){

        $this->db = new mysqli("localhost","DBUSER2021","DBPSWD2021");

        if($this->db->connect_errno){
            $this->lastDone =  "<p>Error de conexion: " . $this->db->connect_error . "</p>";
            
            
        }else{
            
            $this->lastDone = "<p>Conexion establecida con exito</p>";
          
            
            
        }

    }

    public function closeConnection(){

        $this->db->close();

    }


    
    public function ejecutarQuery($query){

        $resultado = $this->db->query($query);

        if ($resultado == true) { //ejecutando query

                $this->lastDone =  "<p>Query ejecutada correctamente: " . $query . "</p>";
                
                return $resultado;
    
        } else {
    
            $this->lastDone = "<p>Error al ejecutar consulta " . $query . "</p>";
            
    
        }

    }


    public function crearBaseDeDatos(){

        $this->crearConexion();
        
        $this->ejecutarQuery("CREATE DATABASE  IF NOT EXISTS db_sew_php COLLATE utf8_spanish_ci ;");

        
        $this->currentDB = "db_sew_php";

        

        $this->db->select_db($this->currentDB);

        $this->lastDone = "<p>Base de datos db_sew_php creada correctamente</p>";  
        
        

    }

    public function crearTabla(){

        $this->crearConexion();

        

        $this->tablaScript = " CREATE TABLE if not exists PruebasUsabilidad (
       IDRealizador INT NOT NULL,
       Nombre_Realizador VARCHAR(255) NOT NULL,
       Apellidos_Realizador VARCHAR(255) NOT NULL,
       Emai VARCHAR(255) NOT NULL,
       Telefono INT NOT NULL,
       Edad INT NOT NULL,
       Sexo varchar(255) NOT NULL,
       PericiaInformatica INT(255) NOT NULL,
       Tiempo INT(255) NOT NULL,
       Correctamente BOOLEAN NOT NULL,
       Comentarios VARCHAR(255) NOT NULL,
       Propuestas VARCHAR(255) NOT NULL,
       Valoracion INT(255) NOT NULL
        );
   
        ";

        
        $this->db->select_db($this->currentDB);

        
        $this->ejecutarQuery($this->tablaScript);

        $this->lastDone = "<p>Tabla PruebasUsabilidad creada correctamente</p>";
       

    }

    public function insertarDatos(){

        $this->crearConexion();


        $this->db->select_db($this->currentDB);

        
        
        $insert1 = "INSERT INTO PruebasUsabilidad(IDRealizador,Nombre_Realizador,Apellidos_Realizador,Emai,Telefono,Edad,Sexo,PericiaInformatica,Tiempo,Correctamente,Comentarios,Propuestas,Valoracion) 
        VALUES (1,'Martin','Beltran Diaz','UO276244@uniovi.es',123123123,20,'masculino',8,65,1,'se lo paso bien','menos ejercicios',7);";
        $insert2 = "INSERT INTO PruebasUsabilidad(IDRealizador,Nombre_Realizador,Apellidos_Realizador,Emai,Telefono,Edad,Sexo,PericiaInformatica,Tiempo,Correctamente,Comentarios,Propuestas,Valoracion) 
        VALUES (2,'Paco','Paquirrin Pacoso','el_paco@gmail.es',456456456,65,'masculino',2,3453,0,'se lo paso mal','menos ejercicios',4);";
        $insert3= "INSERT INTO PruebasUsabilidad(IDRealizador,Nombre_Realizador,Apellidos_Realizador,Emai,Telefono,Edad,Sexo,PericiaInformatica,Tiempo,Correctamente,Comentarios,Propuestas,Valoracion) 
        VALUES (3,'Eren','Jagger Mainster','elti_tan@gmail.es',789789789,20,'masculino',0,456789,0,'se enfado y se fue','menos ejercicios',0);";
        $insert4 = "INSERT INTO PruebasUsabilidad(IDRealizador,Nombre_Realizador,Apellidos_Realizador,Emai,Telefono,Edad,Sexo,PericiaInformatica,Tiempo,Correctamente,Comentarios,Propuestas,Valoracion) 
        VALUES (4,'Biza','Rap Diaz','elbizarap@gmail.es',696969696,32,'femenino',10,5,1,'hizo la prueba casi instantanea','que no sea tan facil',8);";


        $this->ejecutarQuery('delete from pruebasusabilidad;');
        
        $this->ejecutarQuery($insert1);
        $this->ejecutarQuery($insert2);
        $this->ejecutarQuery($insert3);
        $this->ejecutarQuery($insert4);


        $this->lastDone = "<p>Datos insertados correctamente</p>";

    }

    public function buscarValoracionesAprobadas(){

        $this->crearConexion();

        $this->db->select_db($this->currentDB);

        $buscarDatosAprobados = "SELECT * FROM pruebasusabilidad where Valoracion >= 5;";

        
        $resultado = $this->ejecutarQuery($buscarDatosAprobados);
        if($resultado){
            $this->lastDone = "<p>Valoraciones positivas: </p>";
            while($row = $resultado->fetch_array()){
                $this->lastDone .= "<p>Nombre: " . $row['Nombre_Realizador'] . " - Apellidos: " . $row['Apellidos_Realizador'] . " - Valoracion: " . $row['Valoracion'] . "</p>";
            }

            
        }else{
            $this->lastDone = "<p>Error al buscar los aprobados.</p>";
        }

       

    }

    public function incrementarNotas(){

        $this->crearConexion();
        $this->db->select_db($this->currentDB);

        $updatearNotas = "UPDATE PruebasUsabilidad SET Valoracion = Valoracion +1;";
        $this->ejecutarQuery($updatearNotas);

        $this->lastDone = "<p>Valoraciones incrementadas en 1 punto</p>";
    }

    public function borrarTodo(){

        $this->crearConexion();
        $this->db->select_db($this->currentDB);
        $borrarTodo = "delete from pruebasusabilidad;";
        $this->ejecutarQuery($borrarTodo);

        $this->lastDone = "<p>Todos los datos de la tabla borrados</p>";

    }


    public function informe(){

        $numeroUsuarios = 0;
        $edadTotal = 0;
        $numeroHombres = 0;
        $periciaTotal = 0;
        $tiempoTotal = 0;
        $aciertosTotales = 0;
        $valoracionTotal = 0;


        $this->crearConexion();

        $this->db->select_db($this->currentDB);

        $buscarAll = "SELECT * FROM pruebasusabilidad ;";

        
        $resultado = $this->ejecutarQuery($buscarAll);
        if($resultado){
            $this->lastDone = "<p>Informe: </p>";
            while($row = $resultado->fetch_array()){

                $numeroUsuarios =  $numeroUsuarios + 1;

                $edadTotal = $edadTotal + $row['Edad'];

                if($row['Sexo'] == 'masculino'){
                    $numeroHombres = $numeroHombres + 1;
                }

                $periciaTotal = $periciaTotal + $row['PericiaInformatica'];

                $tiempoTotal = $tiempoTotal + $row['Tiempo'];

               if($row['Correctamente'] == 1){
                    $aciertosTotales = $aciertosTotales + 1;
               }

               $valoracionTotal =  $valoracionTotal + $row['Valoracion'];
                
            }

            if($numeroUsuarios >= 1){

                $edadMedia = $edadTotal / $numeroUsuarios;
                $porHombres = ($numeroHombres / $numeroUsuarios) * 100;
                $porMujeres = 100 - $porHombres;
                $mediaPericia = $periciaTotal / $numeroUsuarios;
                $mediaTiempo = $tiempoTotal / $numeroUsuarios;
                $porCorrecto = ($aciertosTotales / $numeroUsuarios)*100;
                
                $mediaValor = $valoracionTotal / $numeroUsuarios;
    
    
                $this->lastDone .= "<p>Edad media: ".$edadMedia."</p>";
                $this->lastDone .= "<p>Hombres: ". $porHombres . "% - Mujeres: " . $porMujeres ."%</p>";
                $this->lastDone .= "<p>Pericia media: " . $mediaPericia. "</p>";
                $this->lastDone .= "<p>Timepo medio: " . $mediaTiempo . "</p>";
                $this->lastDone .= "<p>Porcentaje de aciertos: " . $porCorrecto . "%</p>";
                $this->lastDone .= "<p>Valoracion media: " . $mediaValor . "</p>";
    

            }else{
                $this->lastDone .= "<p>No hay registros aun</p>";
            }
           
            
        }else{
            $this->lastDone = "<p>Error al generar informe.</p>";
        }


        




    }


    public function descargarDatosCSV(){


        $this->crearConexion();

        $this->db->select_db($this->currentDB);

        $buscarAll = "SELECT * FROM pruebasusabilidad ;";

        
        $resultado = $this->ejecutarQuery($buscarAll);
        if($resultado){


           
            $fp = fopen('pruebasUsabilidad.csv', 'w');
            $this->lastDone = "<p>Recuerde cambiar los ajustes en Excel para leer archivos separados por ',' y no ';'.</p>";
            
            while($row = mysqli_fetch_assoc($resultado)){

                fputcsv($fp, $row);
            }
            
            fclose($fp);
            $this->lastDone = "<p>Recuerde cambiar los ajustes en Excel para leer archivos separados por ',' y no ';'.</p>";
            


        }else{
            $this->lastDone = "<p>Error al exportar CSV.</p>";

            return;
        }

       
        

        


        $fileName = basename('pruebasUsabilidad.csv');
        $filePath = ''.$fileName;
        if(!empty($fileName) && file_exists($filePath)){
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$fileName");
            header("Content-Type: text/csv");
            header("Content-Transfer-Encoding: binary");
            
            // Read the file
            readfile($filePath);
            $this->lastDone = "<p>Recuerde cambiar los ajustes en Excel para leer archivos separados por ',' y no ';'.</p>";
            exit;
        }else{
            $this->lastDone = "<p>Error al exportar CSV.</p>";

            return;
        }

    }


    public function importarCSV(){

        $this->crearConexion();

        $this->db->select_db($this->currentDB);

       
          
        $fileName = basename('pruebasUsabilidad.csv');
        $filePath = ''.$fileName;
        if(!empty($fileName) && file_exists($filePath)){
            $file = fopen($fileName, "r");
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
            {
                $query = $this->db->prepare('INSERT INTO PruebasUsabilidad(IDRealizador,Nombre_Realizador,Apellidos_Realizador,
                Emai,Telefono,Edad,Sexo,PericiaInformatica,Tiempo,Correctamente,Comentarios,Propuestas,Valoracion) 
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');

                $query->bind_param('isssiisiiissi',$getData[0],$getData[1],$getData[2],$getData[3],$getData[4],$getData[5],$getData[6],$getData[7],$getData[8],
                $getData[9],$getData[10],$getData[11],$getData[12]);
                $result = $query->execute();
                if(isset($result))
                {
                    $this->lastDone = "<p>CSV importado con exito</p>";   
                }
                else {
                    $this->lastDone = "<p>Error al importar CSV.</p>";
                }
            }
        
            fclose($file);  
        }
    }



}




if(!isset($_SESSION['bd'])){
    

    $bd = new BaseDatos();
    $bd->crearConexion();
    $_SESSION['bd'] = $bd;
    
}

if(count($_POST)>0){

   

    $bd = $_SESSION['bd'];


 
 
 
    if(isset($_POST['crearBD'])) $bd->crearBaseDeDatos();
    
    if(isset($_POST['crearTabla']))$bd->crearTabla();
    if(isset($_POST['insert']))$bd->insertarDatos();
    if(isset($_POST['aprobados']))$bd->buscarValoracionesAprobadas();
    if(isset($_POST['increment']))$bd->incrementarNotas();
    if(isset($_POST['borrar']))$bd->borrarTodo();
    if(isset($_POST['informe']))$bd->informe();
    if(isset($_POST['descargar']))$bd->descargarDatosCSV();
    if(isset($_POST['subir']))$bd->importarCSV();
    

    
    $bd->closeConnection();
    $_SESSION['bd'] = $bd;

 
}


echo "<!DOCTYPE HTML>

<html lang='es'>

<head>
    <!-- Datos que describen el documento -->

    <meta charset='UTF-8' />
    <title>Ejercicio 4</title>
    <link rel='stylesheet' type='text/css' href='Ejercicio6.css' />
    

    <meta name='author' content='Martin Beltran' />
    <meta name='description' content='Ejercicio 6' />
    <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
    
    
       
</head>
<body>

    <h1>GESTION DE BASE DE DATOS</h1>
    <form action='#' method='post' name='basedatos'>

        <input type='submit' value='Crear Base de Datos' name='crearBD' />
        <input type='submit'  value='Crear Tabla' name='crearTabla'/>
        <input type='submit' value='Insertar Datos' name='insert' />
        
        <input type='submit' value='Buscar Notas Aprobadas' name='aprobados' />
        <input type='submit' value='Incrementar Notas +1' name='increment' />
        <input type='submit' value='Borrar todos los datos de la tabla' name='borrar' />
        <input type='submit' value='Generar Informe' name='informe' />
        <input type='submit' value='Descargar CSV' name='descargar' />
        <input type='submit' value='Subir CSV' name='subir' />

        
        
    </form>
    <section>
    
    <h2>ESTADO ACTUAL</h2>"; 
        
        
        echo $_SESSION['bd']->getLastDone();
        echo "
        
        


    </section>


</body>

</html>";


?>

    
