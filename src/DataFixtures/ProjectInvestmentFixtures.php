<?php


namespace App\DataFixtures;


use App\Entity\ProjectInvestment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectInvestmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //Create 2 projectInvestments with the same user
        for ($i = 0; $i < 2; $i++) {
            $investment = (new ProjectInvestment())
                ->setAmount(10000)
                ->setUser($this->getReference(UserFixtures::USER_REFERENCE));

            if($i==0)
            {
                $investment->setProject($this->getReference(ProjectFixtures::Project1_REFERENCE));
            }else{
                $investment->setProject($this->getReference(ProjectFixtures::Project2_REFERENCE));
            }

            $manager->persist($investment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProjectFixtures::class,
            UserFixtures::class
        );
    }
}