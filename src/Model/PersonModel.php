<?php
namespace MargretSchroeder\ContaoStammBundle\Model;

use Contao\Model;
use Contao\Model\Collection;
use Contao\Date;

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
    
}