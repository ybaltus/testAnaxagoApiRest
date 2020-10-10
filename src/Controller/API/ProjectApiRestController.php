<?php

namespace App\Controller\API;

use App\Entity\Project;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class ProjectApiRestController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Get list of projects
     *
     * @Route("/list/projects", name="api_list_project", methods={"GET"})
     */
    public function listProjects()
    {
        $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();

        $data = array();
        if(isset($projects) AND count($projects)>0)
        {
            foreach ($projects as $project)
            {
                $data[] = [
                    "title" => $project->getTitle(),
                    "description" => $project->getDescription(),
                    "status" => Project::STATUS_FUNDED[$project->getFullyFunded()]
                ];
            }
        }

        $data=$this->serializer->serialize($data, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK, "List of projects correctly returned.");
        return $response;
    }
}
