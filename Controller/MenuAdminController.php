<?php

namespace Skillberto\SonataPageMenuBundle\Controller;

use Skillberto\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
}