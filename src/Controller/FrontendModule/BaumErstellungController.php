<?php
namespace MargretSchroeder\ContaoStammBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use MargretSchroeder\ContaoStammBundle\Library\StammbaumPerson;
use MargretSchroeder\ContaoStammBundle\Library\Baum;


/**
 * @FrontendModule(category="miscellaneous" )
 */
class BaumErstellungController extends AbstractFrontendModuleController
{
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        /* das funktioniert leider nicht...
        $GLOBALS['TL_CSS'][] = '/files/bootstrap.min.css';
        $GLOBALS['TL_CSS'][] = 'files/allgemein.css';
        */
        
        if ($request->isMethod('post')) {
            if (null !== ($redirectPage = PageModel::findByPk($model->jumpTo))) {
                throw new RedirectResponseException($redirectPage->getAbsoluteUrl());
            }
        }
        
        
        $alle = \MargretSchroeder\ContaoStammBundle\Model\PersonModel::findall();
        $personen = $alle->fetchAll();
       
        
        $baumobject = new StammbaumPerson($personen, 6,6);
        
        //var_dump($baumobject->baum);
        
        $pp = $baumobject->baum;
        
        $template->pp = $pp;
        
        //echo "$pp->vorname   $pp->y   <br>";
        //echo StammbaumPerson::REx;
        
        
         $template->hugo = "dies ist nur ein Versuch";
        
        $template->action = $request->getUri();
        
        //var_dump($template);
        
        return $template->getResponse();
    }
}