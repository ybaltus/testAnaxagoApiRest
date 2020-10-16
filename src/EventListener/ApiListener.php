<?php

namespace App\EventListener;

use App\Entity\Project;
use App\Entity\ProjectInvestment;
use App\Notification\ThresholdNotification;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class ApiListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ThresholdNotification
     */
    private $notification;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, ContainerInterface $container, ThresholdNotification $notification)
    {
        $this->em = $em;
        $this->container = $container;
        $this->logger = $logger;
        $this->notification = $notification;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        if(strcmp('api_investment_add', $routeName) === 0)
        {
            $projectSlug = $request->request->get('slug');

            $project = $this->container->get('doctrine')->getRepository(Project::class)->findOneBy(array('slug'=>$projectSlug));
            $this->sendThresholdNotifications($project);
        }
    }

    /**
     * Send thresholdNotifications
     * @param $project
     */
    private function sendThresholdNotifications($project)
    {
        $repository = $this->container->get('doctrine')->getRepository(ProjectInvestment::class);
        $sum_funding = $repository->getSumFundedByProject($project);

        //Check the threshold of the project
        if($sum_funding >= $project->getThreshold())
        {
            //Update the project
            if(!$project->getFullyFunded())
            {
                $project->setFullyFunded(true);
                $this->em->persist($project);
                $this->em->flush();

            //Get the users
            $users = $repository->getUsersByProject($project);

            //Send notifications to the users
            $this->notification->notify($project, $sum_funding, $users);
            }
        }
    }
}
