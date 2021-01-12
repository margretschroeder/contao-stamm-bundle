<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Margret Schröder
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_wohnung
 */
 
$GLOBALS['TL_DCA']['tl_wohnung'] = array
(

	// Config
	'config' => array
	( 
		'dataContainer'               => 'Table',
		'enableVersioning'            => false,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),
	
	// List
	'list' => array
	(
	    'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('wname'),
			'flag'                    => 11,
			'panelLayout'             => 'filter;sort,search,limit',		
		),
		
		'label'             => array
		(   
		  'fields' => array('wname', 'wgroesse'),  
		  'format' => 'Wohnung: %s, Größe: %s ',
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
				'label' => &$GLOBALS['TL_LANG']['tl_wohnung']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wohnung']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			
			'delete' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_wohnung']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			
			/*'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wohnung']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_wohnung', 'toggleIcon'),
			),*/
			
			'show'   => array
			(
				'label'      => &$GLOBALS['TL_LANG']['tl_wohnung']['show'],
				'href'       => 'act=show',
				'icon'       => 'show.gif',
				'attributes' => 'style="margin-right:3px"'
			),
		)
		
		
		
		
	),
	
	// Palettes
	'palettes' => array
	(
	'__selector__'                => array('login', 'assignDir'),
		'default'                 => '{wohnung_legend},wname; wgroesse , wbewzahl ;{date_legend},date,time; wlage; wstellcar, wstellbike; wbeschreibung; wkuerzel; wprofbild; wbewohnerprofile',
	
	),
	
	// Subpalettes
	'subpalettes' => array
	(),
	
	// Fields
	'fields' => array
	(
		'id' => array
		(
			
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		
		'tstamp' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['tstamp'],
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
	
	
		'date' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['date'],
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
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['time'],
			'default'                 => time(),
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'time', 'doNotCopy'=>true, 'tl_class'=>'w50'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
	
	
	
	
	    'wname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
	  'wgroesse' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wgroesse'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		'wbewzahl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wbewzahl'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		'wstellcar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wstellcar'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		'wstellbike' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wstellbike'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'digit',),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		
		
		'wlage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wlage'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
		'wbeschreibung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wbeschreibung'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		
		'wbewohnerprofile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wbewohnerprofile'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		
		'wkuerzel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wkuerzel'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
			'sql'                     => "text NULL"
		),
		
		'wprofbild' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wohnung']['wprofbild'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
	
	),
	
	);			