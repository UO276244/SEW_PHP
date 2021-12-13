<?php 

session_start();

class CalculadoraBasica{

    protected $currentPantalla;
    protected $memoria;

    public function __construct(){
        $this->currentPantalla="";
        $this->memoria="";
    }


    public function getPantalla(){
        return $this->currentPantalla;
    }


    public function digito($dig){
        $this->currentPantalla =  $this->currentPantalla .$dig;

    }

    public function operacion($operador){
        $this->currentPantalla =  $this->currentPantalla .$operador;
        
    }

    public function igual(){

        try {
            
            $res=eval("return $this->currentPantalla ;"); 
            $this->currentPantalla = $res;
        }
        catch (Error $e) {
            $this->currentPantalla = "Syntax Error";
        }  
        catch(Execepcion $e){
            $this->currentPantalla = "Syntax Error";
        }
        

    }

    public function limpiar(){
        $this->currentPantalla = '';
    }


    public function mrc(){
        $this->memoria = $this->currentPantalla;
    }

    public function mMenos(){
        $this->currentPantalla .= '-(' .$this->memoria .")";

    }

    public funCtion mMas(){

        $this->currentPantalla .= '+' .$this->memoria .")";

    }

   


    public function getMemoria(){
        return $this->memoria;
    }

}


if(!isset($_SESSION['calculadora'])){
    
    $calculadoraBasica = new CalculadoraBasica();
    $_SESSION['calculadora'] = $calculadoraBasica;
}




if(count($_POST)>0){



   $calc = $_SESSION['calculadora'];

    if(isset($_POST['mrc'])) $calc->mrc();
    if(isset($_POST['m-'])) $calc->mMenos();
    if(isset($_POST['m+'])) $calc->mMas();
    if(isset($_POST['/'])) $calc->operacion('/');

    if(isset($_POST['7'])) $calc->digito('7');
    if(isset($_POST['8'])) $calc->digito('8');
    if(isset($_POST['9'])) $calc->digito('9');
    if(isset($_POST['*'])) $calc->operacion('*');

    if(isset($_POST['4'])) $calc->digito('4');
    if(isset($_POST['5'])) $calc->digito('5');
    if(isset($_POST['6'])) $calc->digito('6');
    if(isset($_POST['-'])) $calc->operacion('-');

    if(isset($_POST['1'])) $calc->digito('1');
    if(isset($_POST['2'])) $calc->digito('2');
    if(isset($_POST['3'])) $calc->digito('3');
    if(isset($_POST['+'])) $calc->operacion('+');

    if(isset($_POST['0'])) $calc->digito('0');
    if(isset($_POST['punto'])) $calc->digito('.');
    if(isset($_POST['C'])) $calc->limpiar();
    if(isset($_POST['='])) $calc->igual();


    
    

    $_SESSION['calculadora'] = $calc;

}



echo "
<!DOCTYPE HTML>
<html lang='es'>
    <head>
        <!-- Datos que describen el documento -->

        <meta charset='UTF-8' />
        <title>Calculadora Basica</title>
        <link rel='stylesheet' type='text/css' href='CalculadoraBasica.css' />
        
    
        <meta name='author' content='Martin Beltran' />
        <meta name='description' content='Calculadora Basica' />
        <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
       
           
    </head>
    <body>
        <h1>Calculadora Basica</h1>
        <form action='#' method='post' name='Calculadora'>

            <label for='pantalla'>Resultado</label>
            <input type='text' name='pantalla' id='pantalla' value='"; 


            echo $_SESSION['calculadora']->getPantalla();
            
            
            
            echo"' lang='es' disabled>
            <!--Primera fila de botones-->
            <input type='submit' value='mrc' name='mrc' />
            <input type='submit' value='m-' name='m-' />
            <input type='submit' value='m+'  name='m+'/>
            <input type='submit' value='/'  name='/'/>
<!--Segunda fila de botones-->
            <input type='submit' value='7'  name='7'/>
            <input type='submit' value='8'  name='8'/>
            <input type='submit' value='9'  name='9'/>
            <input type='submit' value='*'  name='*'/>
<!--Tercera fila de botones-->
            <input type='submit' value='4'  name='4'/>
            <input type='submit' value='5' name='5' />
            <input type='submit' value='6'  name='6'/>
            <input type='submit' value='-' name='-' />
<!--Cuarta fila de botones-->
            <input type='submit' value='1' name='1'/>
            <input type='submit' value='2'  name='2'/>
            <input type='submit' value='3' name='3' />
            <input type='submit' value='+'  name='+'/>
<!--Quinta fila de botones-->
            <input type='submit' value='0'  name='0'/>
            <input type='submit' value='.' name='punto'/>
            <input type='submit' value='C' name='C'/>
            <input type='submit' value='='  name='='/>


            
        </form>

    </body>
</html>";

?>