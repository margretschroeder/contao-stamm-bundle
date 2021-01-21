<?php
    
  namespace MargretSchroeder\ContaoStammBundle\Module;  
  //use MargretSchroeder\ContaoStammBundle\Library\MessageGenerator;
  
   class ListStammModule extends \Module
    {
    /**
     * @var string
     */
    protected $strTemplate = 'mod_listStamm';

    /**
     * Displays a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $template = new \BackendTemplate('be_wildcard');

            $template->wildcard = '### '.utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['listStamm'][0]).' ###';
            $template->title = $this->headline;
            $template->id = $this->id;
            $template->link = $this->name;
            $template->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $template->parse();
        }

        return parent::generate();
    }

    /**
     * Generates the module.
     */
    protected function compile()
    {
        
        //$alle = \MargretSchroeder\ContaoStammBundle\Model\PersonModel::findall();
        $alle = \MargretSchroeder\ContaoStammBundle\Model\PersonModel::findPublishedAll();
        $zeilen = $alle->count();
         
        $personen = $alle->fetchAll();
       

        foreach( $personen as $key => $val ){
            $objFile = \FilesModel::findByUuid($val[singleSRC]); 
            $personen[$key]['bild'] = $objFile->path;
        } 
        
        $pp = \MargretSchroeder\ContaoStammBundle\Model\PersonModel::findByIdOrAlias(6);
        $objFile = \FilesModel::findByUuid($pp->singleSRC); 
        $bild=  $objFile->path;
        $pp->bild =$bild;
        
		
		$this->Template->Personen = $personen;
        
    }
} 
    
?>