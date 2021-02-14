<?php
declare(strict_types=1);

namespace MargretSchroeder\ContaoStammBundle\Library;

class Baum
{
    public const REx = '120';
    public const REy = '160';
    public const ABSTANDX = '200';
    public const LINKS =  self::ABSTANDX * (-1);
    public const RECHTS =  self::ABSTANDX;
    public const YFACTOR =  13 ;
    public const PVERSATZ = 60;
    
    public $personen;
    public $cid;
    public $alt;
    public $baum;
    public $kernbaum;
    public $kernrollen;
    
    function __construct(array $personen, string $cid ) {
        
        $this -> personen = $personen;
        $this -> cid = $cid;
        
        $this-> alt = $this->find_alt();
        $this -> kernbaum = array();
        $this -> kernrollen = array( "ich" , 'vater', 'mutter', 'geschwist', 'partner', 'kind' );
        $this -> baum = $this->create_baum();
        return true;
    }
    
    function create_baum() {
        
        $baum = array();
        
        
        $row = $this->find_row_by_id($this->cid);
        //echo "cid:" . $this->cid;
        //echo "ROW:" . $row;
        
        $ich=$this->addperson($this->personen, $baum, $row, 'ich', '' );   
        $vater = $this->addperson($this->personen, $baum, $row, 'vater',  self::LINKS );
        $mutter = $this->addperson($this->personen, $baum, $row, 'mutter', self::RECHTS );
        $geschwister = $this->finde_add_geschwister( $this->personen, $baum, $row  );
        $partner = $this->finde_add_partner($this->personen, $baum, $this->cid, 2*self::LINKS );
        $kinder  = $this->finde_add_kinder($this->personen, $baum, $this->cid, self::RECHTS );
        
       /*
       
        
        
        $this->kernbaum = $this->make_kern_familie($baum, $this->cid, $this->kernrollen);
        
        $gvater1 = $this->addperson($this->personen, $baum, $ich[vater], 'vater',  LINKS );
        $gmutter1 = $this->addperson($this->personen, $baum, $ich[vater], 'mutter',  RECHTS );
        
        $gvater2 = $this->addperson($this->personen, $baum, $ich[mutter], 'vater',  LINKS );
        $gmutter2 = $this->addperson($this->personen, $baum, $ich[mutter], 'mutter',  RECHTS );
        */
        
        
        $this->make_eltern_path($baum);
        $this->make_partner_path($baum);
        
         
        for($i = 0; $i < count($baum); $i = $i+1  ){
        echo "<p>" ."i=" . $i    .  "<br>";
            var_dump($baum[$i] ); 
        echo "</p>";
        }
        
        
        return $baum;
        
    }
    
    function make_partner_path(&$baum) {
        foreach ( $baum as $index => $person  ){
            if ($person[partner] != '' ){
                
                   $partner1 = $baum[$index];
                   $partner2 = $baum[ $this->find_baum_row_by_id($baum, $partner1[partner])];
                
                   $punkt1 = array(x=> $partner1[x],y=> $partner1[y]);
                   $punkt2 = array(x=>$partner2[x], y=>$partner2[y]);
                   
                   
                   if ( $punkt1[y] >= $punkt2[y]  ) 
                   {
                       $startp= array(x=>($punkt1[x] + self::REx/2 ) , y=>($punkt1[y] + self::REy));
                       $zielp = array(x=>($punkt2[x] + self::REx/2 ) , y=>($punkt2[y] + self::REy));
                   } else {
                       
                       $zielp= array(x=>($punkt1[x] + self::REx/2 ) , y=>($punkt1[y] + self::REy));
                       $startp = array(x=>($punkt2[x] + self::REx/2 ) , y=>($punkt2[y] + self::REy));
                            
                   }
                   $partnerpath = '<path class="elternpath"   
                                    d="M ' .  $startp[x]. ','. $startp[y] . 'v'. self::PVERSATZ/2 .
                                    'H'. $zielp[x] . 'V' . $zielp[y] . '  "        
                                   stroke = "green" stroke-width="7" fill="none"/> ';
                   
                   $baum[$index][partnerpath]= $partnerpath;
                
            }
        
        }
        
        
    }
    
    
    function make_eltern_path(&$baum) {
           $i=0;
        foreach ( $baum as $person  ){
            
            $mutter =array();
            $vater = array();
            if ($person[mutter] != '' and  $mutterid= $this->find_row_by_id($person[mutter]) != ''  )
            {
                $mutter_bid= $this->find_baum_row_by_id($baum , $person[mutter]);
                $mutter =  $baum[$mutter_bid];   
            }
            
            if ($person[vater] != ''  and  ! $vaterid= $this->find_row_by_id($person[vater])   )
            {
                $vater_bid= $this->find_baum_row_by_id($baum , $person[vater]);
                $vater =  $baum[$vater_bid];
            }
            
            if( isset($vater[x]) and isset($mutter[x])  ){
                
                $min = min($vater[x], $mutter[x]);
                $max = MAX($vater[x], $mutter[x]) + self::REx;
                $center = $min + ($max - $min )/2;
                
                $elternpath =' <path class="elternpath" d="M'.   ($mutter[x] + self::REx/2) ." , " .  ($mutter[y] + self::REy) . 'v'. 
                    self::PVERSATZ . 'H'. ($vater[x] + self::REx/2) . 'V'. ($vater[y] + self::REy)  . 
                    'M'. ($person[x] + self::REx/2 ).','. ($person[y]  ). 'V'. ($mutter[y] + self::REy+  self::PVERSATZ ) .
                    'H'. $center  .' "  stroke="black" stroke-width="7" fill="none"/>';
             
                
                $baum[$i][elternpath]= $elternpath;
            }
            
         $i = $i+1;     
        }
       
        return; 
        
    }
    
    function find_baum_row_by_id(&$baum, $id){
        $brow="";
        for($i=0 ; $i < count($baum) ; $i=$i+1   ) {
            if ( $baum[$i][id] == $id ){
                $brow = $i;
            }
        }
       
        return $brow;
    }
    
    
    function find_row_by_id( $id ){
       
        $row ="-1";
        for($i=0 ; $i < count($this->personen) ; $i=$i+1   ) {
            //echo "ID" . $this->personen[$i][id] . "gesucht wird:" . $id;
            if ( $this->personen[$i][id] == $id ){
                   $row = $i; 
            }
        }
        return $row;
        
    }
    
    function find_alt(){
        $jahr = 2050;
        foreach( $this->personen as $p) {
            
            $pjahr = date(Y, strtotime($p[geburt])) ;
            if ( $pjahr <= $jahr ){
                $jahr = $pjahr;
            }   
        }
        $this->alt = $jahr;
        return $jahr;
    }
    
    function find_gjahr_from_row($row) {
        
        //echo "<p>GJAHR:" . $this->personen[$row][geburt] ." " . date(Y,strtotime($this->personen[$row][geburt])) . "</p>";
        $gjahrca = $this->personen[$row][geburtsjahr];
       
        if ($gjahrca == "") {
            //echo "leer";
            $gjahr = date(Y, strtotime($this->personen[$row][geburt]));
        } else {
            $gjahr = $gjahrca;
        }
        //echo $gjahr ;
        return $gjahr;
    }
    
    function find_y_from_row($row){
        $ende =  2050;
        $start =  $this->alt - 29;
        $geburt= $this->find_gjahr_from_row($row);
        $yyy= (($geburt - $start)* self::YFACTOR )  ;
        return $yyy;
        
    }
    
    
    
    
    function make_kern_familie(&$baum, $id, $kernrollen ){
        while(list($name,$val) = each($baum)){
            if  ( in_array($val[rolle] , $kernrollen )) {
                $baum[$name][css] =  $baum[$name][css]  . " kern";
            }
        }
        return $baum ;
    }
    
    
    
    function place_kinder( $kinder, &$baum, $elter1 ){
        
        $elter2 = array();
        
        foreach ($kinder  as $name => $val) {
            array_push($elter2, $val[elter2]);
            
        }
        $e2 = (array_unique($elter2));
        
        foreach ( $e2  as $ename => $eval   ){
            
            $mykinder = array();
            
            $last_key = end(array_keys($kinder));
            
            foreach ($kinder  as $name => $val) {
                if ( $eval == $val[elter2] ) {
                    array_push($mykinder, $val[id]);
                }
            }
            
            $kk = count($mykinder);
            $x1 = $baum[$eval][x];
            $x2 = $baum[$elter1][x];
            $abs = (max($x1,$x2)- min($x1,$x2));
            
            
            switch ($kk){
                case 1:
                    $xwert = min($x1,$x2) + $abs/2 -REX/2;
                    break;
                    
                case 2:
                    $xwert= min($x1,$x2);
                    break;
                    
                case 3:
                    $xwert = min($x1,$x2) + $abs/2 -REX/2 - ABSTANDX;
                    break;
            }
            
            while(list($kname,$kval) = each($mykinder)){
                $baum[$kval][x] = $xwert;
                $xwert = $xwert+ $abs;
            }
        }
        
        
        
        return;
        
    }
    
    
    
    function adjust_x(&$baum, $person, $richtung ){
        
        $x = $person[x];
        
        
        foreach( $baum as $name => $val ){
            if ( $val[x] >= $x and $name != $person[id]  ){
                $baum[$name][x] = $baum[$name][x] + $richtung;
            }
        }
        
        return true;
        
    }
    
    
    function addperson($personen, &$baum, $id  , $rolle , $abst ) {
        
        
        
        if ( count($baum) != 0 ) {
            $x0 = $baum[0][x];
        }	else {
            $x0 = -60;
        }
        
        
        switch ($rolle)  {
            case 'ich':
                $row = $id; 
                $x = $x0;
                $y= $this->find_y_from_row($id);
                $pid= $id; 
                break;
            case 'vater':
                $tmp=$personen[$id][vater];
                $row = $this->find_row_by_id($tmp); 
                $x= ($x0 + $abst);
                $y= $this->find_y_from_row($row);
                $pid = $tmp;
                break;
            case 'mutter':
                $tmp=$personen[$id][mutter];
                $row = $this->find_row_by_id($tmp); 
                $x= ($x0 + $abst );
                $y= $this->find_y_from_row($row);
                $pid = $tmp;
                break;
            default:
                $pid = $id;
                $row =$this->find_row_by_id( $id);
                $x= ($x0 + $abst );
                $y= $this->find_y_from_row($row);
        }
        
        
        
            //echo "Name:" . $name ;
            
            $person = $personen[$row];
            $person['rolle'] =$rolle;
            $person['x']= $x;
            $person['y']= $y;
            $person['pid'] = $pid;
            
            $objFile = \FilesModel::findByUuid($personen[$row][singleSRC]);
            $person['bild'] =  $objFile->path;
            
            //echo "<p>";
            //var_dump($personen[$row] );
            //echo "</p>";
            
            
            //$baum[$name]=$person;
            array_push($baum, $person);
            
            
            
            return $person;
        
    }
    
    function finde_add_kinder($personen, &$baum ,$id , $x){
        
        $kinder=array();
        
  
        foreach($personen as $name => $attr ){
            if ($v=$attr[vater] == $id or $m=$attr[mutter] == $id){
                
                echo "Kind gefunden: $attr[firstname] ";
                
                array_push($kinder, $personen[$this->find_row_by_id($attr[id]) ]  );
                
            }
        }
     
        //echo "<p>";
        //var_dump($kinder );
        //echo "</p>";
        
        
        $kzahl = count($kinder);
        
        foreach($kinder as $row => $attr ){
            
       
            $elter1 = $personen[$this->find_row_by_id($id)];
            
            if ($attr[mutter] =='' or $attr[vater]=='' ){
                $elter2='';
            } else {
                if ( $attr[mutter] == $id  ){
                    $elter2 = $personen[$this->find_row_by_id($attr[vater]) ];
                } else {
                    $elter2 = $personen[$this->find_row_by_id($attr[mutter]) ];
                }
              
               // elter2 muss eventuell noch im Baum untergebracht werden
               
                
                
            }
            
            
        }
        
        
        
        /*
        if ( $x != 'NOADD')  {
            
            foreach( $kinder as $name => $val ) {
                $elter2= $this->addperson($personen, $baum, $val[elter2], 'elter', $x );
                
                if ($elter2 != '' ){
                    $this->adjust_x($baum, $elter2,$x);
                }
                $kind=  $this->addperson($personen, $baum, $val[id], 'kind', $x/2 );
            }
            $this->place_kinder($kinder, $baum, $id);
        }
        
        */
        return $kinder;
    }
    
    
    
    function finde_add_geschwister($personen, &$baum, $id){
        
        $geschwister=array();
        
        //$echo "ID:  $id  ";
        
        foreach($personen as $person ){
            if ($person[vater] == $personen[$id][vater] and  $person[mutter] == $personen[$id][mutter] and $person[id] != $personen[$id][id]   )
                //$geschwister[$attr[id]]= $personen[$attr[id]];
                array_push($geschwister, $person);
        }
        foreach( $geschwister as $person) {
            /*
            echo "<br>";
            
            foreach( $person as $name => $val ){
                echo "Name: $name Val: $val  <br>";
            }
            */
            $geschw=  $this->addperson($personen, $baum, $person[id], 'geschwist', 1* self::RECHTS);
        }
        
        
        /*
        $index=1;
        foreach( $geschwister as $name => $val ) {
            $kk =  $this->finde_add_kinder($this->personen, $baum, $name, NOADD ) ;
            
            if( count($kk) == 0 ){
                $geschw=  $this->addperson($this->personen, $baum, $name, 'geschwist', $index * RECHTS );
                $this->adjust_x($baum, $geschw , RECHTS);arr
                $index++;
            }
        }
        
        foreach( $geschwister as $name => $val ) {
            $kk =  $this->finde_add_kinder($this->personen, $baum, $name, NOADD ) ;
            if( count($kk) != 0 ){
                $geschw=  $this->addperson($this->personen, $baum, $name, 'geschwist', $index * RECHTS );
                $this->adjust_x($baum, $geschw , RECHTS);
                $index++;
            }
        }
        
        */
        return $geschwister;
    }
    
    function finde_add_partner($personen, &$baum , $id, $x ) {
        
        //echo "ID: $id  CID $this->cid";
        $id_db = $id;
        $id_row = $this->find_row_by_id($id);
        
        if ( $personen[$id_row][partner] != "" and  $personen[$personen[$id_row][partner]] != ''){
            $partner1= $personen[$personen[$id][partner]];
        } else {
            foreach( $personen as $name => $wert ){
                if ( $wert[partner] == $id ){
                   $partner1 = $wert[id];
               
                  // echo "Name: $name  Val: $wert[id]  Partner: $partner1";
                  }   
            }
        }
        if ( $partner1 != '')
            $partner =  $this->addperson($this->personen, $baum, $partner1, 'partner', $x );
        
            return $partner;
    }
}
