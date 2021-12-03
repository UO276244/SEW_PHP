<?php 

session_start();

class CalculadoraBasica{

    protected $currentPantalla;
    protected $memoria;

    public function __construct(){
        $this->currentPantalla='';
        $this->memoria='';
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
            
            $res=eval('return ' . $this->currentPantalla . ' ;'); 
            $this->currentPantalla = $res;
            
            return $res;
        }
        catch (Error $e) {
            $this->currentPantalla = 'Syntax Error';
            
        }  
        catch(Execepcion $e){
            $this->currentPantalla = 'Syntax Error';
        }
        

    }

    public function limpiar(){
        $this->currentPantalla = '';
    }


    public function mrc(){
        $this->memoria = $this->currentPantalla;
    }

    public function mMenos(){
        $this->currentPantalla .= '-(' .$this->memoria .')';

    }

    public funCtion mMas(){

        $this->currentPantalla .= '+(' .$this->memoria . ')';

    }

   


    public function getMemoria(){
        return $this->memoria;
    }

}



class CalculadoraCientifica extends CalculadoraBasica{

    public function __construct(){
        parent::__construct();
        $this->isDeg = false;
    }


    public function mc(){
        $this->memoria='';
    }

    public function mr(){
        $this->currentPantalla .= $this->memoria;
    }

    public function deg(){


        $num = $this->igual();

        if($this->isDeg == true){

            $this->currentPantalla  .= $num *( M_PI/180);
            $this->isDeg = false;

        }else{
            
            $this->currentPantalla  .= $num *(180/M_PI);
            $this->isDeg = true;
        }

    }

    public function pow2(){
        $this->currentPantalla .= '**2';

    }

    public function pow(){

        $this->currentPantalla .= '**';

    }

    public function sin(){

        $this->currentPantalla .= 'sin(';

    }

    public function cos(){
        $this->currentPantalla .= 'cos(';
    }

    public function tan(){
        $this->currentPantalla .= 'tan(';
    }

    public function sqrt(){
        $this->currentPantalla .= 'sqrt(';

    }

    public function pow10(){

        $this->currentPantalla .= '10**';

    }

    public function log10(){

        $this->currentPantalla .= 'log10(';

    }

    public function exp(){
        $this->currentPantalla .= 'exp(';
    }

    public function abs(){
        $this->currentPantalla .= 'abs(';
    }

    public function ln(){

        $this->currentPantalla .= 'log(';

    }

    public function e(){
        $this->currentPantalla .= 'M_E';
    }

    public function borrar(){
        $this->currentPantalla = substr($this->currentPantalla, 0, -1);
    }

    public function pi(){
        $this->currentPantalla .= "M_PI";
    }


    public function factorial(){

         $aux = 0;
            try {
                $aux = $this->igual();
                $total = 1; 
            
                for ($x = 1; $x <= $aux; $x++) {
                    $total = $total * $x; 
                } 
                
                $this->currentPantalla = $total;
            }
            catch (Error $e) {
                $this->currentPantalla = 'Syntax Error';
            }  
            catch(Execepcion $e){
                $this->currentPantalla = 'Syntax Error';
            }  

    }

    public function cambiarSigno(){
        try{

            $num = $this->igual();
            $num = $num * (-1);
            $this->currentPantalla = $num;

        }catch(Error $e){ 
            $this->currentPantalla = 'Syntax Error';
            

        }catch(Exception $e){ 
            $this->currentPantalla = 'Syntax Error';
            

        }
    }


    public function parentesisFinal(){
        $this->currentPantalla .= ')';
    }

    public function parentesisInicio(){
        $this->currentPantalla .= '(';
    }
}


if(!isset($_SESSION['calculadora'])){
    
    $calculadoraCientifica = new CalculadoraCientifica();
    $_SESSION['calculadora'] = $calculadoraCientifica;
}




if(count($_POST)>0){



   $calc = $_SESSION['calculadora'];


   if(isset($_POST['DEG'])) $calc->deg();

    if(isset($_POST['MC'])) $calc->mc();
    if(isset($_POST['M-'])) $calc->mMenos();
    if(isset($_POST['M+'])) $calc->mMas();
    if(isset($_POST['MR'])) $calc->mr();
    if(isset($_POST['MS'])) $calc->mrc();
    if(isset($_POST['/'])) $calc->operacion('/');


    if(isset($_POST['x^2'])) $calc->pow2();
    if(isset($_POST['x^y'])) $calc->pow();
    if(isset($_POST['sin'])) $calc->sin();
    if(isset($_POST['cos'])) $calc->cos();
    if(isset($_POST['tan'])) $calc->tan();


    if(isset($_POST['√'])) $calc->sqrt();
    if(isset($_POST['10^x'])) $calc->pow10();
    if(isset($_POST['log10'])) $calc->log10();
    if(isset($_POST['exp'])) $calc->exp();
    if(isset($_POST['abs'])) $calc->abs();

    if(isset($_POST['ln'])) $calc->ln();
    if(isset($_POST['e'])) $calc->e();
    if(isset($_POST['←'])) $calc->borrar();
    if(isset($_POST['π'])) $calc->pi();
    if(isset($_POST['n!'])) $calc->factorial();

    if(isset($_POST['+-'])) $calc->cambiarSigno();
    if(isset($_POST[')'])) $calc->parentesisFinal();
    if(isset($_POST['('])) $calc->parentesisInicio();

    if(isset($_POST['7'])) $calc->digito('7');
    if(isset($_POST['8'])) $calc->digito('8');
    if(isset($_POST['9'])) $calc->digito('9');
    if(isset($_POST['x'])) $calc->operacion('*');

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



echo "<!DOCTYPE HTML>
<html lang='es'>
    <head>
        <!-- Datos que describen el documento -->

        <meta charset='UTF-8' />
        <title>Calculadora Cientifica</title>
        <link rel='stylesheet' type='text/css' href='CalculadoraCientifica.css' />
        
    
        <meta name='author' content='Martin Beltran' />
        <meta name='description' content='Calculadora Cientifica' />
        <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
        
           
    </head>
    <body>
        <h1>Calculadora Cientifica</h1>
        <form action='#' method='post' name='Calculadora'>

            
            <label for='pantalla'>Resultado</label>
            <input type='text' name='pantalla' id='pantalla' value='"; 
            
            
        echo $_SESSION['calculadora']->getPantalla();



            echo "' lang='es' disabled>
           
                <input type='submit'  value='DEG' name='DEG' />
                
                <input type='submit'  value='HYD'  name='HYD'/>
                <input type='submit'  value='F-E' name='F-E' />
                
                <input type ='submit' value = 'inv' disabled/>
                <input type ='submit' value = 'inv' disabled/>
           
           
            <input type='submit' value='MC' name='MC'/>
            <input type='submit' value='MR' name='MR'/>
            <input type='submit' value='M+'  name='M+'/>
            <input type='submit' value='M-'  name='M-'/>
            <input type='submit' value='MS'  name='MS'/>
        


            <input type='submit' value='x^2'  name='x^2'/>
            <input type='submit' value='x^y' name='x^y'/>
            <input type='submit' value='sin'  name='sin'/>
            <input type='submit' value='cos'  name='cos'/>
            <input type='submit' value='tan'  name='tan'/>

           

            <input type='submit' value='√'  name='√'/>
            <input type='submit' value='10^x'  name='10^x'/>
            <input type='submit' value='log 10'  name='log10'/>
            <input type='submit' value='exp'  name='exp'/>
            <input type='submit' value='|x|'  name='abs'/>

            

            <input type='submit' value='ln'  name='ln'/>
            <input type='submit' value='e'  name='e'/>
            <input type='submit' value='C' name='C'/>
            <input type='submit' value='←'  name='←'/>
            <input type='submit' value='/'  name='/'/>

            

            <input type='submit' value='π'  name='π'/>
            <input type='submit' value='7'  name='7'/>
            <input type='submit' value='8'  name='8'/>
            <input type='submit' value='9'  name='9'/>
            <input type='submit' value='x'  name='x'/>

           

            <input type='submit' value='n!'  name='n!'/>
            <input type='submit' value='4'  name='4'/>
            <input type='submit' value='5'  name='5'/>
            <input type='submit' value='6' name='6'/>
            <input type='submit' value='-'  name='-'/>

          

            <input type='submit' value='+-'  name='+-'/>
            <input type='submit' value='1' name='1' />
            <input type='submit' value='2' name='2'/>
            <input type='submit' value='3'  name='3'/>
            <input type='submit' value='+'  name='+'/>

            <input type='submit' value='('  name='('/>
            <input type='submit' value=')'  name=')'/>
            <input type='submit' value='0'  name='0'/>
            <input type='submit' value='.'  name='punto'/>
            <input type='submit' value='='  name='='/>

        </form>

    </body>
</html>";

?>