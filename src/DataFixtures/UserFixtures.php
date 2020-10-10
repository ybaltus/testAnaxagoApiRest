<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 12/07/18
 * Time: 17:33
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public const USER_REFERENCE = 'userPI';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $investor = (new User())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@local.com')
            ->setPlainPassword('john')
            ->setCreatedAt(new \DateTime('now'));
        $password = $this->passwordEncoder->encodePassword($investor, $investor->getPlainPassword());
        $investor->setPassword($password);
        $manager->persist($investor);

        $admin = (new User())
            ->setFirstName('admin')
            ->setLastName('anaxago')
            ->setEmail('admin@local.com')
            // because we like security
            ->setPlainPassword('admin')
            ->setCreatedAt(new \DateTime('now'))
            ->addRoles('ROLE_ADMIN');
        ;
        $password = $this->passwordEncoder->encodePassword($admin, $admin->getPlainPassword());
        $admin->setPassword($password);
        $manager->persist($admin);

        $manager->flush();

        //Other fixtures can get this object
        $this->addReference(self::USER_REFERENCE, $investor);
    }
}
