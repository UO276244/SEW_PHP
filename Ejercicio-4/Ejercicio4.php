<?php 

session_start();


class SilverApi{




    public function __construct(){


        $this->monedasArray = array('AED' => 'AED - U.A.E Dirham',
                                    'AUD' => 'AUD - Australian Dollard',
                                    'BTC' => 'BTC - Bitcon',
                                    'CAD' => 'CAD - Canadian Dollar',
                                    'CHF' => 'CHF - Franco Sueco',
                                    'CNY' => 'CNY - Yuan Chino',
                                    'CZK' => 'CZK - Corona Checa',
                                    'EGP' => 'EGP - Libra Egipcia',
                                    'EUR' => 'EUR - Euro Europeo',
                                    'GBP' => 'GBP - Libra Britanica',
                                    'INR' => 'INR - Rupia india',
                                    'JPY' => 'JPY - Yen Japonés',
                                    'KWD' => 'KWD - Kawaiti Dinar',
                                    'MYR' => 'MYR - Ringgit Malasio',
                                    'PLN' => 'PLN - Zloty Polaco',
                                    'RUB' => 'RUB - Rublo Ruso',
                                    'SGD' => 'SGD - Dolar Singapur',
                                    'THB' => 'THB - Baht Tailandés',
                                    'USD' => 'USD - Dolar Estadounidense',
                                    'ZAR' => 'ZAR - Rand Sudafricano'
                                    );
    
     $this->apiKey = '4afkt52gjj28c18cf3wm7n254k84iipv0c7d5o1o573f9kagw4686gvgt808';

     $this->selectedVal = 'USD';

     $this->jsonText = '';


     $this->called =false;

     $this->curValuePlata="";//XAG
     $this->curValueOro="";//XAU
     $this->curValuePlatino="";//XPT
     $this->curValueCobre="";//XCU

     $this->error = false;
     $this->errorTxt = '';

    
    }


    public function printResult(){


        if( $this->called){

            $string = "<h2> Precio pedido: </h2>";
            $string .= "<p>Precio de la plata en " . $this->selectedVal .": " .  $this->curValuePlata . "</p>";
            $string .= "<p>Precio del oro en " . $this->selectedVal .": " .  $this->curValueOro . "</p>";
            $string .= "<p>Precio del platino en " . $this->selectedVal .": " .  $this->curValuePlatino . "</p>";
            $string .= "<p>Precio del cobre en " . $this->selectedVal .": " .  $this->curValueCobre . "</p>";
            $string .= "<h2> Json Recibido: </h2> <label for='pantalla'>JSON</label> <br>
            <textarea rows='10' cols='100' name='pantalla' id='pantalla' lang='en' >";
            $string .=  $this->jsonText;
            $string .= "</textarea>";


            return $string;

        }

        if($this->error == true){
            return $this->errorTxt ;
        }

    }

    public function getLimitDate(){
        return "20". date('y-m-d',(strtotime ( '-1 day' , strtotime ( date('y-m-d')) ) ));
    }

    public function createSelect(){

        
        $string = "<select id='select' name='select'>";
        foreach($this->monedasArray as $k => $v) {
            $string .=  "<option value=' ". $k ."'>" . $v . "</option>";
        }
        $string .= "</select>";

        return $string;
    }

    public function llamar($selected_val,$initDate){


       
      $this->selectedVal = $selected_val;
       $access_key = $this->apiKey;

      $url = 'https://metals-api.com/api/'.$initDate.'?access_key='.$access_key.'&base=' . $selected_val ;
     
       $ch = curl_init($url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
       // Store the data:
       $json = curl_exec($ch);
       curl_close($ch);
       
      // Decode JSON response:

       $exchangeRates = json_decode($json,true);

      if($exchangeRates['success']){

        $this->called = true;

        $this->jsonText = $json;
        
        $this->curValuePlata=$exchangeRates['rates']['XAG'];;//XAG
        $this->curValueOro=$exchangeRates['rates']['XAU'];;//XAU
        $this->curValuePlatino=$exchangeRates['rates']['XPT'];//XPT
        $this->curValueCobre=$exchangeRates['rates']['XCU'];//XCU
        

      }else{
        $this->called = false;
        $this->errorTxt = $exchangeRates['error']['info'];
        $this->error =  true;
      }
      
    
       
    }


}

if(!isset($_SESSION['api'])){
    

    $api = new SilverApi();
    $_SESSION['api'] = $api;
    
}




if(count($_POST)>0){

   

    $api = $_SESSION['api'];
 
 
    $selected_val = $_POST['select'];
    if(!isset($_POST['selecFechaInit'])){

        echo"No has seleccionado fecha";

    }else{

        $initDate = $_POST['selecFechaInit'];
   
  
        if(isset($_POST['download'])) $api->llamar($selected_val,$initDate);
  
 
 

    }
    
     $_SESSION['api'] = $api;
 
 }





echo "<!DOCTYPE HTML>
<html lang='es'>
    <head>
        <!-- Datos que describen el documento -->

        <meta charset='UTF-8' />
        <title>Ejercicio 4</title>
        <link rel='stylesheet' type='text/css' href='Ejercicio4.css' />
        
    
        <meta name='author' content='Martin Beltran' />
        <meta name='description' content='Ejercicio 4' />
        <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
        
        
           
    </head>
    <body>


    ";
    
    echo"
        <section>

            <h1>Precio Online de Metales Preciosos</h1>

            <form action='#' method='post' name='descargar'> 

                
                <label for='selecFechaInit'>Fecha del precio</label>";
                
               

                
                echo "<input type='date' id='selecFechaInit'  name='selecFechaInit' step='1' min='2000-01-01' max='"; 
                
                echo $_SESSION['api']->getLimitDate();

                echo "' value = '";
                echo $_SESSION['api']->getLimitDate();
                echo"'/>";


                echo " <label for='select'> Seleccione moneda </label>";
                

                echo $_SESSION['api']->createSelect();


                echo"
               
                
                
                <input type='submit' name='download' value='Pedir Datos'/>

            </form>"; 
            
        echo $_SESSION['api']->printResult();
            
            
            
    echo"
        </section>


    </body>";

?>