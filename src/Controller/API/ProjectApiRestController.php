<?php

namespace App\Controller\API;

use App\Entity\Project;
use App\Entity\ProjectInvestment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/v1")
 */
class ProjectApiRestController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
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

        return $this->responseInfoHandler($data, Response::HTTP_OK, "List of projects correctly returned.");

    }

    /**
     * Add an ProjectInvestment
     *
     * @Route("/investment/add", name="api_investment_add", methods={"POST"})
     * @param Request $request
     * @return object|Response|null
     */
    public function addProjectInvestment(Request $request)
    {
        //Post parameters
        $slug=$request->request->get('slug');
        $email = $request->request->get('email');
        $plainPassword = $request->request->get('password');
        $amount = $request->request->get('amount');

        //Check missing arguments
        $response = $this->missingArgumentsHandler($email, $plainPassword,false, $slug, $amount);
        if(isset($response))
            return $response;

        //Check user exist
        $user = $this->identifierHandler($email, $plainPassword);
        if(!$user instanceof User)
            return $user;

        //Check project exist
        $project = $this->getProject($slug);
        if(!$project instanceof Project)
            return $project;

        //Insert or update the amount of investment
        $invest = $this->getDoctrine()->getRepository(ProjectInvestment::class)->findByUserProject($user, $project);
        if(!isset($invest))
        {
            $invest = new ProjectInvestment();
            $invest->setUser($user);
            $invest->setProject($project);
            $invest->setAmount($amount);
        }else{
            $invest->setAmount($amount+$invest->getAmount());
        }
        $this->em->persist($invest);
        $this->em->flush();

        return $this->responseInfoHandler($invest, Response::HTTP_CREATED, "Investment correctly added.");
    }


    /**
     * Get the responses with seralizerContext:info
     *
     * @param $data
     * @param $statusCode
     * @param $text
     * @return Response
     */
    private function responseInfoHandler($data, $statusCode, $text)
    {
        $data=$this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups(array('info')));
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($statusCode, $text);
        return $response;

    }

    /**
     * Check missing arguments
     *
     * @param $slug
     * @param $email
     * @param $plainPassword
     * @param $amount
     * @param bool $isUnauthorized
     * @return Response
     */
    private function missingArgumentsHandler($email, $plainPassword, $isUnauthorized, $slug=null, $amount=null)
    {
        $response= new Response("Error occured - Bad request - Missing arguments", Response::HTTP_BAD_REQUEST);
        if($isUnauthorized == true)
        {
            if(!isset($email) OR !isset($password))
            {
                return $response;
            }
        }else{
            if(!isset($email) OR !isset($plainPassword) OR !isset($amount) OR !isset($slug))
            {
                return $response;
            }
        }
    }

    /**
     * Check user exist
     * @param $email
     * @param $plainPassword
     * @return object|Response|null
     */
    private function identifierHandler($email, $plainPassword)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email'=>$email));
        if(isset($user))
        {
            $user->setPlainPassword($plainPassword);
            if($this->passwordEncoder->isPasswordValid($user, $user->getPlainPassword()))
                return $user;
        }
        return new Response("Error occured - Bad authentication", Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Check project exist
     * @param $slug
     * @return object|Response
     */
    private function getProject($slug)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(array('slug'=>$slug));

        if(isset($project))
        {
            return $project;
        }
        return new Response("Error occured - The ressource does not exist", Response::HTTP_NOT_FOUND);
    }
}
