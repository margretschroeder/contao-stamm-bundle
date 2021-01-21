<?php


/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2021 Margret Schröder
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_person
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Config;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Contao\DataContainer;
use Contao\FrontendUser;
use Contao\Image;
use Contao\Input;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;
use Contao\System;
use Contao\Versions;

use MargretSchroeder\ContaoStammBundle\Model\PersonModel;


$GLOBALS['TL_DCA']['tl_person'] = array
(
// Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        /*
        'onsubmit_callback' => array
        (
            array('tl_person', 'storeDateAdded')
        ),
        */
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),
    
    
    'list' => array(
        
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('lastname'),
            'flag'                    => 11,
            'panelLayout'             => 'filter;sort,search,limit',
        ),
        
        'label'             => array
        (
            'fields' => array('id','lastname', 'firstname'),
            'format' => 'PNr.: %s : %s  %s ',
        ),
        
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        
        'operations'        => array
        (
            
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_person']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_person']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_person']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            
            'toggle' => array
            (
                'icon'                => 'visible.svg',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_person', 'toggleIcon'),
                'showInHeader'        => true
            ),
            
            'show'   => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_person']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ),
            
            
          ),
       
        ),
     
    'fields' => array
    (
       
        'id' => array
        (     
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        
        
        'tstamp' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['tstamp'],
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        
        
        'date' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['date'],
            'default'                 => time(),
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 8,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        
        'time' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['time'],
            'default'                 => time(),
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        
        'published' => array
        (
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true),
            'sql'                     => "char(1) NOT NULL default ''"
        ),
        
        'start' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        
        'stop' => array
        (
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                     => "varchar(10) NOT NULL default ''"
        ),
        
        'lastname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['lastname'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        
        'firstname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['firstname'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        'geburtsname' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['geburtsname'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        
         
         'geburt' => array
        (
            'exclude'                 => true,
			'inputType'               => 'text',
		//	'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50 wizard'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "date NOT NULL "
        ),
      
     'geburtsjahr' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['geburtsjahr'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
            'sql'                     => "int(10) unsigned"
        ),
            
     
        'tod' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['tod'],
            'default'                 => date('y-m-d',mktime(0, 0, 0, 7, 1, 2000)),
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 8,
            'inputType'               => 'text',
            //'eval'                    => array('rgxp'=>'date', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "date"
        ),
          
        'todesjahr' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['todesjahr'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
            'sql'                     => "int(10) unsigned"
        ), 
   
         'mutter' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['mutter'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_person.CONCAT(lastname,  "," , firstname)',
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
         ), 
        
         'vater' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['vater'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_person.CONCAT(lastname,  "," , firstname)',
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        
        'partner' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['partner'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_person.CONCAT(lastname,  "," , firstname)',
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        
        'mutter2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['mutter2'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_person.CONCAT(lastname,  "," , firstname)',
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        
        'vater2' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_person']['vater2'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_person.CONCAT(lastname,  "," , firstname)',
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
       
        
        'singleSRC' => array
        (
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr'),
            /*
            'load_callback' => array
            (
                array('tl_content', 'setSingleSrcFlags')
            ),
            */
            'sql'                     => "binary(16) NULL"
        ),
        
        
        
        
        
        
      ),
    
    'palettes' => array(
        
        'default'                     => '{title_legend},lastname, firstname; geburtsname, singleSRC ; {daten_legend},geburt, geburtsjahr; tod, todesjahr;{beziehung_legend},mutter, vater;partner ;{besbeziehung_legend:hide},mutter2, vater2 ; {publish_legend},published,start,stop',
    )
        
  
   
    
);    
    
class tl_person extends Backend
{
    public function __construct()
    {
        parent::__construct();
        $this->import(BackendUser::class, 'User');
    }
    
    
    public function storeDateAdded($dc)
    {
        // Front end call
        if (!$dc instanceof DataContainer)
        {
            return;
        }
        
        // Return if there is no active record (override all)
        if (!$dc->activeRecord || $dc->activeRecord->dateAdded > 0)
        {
            return;
        }
        
        // Fallback solution for existing accounts
        if ($dc->activeRecord->lastLogin > 0)
        {
            $time = $dc->activeRecord->lastLogin;
        }
        else
        {
            $time = time();
        }
        
        $this->Database->prepare("UPDATE tl_person SET dateAdded=? WHERE id=?")
        ->execute($time, $dc->id);
    }
    
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (Input::get('tid'))
        {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }
        
        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_person::published', 'alexf'))
        {
            return '';
        }
        
        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);
        
        if (!$row['published'])
        {
            $icon = 'invisible.svg';
        }
        
        if (!$this->User->hasAccess($row['type'], 'alpty') || ($objPerson= PersonModel::findById($row['id'])) === null || !$this->User->isAllowed(BackendUser::CAN_EDIT_PAGE, $objPerson->row()))
        {
            return Image::getHtml($icon) . ' ';
        }
        
        return '<a href="' . $this->addToUrl($href) . '" title="' . StringUtil::specialchars($title) . '"' . $attributes . '>' . Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }
    
   
    
    /**
     * Disable/enable a user group
     *
     * @param integer       $intId
     * @param boolean       $blnVisible
     * @param DataContainer $dc
     *
     * @throws AccessDeniedException
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');
        
        if ($dc)
        {
            $dc->id = $intId; // see #8043
        }
        
        // Trigger the onload_callback
        if (is_array($GLOBALS['TL_DCA']['tl_person']['config']['onload_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_person']['config']['onload_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }
        
        // Check the field access
        if (!$this->User->hasAccess('tl_person::published', 'alexf'))
        {
            throw new AccessDeniedException('Not enough permissions to publish/unpublish person ID ' . $intId . '.');
        }
        
        $objRow = $this->Database->prepare("SELECT * FROM tl_person WHERE id=?")
        ->limit(1)
        ->execute($intId);
        
        if ($objRow->numRows < 1)
        {
            throw new AccessDeniedException('Invalid person ID ' . $intId . '.');
        }
        
        // Set the current record
        if ($dc)
        {
            $dc->activeRecord = $objRow;
        }
        
        $objVersions = new Versions('tl_person', $intId);
        $objVersions->initialize();
        
        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_person']['fields']['published']['save_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_person']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, $dc);
                }
                elseif (is_callable($callback))
                {
                    $blnVisible = $callback($blnVisible, $dc);
                }
            }
        }
        
        $time = time();
        
        // Update the database
        $this->Database->prepare("UPDATE tl_person SET tstamp=$time, published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
        ->execute($intId);
        
        if ($dc)
        {
            $dc->activeRecord->tstamp = $time;
            $dc->activeRecord->published = ($blnVisible ? '1' : '');
        }
        
        // Trigger the onsubmit_callback
        if (is_array($GLOBALS['TL_DCA']['tl_person']['config']['onsubmit_callback']))
        {
            foreach ($GLOBALS['TL_DCA']['tl_person']['config']['onsubmit_callback'] as $callback)
            {
                if (is_array($callback))
                {
                    $this->import($callback[0]);
                    $this->{$callback[0]}->{$callback[1]}($dc);
                }
                elseif (is_callable($callback))
                {
                    $callback($dc);
                }
            }
        }
        
        $objVersions->create();
        
        // The onsubmit_callback has triggered scheduleUpdate(), so run generateSitemap() now
        $this->generateSitemap();
        
        if ($dc)
        {
            $dc->invalidateCacheTags();
        }
    }
    
}





    
?>