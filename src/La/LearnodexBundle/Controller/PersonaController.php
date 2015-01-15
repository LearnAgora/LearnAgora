<?php

namespace La\LearnodexBundle\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\Affinity;
use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Persona;
use La\CoreBundle\Entity\User;
use La\LearnodexBundle\Forms\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PersonaController extends Controller
{
    /**
     * @var ObjectManager $entityManager
     *
     *  @DI\Inject("doctrine.orm.entity_manager"),
     */
    private $entityManager;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.persona")
     */
    private $personaRepository;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.agora")
     */
    private $agoraRepository;
    /**
     * @var ObjectRepository
     *
     * @DI\Inject("la_core.repository.affinity")
     */
    private $affinityRepository;

    public function indexAction()
    {
        $persona = $this->personaRepository->findAll();

        return $this->render('LaLearnodexBundle:Persona:index.html.twig',array(
            'persona'      => $persona,
        ));
    }

    public function editAction(Request $request, $id=0)
    {
        /** @var $persona Persona */
        if ($id) {
            $persona = $this->personaRepository->find($id);
        } else {
            $persona = new Persona();
        }

        $form = $this->createFormBuilder($persona)
            ->setAction('#')
            ->add('user',new UserType(), array(
            ))
            ->add('description','text', array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control h1',
                    'placeholder' => 'Enter description',
                ),
            ))
            ->add('create','submit', array('label' => $id ? 'Save' : 'Create'))
            ->getForm();

        if (!is_null($request)) {
            $form->handleRequest($request);
        };

        if ($form->isValid()) {
            /** @var $user User */
            $user = $persona->getUser();
            $user->setEmail($user->getUsername());
            $user->setPassword('none');
            $user->setLastLogin(new \DateTime(date('Y-m-d H:i:s',time())));
            $this->entityManager->persist($user);
            $this->entityManager->persist($persona);
            $this->entityManager->flush();

            $this->get('la_learnodex.update_all_affinities');

            return $this->redirect($this->generateUrl('persona_affinity', array('id'=>$persona->getId())));
        }

        return $this->render('LaLearnodexBundle:Persona:new.html.twig',array(
            'form'      => $form->createView(),
            'persona'   => $persona,
        ));
    }

    public function affinityAction(Request $request, $id)
    {
        /** @var $persona Persona */
        if ($id) {
            $persona = $this->personaRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No persona found for id ' . $id );
        }

        $agoras = $this->agoraRepository->findAll();

        if (!is_null($request)) {
            /* @var Agora $agora */
            foreach ($agoras as $agora) {
                $affinityValue = $request->request->get('agora_'.$agora->getId());
                if ($affinityValue) {
                    $affinity = $this->affinityRepository->findOneBy(
                        array(
                            'user'  => $persona->getUser(),
                            'agora' => $agora
                        )
                    );
                    if (!$affinity) {
                        $affinity = new Affinity();
                        $affinity->setUser($persona->getUser());
                        $affinity->setAgora($agora);
                    }
                    $affinity->setValue($affinityValue);
                    $this->entityManager->persist($affinity);
                }
            }
            $this->entityManager->flush();
        };

        $affinities = $persona->getUser()->getAffinities();

        //filter out the unused agoras
        $usedAgoraIdList = array();
        foreach ($affinities as $affinity) {
            $usedAgoraIdList[] = $affinity->getAgora()->getId();
        }
        $unusedAgoras = array();
        foreach ($agoras as $agora) {
            if (!in_array($agora->getId(),$usedAgoraIdList)) {
                $unusedAgoras[] = $agora;
            }
        }

        $this->get('la_learnodex.update_all_affinities');

        return $this->render('LaLearnodexBundle:Persona:affinities.html.twig',array(
            'persona'       => $persona,
            'agoras'        => $unusedAgoras,
            'affinities'    => $affinities,
        ));
    }

    public function removeAffinityAction($personaId,$id)
    {
        $affinity = $this->affinityRepository->find($id);
        $this->entityManager->remove($affinity);
        $this->entityManager->flush();

        $this->get('la_learnodex.update_all_affinities');

        return $this->redirect($this->generateUrl('persona_affinity', array('id'=>$personaId)));
    }

    public function compareAction($id) {
        /** @var $persona Persona */
        if ($id) {
            $persona = $this->personaRepository->find($id);
        } else {
            throw $this->createNotFoundException( 'No persona found for id ' . $id );
        }

        $personaUser = $persona->getUser();
        $affinityArray = $this->affinityRepository->loadAffinitiesForUsers($personaUser);

        return $this->render('LaLearnodexBundle:Persona:compare.html.twig',array(
            'persona'       => $persona,
            'affinityArray' => $affinityArray,
        ));
    }
}
