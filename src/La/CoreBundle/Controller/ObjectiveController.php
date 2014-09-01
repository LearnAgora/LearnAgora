<?php

namespace La\CoreBundle\Controller;

use La\CoreBundle\Entity\Action;
use La\CoreBundle\Entity\Logos;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Phronesis;
use La\CoreBundle\Entity\Techne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ObjectiveController extends Controller
{
    public function indexAction()
    {
        $entity = null;
        if (isset($_REQUEST['id'])) {
            $entityType = $_REQUEST["type"];
            $name = $_REQUEST["name"];
            $description = $_REQUEST["description"];
            $entity = new Action();
            switch ($entityType) {
                case "phronesis" : $entity = new Phronesis(); break;
                case "techne" : $entity = new Techne(); break;
                case "logos" : $entity = new Logos(); break;
                case "objective" : $entity = new Objective(); break;
                case "action" : $entity = new Action(); break;
            }
            $entity->setName($name);
            $entity->setDescription($description);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
        }

        return $this->render('LaCoreBundle:Objective:index.html.twig', array(
            'name' => 'objectives',
            'entity' => $entity,
        ));
    }
}
