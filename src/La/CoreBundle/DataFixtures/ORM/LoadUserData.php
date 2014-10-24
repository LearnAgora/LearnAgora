<?php

namespace La\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use La\CoreBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
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
    public function load(ObjectManager $manager)
    {
        $this->encoderFactory = $this->container->get('security.encoder_factory');

        $admin = $this->createUser('admin', 'admin@learnagora.com');
        $admin->setSuperAdmin(true);

        $user1 = $this->createUser('Vic', 'vic@learnagora.com');
        $user2 = $this->createUser('Anna', 'anna@learnagora.com');
        $user3 = $this->createUser('Bart', 'bart@learnagora.com');

        $this->addReference('admin', $admin);
        $this->addReference('user-vic', $user1);
        $this->addReference('user-anna', $user2);
        $this->addReference('user-bart', $user3);

        $manager->persist($admin);
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }

    private function createUser($username, $email)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setEnabled(true);

        $encoder = $this->encoderFactory->getEncoder($user);
        $password = $encoder->encodePassword('secret', $user->getSalt());

        $user->setPassword($password);

        return $user;
    }
}
