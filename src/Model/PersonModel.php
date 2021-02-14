<?php
namespace MargretSchroeder\ContaoStammBundle\Model;

use Contao\Model;
use Contao\Model\Collection;
use Contao\Date;

use Contao\Database\Result;
use Contao\Database;

/**
 * add properties for IDE support
 *
 * @property string $hash
 */
class PersonModel extends Model
{
    protected static $strTable = 'tl_person';
    
 
    
    // if you have logic you need more often, you can implement it here
    public function setHash()
    {
        $this->hash = md5($this->id);
    }
    
    public static function findPublishedAll($intPid=0, array $arrOptions=array()){
        
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        
        $arrValues = array($intPid, $strColumn);
        
        return static::findBy($arrColumns, $arrValues );
        
    }
    
    public static function findpublishedByID( $id, array $arrOptions=array()){
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND ($t.id = $id) ";
        
        $arrValues = array($intPid, $strColumn);
        
        return static::findBy($arrColumns, $arrValues );      
    }
    
    public static function findElter2byIDandElter1($id, $e1, $pub=1){
        
        $antwort=array();
        $db = Database::getInstance();
        
        $objRow = $db->prepare("SELECT DISTINCT A.elter2
                                       FROM tl_person A, tl_person B
                                       WHERE A.id =? 
                                       AND A.elter1=?
                                       AND A.elter2 = B.id
                                       AND B.published =?
                                UNION
                                SELECT DISTINCT A.elter1
                                       FROM tl_person A, tl_person B
                                       WHERE A.id =?   
                                       AND A.elter2=?
                                       AND A.elter1 = B.id
                                       AND B.published =?
                                 ");
        
        $res = $objRow->execute($id,$e1,$pub,$id,$e1,$pub);
        while($res->next()){
            array_push($antwort,$res->elter2,$res->elter1);
        }
        
        $fertig = array_unique($antwort);
        $fertig = array_filter($fertig);
        $ff = $fertig[0];
        return $ff;
        
        
        
    }
    
    public static function findElter2byID($id, $pub=1){
        
        $antwort=array();
        $db = Database::getInstance();
        
        $objRow = $db->prepare("SELECT DISTINCT A.elter2
                                       FROM tl_person A, tl_person B
                                       WHERE  A.elter1=?
                                       AND A.elter2 = B.id
                                       AND B.published =?
                                UNION
                                SELECT DISTINCT A.elter1
                                       FROM tl_person A, tl_person B
                                       WHERE  A.elter2=? 
                                       AND A.elter1 = B.id 
                                       AND B.published =?
                                 ");
        
        $res = $objRow->execute($id,$pub,$id,$pub);
        while($res->next()){
            array_push($antwort,$res->elter2,$res->elter1);
        }
        
        $fertig = array_unique($antwort);
        $fertig = array_filter($fertig);
       
        return $fertig;
        
    }
    
    public static function findChildrenIdbyPid($pid, $pub=1 ){
    
        $antwort=array();
        $db = Database::getInstance();
        
        $objRow = $db->prepare("SELECT id
                                       FROM tl_person
                                       WHERE (elter1=? OR elter2 =? )
                                       AND published =?
                               ");
        
        $res = $objRow->execute($pid,$pid, $pub);
        
        while($res->next()){
            array_push($antwort,$res->id);
        }
        
        return $antwort;
        
    }
    
    
    
    
    public static function findChildrenByID($pid, array $arrOptions=array()){
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND ($t.elter1 = $pid OR $t.elter2=$pid) ";
        
        $arrValues = array($intPid, $strColumn);
        
        return static::findBy($arrColumns, $arrValues );
        
        
    }
    
    public static function findChildrenByPaar($eltern, array $arrOptions=array()){
        
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND 
                         ($t.elter1= $eltern[0] OR $t.elter1=$eltern[1] ) AND ($t.elter2 = $eltern[1] OR $t.elter2=$eltern[0]) ";
        
        
        $arrValues = array($id);
        
        return static::findBy($arrColumns, $arrValues );
        
        
    }
    
   
    
        public static function findEinElternChildrenByElternId($elternid, array $arrOptions=array()){
       
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND
                         ($t.elter1= $elternid OR $t.elter2= $elternid ) AND ($t.elter1 = '' OR $t.elter2='')  ";
        
        
        $arrValues = array($id);
        
        return static::findBy($arrColumns, $arrValues );
        }
        
        
       public static function findEinElternChildrenIdByElternId($elternid, $pub=1 ){
        $antwort=array();
        $db = Database::getInstance();
        
        $objRow = $db->prepare("SELECT DISTINCT A.id
                                       FROM tl_person A, tl_person B
                                       WHERE  A.elter1=?
                                       AND A.published =?  
                                       AND A.elter2 =''   
                                       OR ( A.elter2 = B.id
                                       AND NOT B.published =? )
                                UNION
                                SELECT DISTINCT A.id
                                       FROM tl_person A, tl_person B
                                       WHERE  A.elter2=?
                                       AND A.published=?
                                       AND A.elter1 ='' 
                                       OR  (A.elter1 = B.id
                                       AND NOT B.published =?)
                                 ");
        
        $res = $objRow->execute($elternid,$pub,$pub,$elternid,$pub, $pub);
        while($res->next()){
            array_push($antwort,$res->id);
        }
        
        $fertig = array_unique($antwort);
        $fertig = array_filter($fertig);
        
        
        return $fertig;
    }
    
    
    public static function findEinElternChildrenByElternIdandNOTeltern2($elternid, $notelter2, array $arrOptions=array()){
        
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND
                         ($t.elter1= $elternid OR $t.elter2= $elternid ) AND (($t.elter1 = $notelter2  OR $t.elter2= $notelter2) OR ($t.elter1 = ''  OR $t.elter2= ''))    ";
        
        
        $arrValues = array($id);
        
        return static::findBy($arrColumns, $arrValues );
        
        
    }
    
    
    
    public static function findPartnerByID($pid, array $arrOptions=array()){
        $t= static::$strTable;
        $time = Date::floorToMinute();
        
        $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time') AND $t.partner = $pid ";
        
        $arrValues = array($intPid, $strColumn);
        
        return static::findBy($arrColumns, $arrValues );
        
        
    }
    
    
    
}