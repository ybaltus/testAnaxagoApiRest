<?php


namespace App\Notification;

use App\Entity\Project;
use App\Entity\User;
use Twig\Environment;

class ThresholdNotification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Project $project, $sum_funding, $users)
    {
        //users[firstname, lastname, email, amount]

        foreach ($users as $user)
        {
            $message= (new \Swift_Message('Le projet '.$project->getTitle().' est financé.'))
                ->setFrom('noreply@local.com')
                ->setTo($user['email'])
                ->setBody($this->renderer->render('emails/threshold.html.twig', array(
                    'user' => $user,
                    'project' => $project,
                    'sum_funding' => $sum_funding
                )), 'text/html'
                )
            ;
            $this->mailer->send($message);
        }
    }
}