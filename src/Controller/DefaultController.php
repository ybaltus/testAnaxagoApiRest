<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findAll();

        return $this->render('Default/index.html.twig', ['projects' => $projects]);
    }
}
