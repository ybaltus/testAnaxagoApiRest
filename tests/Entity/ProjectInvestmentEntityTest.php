<?php


namespace App\Tests\Entity;


use App\Entity\Project;
use App\Entity\ProjectInvestment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class ProjectInvestmentEntityTest extends KernelTestCase
{
    /**
     * Test if amount is negative
     */
    public function testAmountNegative()
    {
        $investmentEntity=$this->createInvestmentEntity()->setAmount(-1);
        $this->assertErrorsWithValidator($investmentEntity, 1);
    }

    /**
     * Use the validator
     *
     * @param ProjectInvestment $investment
     */
    private function assertErrorsWithValidator(ProjectInvestment $investment, $number)
    {
        self::bootKernel();
        $container = self::$container;
        $errors = $container->get('validator')->validate($investment);
        $messages = [];
        /**
         * @var ConstraintViolation $error
         */
        foreach ($errors as $error)
        {
            array_push($messages, $error->getPropertyPath()." - ".$error->getMessage());
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    /**
     * Create ProjectInvestment
     *
     * @return ProjectInvestment
     */
    private function createInvestmentEntity()
    {
        self::bootKernel();
        $container = self::$container;
        $doctrine = $container->get('doctrine');
        $userEntity = $doctrine->getRepository(User::class)->findOneBy(array('email'=>'john@local.com'));
        $projectEntity = $doctrine->getRepository(Project::class)->findOneBy(array('slug'=>'fred-compta'));

        $investmentEntity = (new ProjectInvestment())
            ->setAmount(2000)
            ->setProject($projectEntity)
            ->setUser($userEntity)
        ;
        return $investmentEntity;
    }
}