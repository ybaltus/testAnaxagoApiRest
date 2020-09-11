<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 14/07/18
 * Time: 15:21
 */

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @licence proprietary anaxago.com
 * Class ProjectFixtures
 * @package App\DataFixtures\ORM
 */
class ProjectFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getProjects() as $project) {
            $projectToPersist = (new Project())
                ->setTitle($project['name'])
                ->setDescription($project['description'])
                ->setSlug($project['slug']);
            $manager->persist($projectToPersist);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getProjects(): array
    {
        return [
            [
                'name' => 'Fred de la compta',
                'description' => 'Dépoussiérer la comptabilité grâce à l\'intelligence artificielle',
                'slug' => 'fred-compta',
            ],
            [
                'name' => 'Mojjo',
                'description' => 'L\'intelligence artificielle au service du tennis : Mojjo transforme l\'expérience des joueurs et des fans de tennis grâce à une technologie unique de captation et de traitement de la donnée',
                'slug' => 'mojjo',
            ],
            [
                'name' => 'Eole',
                'description' => 'Projet de construction d\'une résidence de 80 logements sociaux à Petit-Bourg en Guadeloupe par le promoteur Orion.',
                'slug' => 'eole',
            ],
        ];
    }
}
