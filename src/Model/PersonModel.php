<?php
namespace MargretSchroeder\ContaoStammBundle\Model;

use Contao\Model;

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
}