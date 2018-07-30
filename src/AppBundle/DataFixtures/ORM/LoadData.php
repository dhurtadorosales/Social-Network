<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadData extends Fixture
{
    private $encoder;

    /**
     * LoadData constructor.
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1
            ->setRole('ROLE_USER')
            ->setEmail('clark@kent.com')
            ->setFirstName('Clark')
            ->setLastName('Kent')
            ->setPassword($this->encoder->encodePassword($user1, 'clark'))
            ->setNick('Superman')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user2 = new User();
        $user2
            ->setRole('ROLE_USER')
            ->setEmail('bruce@wayne.com')
            ->setFirstName('Bruce')
            ->setLastName('Wayne')
            ->setPassword($this->encoder->encodePassword($user2, 'bruce'))
            ->setNick('Batman')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user3 = new User();
        $user3
            ->setRole('ROLE_USER')
            ->setEmail('diana@prince.com')
            ->setFirstName('Diana')
            ->setLastName('Prince')
            ->setPassword($this->encoder->encodePassword($user3, 'diana'))
            ->setNick('Wonder Woman')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user4 = new User();
        $user4
            ->setRole('ROLE_USER')
            ->setEmail('steve@rogers.com')
            ->setFirstName('Steve')
            ->setLastName('Rogers')
            ->setPassword($this->encoder->encodePassword($user4, 'steve'))
            ->setNick('Captain America')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user5 = new User();
        $user5
            ->setRole('ROLE_USER')
            ->setEmail('peter@parker.com')
            ->setFirstName('Peter')
            ->setLastName('Parker')
            ->setPassword($this->encoder->encodePassword($user5, 'peter'))
            ->setNick('Spiderman')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user6 = new User();
        $user6
            ->setRole('ROLE_USER')
            ->setEmail('scott@summers.com')
            ->setFirstName('Scott')
            ->setLastName('Summers')
            ->setPassword($this->encoder->encodePassword($user6, 'scott'))
            ->setNick('Cyclops')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user7 = new User();
        $user7
            ->setRole('ROLE_USER')
            ->setEmail('james@howlett.com')
            ->setFirstName('James')
            ->setLastName('Howlett')
            ->setPassword($this->encoder->encodePassword($user7, 'james'))
            ->setNick('Wolverine')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $user8 = new User();
        $user8
            ->setRole('ROLE_USER')
            ->setEmail('bruce@banner.com')
            ->setFirstName('Bruce')
            ->setLastName('Banner')
            ->setPassword($this->encoder->encodePassword($user8, 'bruce'))
            ->setNick('Hulk')
            ->setBio(null)
            ->setActive(true)
            ->setImage(null);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->persist($user5);
        $manager->persist($user6);
        $manager->persist($user7);
        $manager->persist($user8);

        $manager->flush();
    }
}