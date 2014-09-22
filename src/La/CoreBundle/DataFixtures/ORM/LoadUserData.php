<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    function load(ObjectManager $manager)
    {
        $this->encoderFactory = $this->container->get('security.encoder_factory');

        $manager->persist($this->createUser('Vic', 'vic@learnagora.com'));
        $manager->persist($this->createUser('Anna', 'anna@learnagora.com'));
        $manager->persist($this->createUser('Bart', 'bart@learnagora.com'));

        $manager->flush();
    }

    private function createUser($username, $email)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setIsActive(1);

        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword('secret', $user->getSalt());

        $user->setPassword($password);

        return $user;
    }
}
