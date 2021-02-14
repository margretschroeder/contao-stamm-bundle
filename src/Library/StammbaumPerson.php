<?php
declare(strict_types=1);

namespace MargretSchroeder\ContaoStammBundle\Library;

class StammbaumPerson extends Baum
{
    //public $personen;
    public $id;
    public $vorname;
    public $nachname;
    public $geburt;
    public $geburtca;
    public $gjahr;
    public $tod;
    public $bild;
    public $zeile1;
    public $zeile2;
    public $headline;
    public $jung;
    //public $alt;
    public $scala;
    public $x;
    public $y;
    //public $yfactor;
    public $vater;
    public $mutter;
    public $partner;
    public $eltern2;
    public $article;
    public $erfolg;
    public $kern_x;
    public $kern_y;
    public $kern_radius;
    
    
    function __construct (array $personen,  string $cid , string $id ) {
         
        
        //$this->personen = $personen;
        parent::__construct($personen, $cid);
        
        $this -> yfactor = $yfactor;
        $this -> id = $id;
        $found = FALSE;
   
        
       
        
        foreach( $this->personen as $p) {
            
            if( $p[id]  == $this->id ){
                $found = TRUE;
                $this-> vorname = $p[firstname];
                $this-> nachname = $p[lastname];
                $this-> geburt = $p[geburt];
                $this-> geburtca = $p[geburtsjahr];
                $this -> article = $p[article];
                $this-> vater = $p[vater];
                $this-> mutter = $p[mutter];
                $this-> partner = $p[partner];
                $this-> eltern2 = $p[eltern2];
                $this-> gjahr = $this->find_gjahr();
                $this-> tod = $p[tod];
                $this-> bild = $p[bild];
             //   $this-> alt = $this->find_alt();
                $this-> zeile1 = $this->zeile1();
                $this-> zeile2 = $this->zeile2();
                $this->headline = $this->headline();
                $this-> x = $p[x];
                //$this-> y = $this->find_y();
                $this->kern_x = 0 ;
                $this->kern_y = 0 ;
               // $this->kern_radius = $this->kreis($this->kernbaum);              
            }
        }
        
        
        
        $this->scala = $this->find_jung() - $this->find_alt();
        $this->erfolg = $found;
        
        //echo "gefunden?" . $found . "<br>";
        return $found;
    }  
   /* 
    function find_alt(){
        $jahr = 2050;
        foreach( $this->personen as $p) {
            
            $pjahr = date(Y, strtotime($p[geburt])) ;
            if ( $pjahr <= $jahr ){
                $jahr = $pjahr;
            }
            
        }
        //echo $jahr;
        $this->alt = $jahr;
        return $jahr;
    }
    */
    function find_jung(){
        
        $jahr1 = 1800;
        foreach( $this->personen as $p) {
            
            $pjahr = date(Y, strtotime($p[geburt])) ;
            if ( $pjahr >= $jahr1 ){
                $jahr1 = $pjahr;
            }
            
        }
        $this->jung = $jahr1;
        //echo $jahr1;
        return $jahr1;
    }
    
    function find_gjahr() {
        $gjahrca = $this->geburtca;
        if ($gjahrca == "") {
            //echo "leer";
            $gjahr = date(Y, strtotime($this->geburt));
        } else {
            $gjahr = $gjahrca;
        }
        
        //echo $gjahr ;
        return $gjahr;
        
    }
    
    function find_gjahr_from_id($id) {
        $gjahrca = $this->baum[$id][geburtca];
        if ($gjahrca == "") {
            //echo "leer";
            $gjahr = date(Y, strtotime($this->baum[$id][geburt]));
        } else {
            $gjahr = $gjahrca;
        }
        
        //echo $gjahr ;
        return $gjahr;
        
        
    }
    
    
    
    
    function zeile1() {
        
        return $this->vorname . " " . $this->nachname ;
        
    }
    
    function zeile2() {
        
        //var_dump($this->personen[9]);
        
        $gjahrca = $this->geburtca;
        if ($gjahrca == "") {
            //echo "leer";
            $gjahr = date(Y, strtotime($this->geburt));
        } else {
            $gjahr = $gjahrca;
        }
        
        $this-> gjahr = $gjahr;
        
        if ($this->tod == "" ){
            $zeile2 = "geb." . $gjahr;
        } else {
            $zeile2 = $gjahr . "-" . date(Y, strtotime($this->tod));
        }
        
        
        return $zeile2;
    }
    
    function headline() {
        
        if ($gjahrca == "") {
            
            $geburt = "geboren am " . $this->geburt ;
        } else {
            $geburt  = "geb. ca:  " . $gjahrca;
        }
        if ($this->tod == "" ){
            $tod = "";
        } else {
            $tod = "gestorben am" . $this->tod;
        }
        
        $headline = $this->vorname . " " . $this-> nachname . " ( " . $geburt . " " . $tod . ")";
        return $headline;
        
        
    }
    
    /*
    function find_y(){
        
        $ende =  2050;
        $start =  $this->alt - 29;
        $geburt = $this->gjahr;
        
        $yyy= (($geburt - $start)* $this -> yfactor )  ;
        
        
        //echo "Geburt: " . $geburt . " start: " . $start . " y: " .  $yyy ."\n";
        
        return $yyy;
    }
    
    function find_y_from_id($id){
        $ende =  2050;
        $start =  $this->alt - 29;
        $geburt= $this->find_gjahr_from_id($id);
        $yyy= (($geburt - $start)* $this -> yfactor )  ;
        return $yyy;
        
    }
    */
    /*
    function kreis ($teilfamilie) {
        
        $summex = 100;
        $summey = 0;
        $radius = 0 ;
        //var_dump ($teilfamilie);
        
        while(list($name,$val) = each($teilfamilie)){
            $summex = $summex + $val[x];
            $summey = $summey + $this->find_y_from_id($name)   ;
            $summex = $summex + $val[x] + REx;
            $summey = $summey + $this->find_y_from_id($name)   ;
            $summex = $summex + $val[x];
            $summey = $summey + $this->find_y_from_id($name) + REy   ;
            $summex = $summex + $val[x] + REx;
            $summey = $summey + $this->find_y_from_id($name) +REy   ;
        }
        $this->kern_x = $summex /(4*count($teilfamilie));
        $this->kern_y = $summey /(4*count($teilfamilie));
        
        reset($teilfamilie);
        while(list($name,$val) = each($teilfamilie)){
            $a = max( $val[x] +REx , $this->kern_x ) - min( $val[x] , $this->kern_x ) ;
            $b = max( $this->find_y_from_id($name)+REy , $this->kern_y ) - min( $this->find_y_from_id($name) , $this->kern_y ) ;
            $radtmp = sqrt(pow($a,2)+ pow($b,2));
            if ($radtmp > $radius)
                $radius = $radtmp;
        }
        
        return $radius;
        
    }
    */
}
?>