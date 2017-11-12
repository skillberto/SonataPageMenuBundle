<?php

namespace Skillberto\SonataPageMenuBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class MenuAdminController extends Controller
{
    public function moveAction(Request $request)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $repo = $this->getDoctrine()->getRepository($this->admin->getClass());

        $menu = $this->getObject();

        switch ($request->get('position')) {
            case 'down':
                $repo->moveDown($menu, 1);
                break;

            case 'bottom':
                $repo->moveDown($menu, true);
                break;

            case 'up':
                $repo->moveUp($menu, 1);
                break;

            case 'top':
                $repo->moveUp($menu, true);
                break;

            default:
                throw new RouteNotFoundException();
        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
    
    /**
     * Activate or inactivate the object
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction()
    {
        $object = $this->getObject();
        
        if (method_exists($object, 'setActive') && method_exists($object, 'getActive')) {
            $object->setActive(($object->getActive()==1) ? 0 : 1);
        }
        
        $this->admin->update($object);
        
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
    
    protected function getObject($objectId = NULL)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        
        $object = $this->admin->getObject(empty($objectId) ? $id : $objectId);
        
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }
        
        return $object;
    }
}