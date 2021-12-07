<?php 

session_start();
class PilaLIFO {

    protected $pila;
    protected $tamagnoMax; 

    public function __construct(){
        
        $this->pila = array();
        
        $this->size = 0;
    }

    public function push($elemento) {
    
       
        $this->pila[$this->size] = $elemento;
        $this->size += 1;
        
    }

    public function pop() {
         

        if($this->size > 0){
            
            $this->size -= 1;
            return $this->pila[$this->size];
            
        }
            
       
    }
            
    public function getSize(){
          return $this->size;   
    }
    
    
    public function vacia() {
         $this->size = 0;
         $this->pila =  array();

    }
            
    public function ver(){

        $string = "";
        
        for ($x = $this->size - 1; $x >= 0 ; $x--) {
            $string .= "[ ". $x . " - " . $this->pila[$x] . " ]\n" ;
        } 

        return $string;
        
    } 

}


class CalculadoraRPN{

    public function __construct(){

        $this->currentPantalla="";
        $this->pila= new PilaLIFO();

    }


    public function sin(){

        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = sin($num1);

            $this->push($result);
    

        }

    }

    public function cos(){

        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = cos($num1);

            $this->push($result);
    

        }

    }

    public function tan(){

        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = tan($num1);

            $this->push($result);
    

        }


    }


    public function dividir(){

        if($this->pila->getSize() >= 2){

            $num1 = $this->pop();
            $num2 = $this->pop();

            $result = $num1 / $num2;

            $this->push($result);
    

        }
        
    }

    public function arcsin(){


        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = asin($num1);

            $this->push($result);
    

        }



    }

    public function arccos(){

        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = acos($num1);

            $this->push($result);
    

        }


    }

    public function arctan(){

        if($this->pila->getSize() >= 1){

            $num1 = $this->pop();
            

            $result = atan($num1);

            $this->push($result);
    

        }

    }


    public function sumar(){

        if($this->pila->getSize() >= 2){

            $num1 = $this->pop();
            $num2 = $this->pop();

            $result = $num1 + $num2;

            $this->push($result);
    

        }
        
    }

    public function restar(){

        if($this->pila->getSize() >= 2){

            $num1 = $this->pop();
            $num2 = $this->pop();

            $result = $num1 - $num2;

            $this->push($result);
    

        }

    }


    public function mul(){



        if($this->pila->getSize() >= 2){

            $num1 = $this->pop();
            $num2 = $this->pop();

            $result = $num1 * $num2;

            $this->push($result);
    

        }

       
        


    }

    public function digito($dig){

        
        $this->currentPantalla =  $this->currentPantalla . $dig; 

    }

    
    public function printPila(){
        return $this->pila->ver();
    }

    public function getPantalla(){
        return $this->currentPantalla;
    }


    public function cambiarSigno(){

        $num = $this->pop();
        
        $num = $num * (-1);

        $this->push($num);

    }

    private function pop(){
        return $this->pila->pop();
    }


    private function push($value){

        try{
            
            $value = floatval($value);
            $this->pila->push($value);
        }catch(Excepcion | Error ){

            $this->currentPantalla='Error de calculo';

        }

        

    }


    public function enter(){

        $num = $this->currentPantalla;
        $this->push($num);
        $this->currentPantalla='';


    }


    public function limpiar(){

        $this->currentPantalla='';
        $this->pila->vacia();

    }




    

}


if(!isset($_SESSION['calculadoraRPN'])){
    

    $calculadorarpn = new CalculadoraRPN();
    $_SESSION['calculadoraRPN'] = $calculadorarpn;
}




if(count($_POST)>0){

   

   $calc = $_SESSION['calculadoraRPN'];


   

    
    if(isset($_POST['division'])) $calc->dividir();


    
    if(isset($_POST['sin'])) $calc->sin();
    if(isset($_POST['cos'])) $calc->cos();
    if(isset($_POST['tan'])) $calc->tan();

    if(isset($_POST['arcsin'])) $calc->arcsin();
    if(isset($_POST['arccos'])) $calc->arccos();
    if(isset($_POST['arctan'])) $calc->arctan();


    if(isset($_POST['7'])) $calc->digito('7');
    if(isset($_POST['8'])) $calc->digito('8');
    if(isset($_POST['9'])) $calc->digito('9');
    if(isset($_POST['multiplicacion'])) $calc->mul();

    if(isset($_POST['4'])) $calc->digito('4');
    if(isset($_POST['5'])) $calc->digito('5');
    if(isset($_POST['6'])) $calc->digito('6');
    if(isset($_POST['resta'])) $calc->resta();

    if(isset($_POST['1'])) $calc->digito('1');
    if(isset($_POST['2'])) $calc->digito('2');
    if(isset($_POST['3'])) $calc->digito('3');
    if(isset($_POST['suma'])) $calc->sumar();

    if(isset($_POST['0'])) $calc->digito('0');
    if(isset($_POST['punto'])) $calc->digito('.');
    if(isset($_POST['vaciar'])) $calc->limpiar();
    if(isset($_POST['enter'])) $calc->enter();

    if(isset($_POST['changeSign'])) $calc->cambiarSigno();


    
    

    $_SESSION['calculadoraRPN'] = $calc;

}





echo "<!DOCTYPE HTML>
<html lang='es'>
    <head>
        <!-- Datos que describen el documento -->

        <meta charset='UTF-8' />
        <title>Calculadora RPN</title>
        <link rel='stylesheet' type='text/css' href='CalculadoraRPN.css' />
        
    
        <meta name='author' content='Martin Beltran' />
        <meta name='description' content='Calculadora RPN' />
        <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
        
        
           
    </head>
    <body>
        <h1>Calculadora RPN</h1>
        

            <form action='#' method='post' name='Calculadora'>

                <label for='pantalla'>Pila</label>
                <textarea rows='10'  name='pantalla' id='pantalla' lang='es' disabled>" ;
                 
                echo $_SESSION['calculadoraRPN']->printPila();
                
                echo"
                </textarea>
               
                <label for='currentnum'>Numbero Actual</label>
                <input type='text' name='currentnum' id='currentnum' value='"; 
                
                
                echo $_SESSION['calculadoraRPN']->getPantalla();
                
                echo"' lang='es' disabled>
                
                <input type='submit' value='sin' name='sin' />
                <input type='submit' value='cos' name='cos' />
                <input type='submit' value='tan' name='tan' />
                <input type='submit' value='/' name='division' />
    
                <input  type='submit' value='arcsin' name='arcsin' />
                <input type='submit' value='arccos' name='arccos' />
                <input  type='submit' value='arctan' name='arctan' />
                <input type='submit' value='+' name='suma' />
    
    
                <input type='submit' value='1' name='1' />
                <input type='submit' value='2' name='2' />
                <input type='submit' value='3' name='3' />
                
                <input type='submit' value='-' name='resta' />
    
                <input type='submit' value='4' name='4' />
                <input type='submit' value='5' name='5' />
                <input type='submit' value='6' name='6' />
               
                <input type='submit' value='*' name='multiplicacion' />
    
               
                <input type='submit' value='7' name='7' />
                <input type='submit' value='8' name='8' />
                <input type='submit' value='9' name='9' />
                <input type='submit' value='0' name='0' />
               
                <input type='submit' value='+-' name='changeSign' />
                <input type='submit' value='ENTER' name='enter' />
                <input type='submit' value='.' name='punto'/>
                <input type='submit' value='C' name='vaciar' />
    
                
            </form>

            

          


        

    </body>
</html>";


?>