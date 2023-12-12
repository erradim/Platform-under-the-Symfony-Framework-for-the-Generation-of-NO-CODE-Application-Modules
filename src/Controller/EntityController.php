<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Entities;
use App\Repository\EntitiesRepository;
use App\Repository\ModuleRepository;
use App\Service\MakeControllerService;
use App\Service\MakeFormService;
use App\Service\MakeEntityService;
use App\Service\RemoveEntityService;

class EntityController extends AbstractController
{
    private $makeControllerService;
    private $makeFormService;
    private $makeEntityService;
    private $removeEntityService;

    public function __construct(
        MakeControllerService $makeControllerService,
        MakeFormService $makeFormService,
        MakeEntityService $makeEntityService,
        RemoveEntityService $removeEntityService
    ) {
        $this->makeControllerService = $makeControllerService;
        $this->makeFormService = $makeFormService;
        $this->makeEntityService = $makeEntityService;
        $this->removeEntityService = $removeEntityService;
    }

    #[Route('/createEntity', name: 'create_entity')]
    public function index(
        EntityManagerInterface $em,
        Request $request,
        EntitiesRepository $repo,
        ModuleRepository $modules
    ): Response {
        if ($request->isMethod('POST')) {
            // Create a new Entity object
            $entity = new Entities();

            // Initialize the name and description variables
            $entityName = '';
            //$description = '';

            // 
            $jsonData = json_decode($request->getContent(), true);

            // Extract the name and description from the JSON data
            $entityName = ucfirst($jsonData['Entity']['name']);
            //$description = $jsonData['Entity']['description'];

            // Extract the module name from the JSON data
            $moduleName = $jsonData['Entity']['moduleName'];

            // Check if the entity already exists
            $entityExists = $repo->findOneBy(['name' => $entityName]);
            // If the entity already exists, return an error response
            if ($entityExists) {
                // Temporary: for testing purposes, let's just delete the entity if it already exists
                // $em->remove($entityExists);
                // $em->flush();
                return new Response('Entity already exists!', Response::HTTP_BAD_REQUEST);
            }

            // Set the name and description of the Entity
            $entity->setName($entityName);
            //$entity->setDescription($description);

            // Assign the module to the Entity
            if ($moduleName != '-') {
                $module = $modules->findOneBy(['name' => $moduleName]);
                $entity->setModule($module);
            }

            // Extract the attributes array from the JSON data
            $attributes = $jsonData['Entity']['attributes'];

            // Extract the relations array from the JSON data
            $relations = $jsonData['Entity']['relations'];

            // Make an array of the entities that are associated with an attributes but only once
            $otherEntities = [];
            foreach ($attributes as $attribute) {
                if ($attribute['entity'] != '') {
                    if (!in_array(($attribute['entity']), $otherEntities)) {
                        // Add the entity to the array
                        array_push($otherEntities, ($attribute['entity']));

                        // Assign the module to the Entity
                        $otherEntity = new Entities();
                        $otherEntity->setName(($attribute['entity']));
                        if ($moduleName != '-') {
                            $module = $modules->findOneBy(['name' => $moduleName]);
                            $otherEntity->setModule($module);
                        }
                        //$otherEntity->setModule($module);
                    }
                }
            }

            // Tell Doctrine you want to (eventually) save the Entity (no queries yet)
            $em->persist($entity);

            // Actually execute the queries (i.e. the INSERT query)
            $em->flush();

            // Loop through the attributes array and check if two attributes have the same name
            // If they do, return an error response
            foreach ($attributes as $attribute) {
                foreach ($attributes as $attribute2) {
                    if ($attribute['name'] == $attribute2['name'] && $attribute != $attribute2) {
                        return new Response('Attributes cannot have the same name!', Response::HTTP_BAD_REQUEST);
                    }
                }
            }

            // Loop through the relations array and check if two relations have the same name
            // If they do, return an error response
            foreach ($relations as $relation) {
                foreach ($relations as $relation2) {
                    if ($relation['name'] == $relation2['name'] && $relation != $relation2) {
                        return new Response('Relations cannot have the same name!', Response::HTTP_BAD_REQUEST);
                    }
                }
            }

            $outputControllerDirectory = "../src/Controller/" . ($entityName);
            $outputTwigDirectory = "../templates/module/" . ($entityName);

            $this->makeControllerService->makeCreateControllerMethod($entityName, $attributes, $outputControllerDirectory, $otherEntities);
            $this->makeControllerService->makeReadControllerMethod($entityName, $outputControllerDirectory);
            $this->makeControllerService->makeUpdateControllerMethod($entityName, $attributes, $outputControllerDirectory);
            $this->makeControllerService->makeDeleteControllerMethod($entityName, $outputControllerDirectory, $otherEntities);

            $this->makeFormService->makeCreateForm($entityName, $attributes, $outputTwigDirectory);
            $this->makeFormService->makeReadForm($entityName, $attributes, $outputTwigDirectory);
            $this->makeFormService->makeUpdateForm($entityName, $attributes, $outputTwigDirectory);
            $this->makeFormService->makeDeleteForm($entityName, $outputTwigDirectory);

            $this->makeEntityService->createEntity($entityName, $attributes);
            $this->makeEntityService->createInnerEntities($attributes);
            $this->makeEntityService->createRelations($entityName, $relations);
            $this->makeEntityService->runMigrations();

            // Return a response to the client
            return new Response('Data received successfully!', Response::HTTP_OK);
        }

        return $this->render('entity/create_entity.html.twig', [
            'entities' => $repo->findAll(),
            'modules' => $modules->findAll(),
            'controller_name' => 'EntityController',
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(
        EntitiesRepository $repo,
        ModuleRepository $modules
    ): Response {
        return $this->render('entity/index.html.twig', [
            'entities' => $repo->findAll(),
            'modules' => $modules->findAll(),
        ]);
    }

    #[Route('/deleteEntity/{id}', name: 'delete_entity')]
    public function deleteEntity(
        EntityManagerInterface $entityManager,
        EntitiesRepository $repo,
        ?int $id = null
    ): Response {

        if ($id) {
            $entity = $repo->findOneBy(['id' => $id]);

            if (!$entity) {
                throw $this->createNotFoundException('Entity not found');
            }

            // Cntrollers and views associated with the entity
            $controllerDirectory = "../src/Controller/" . ($entity->getName());
            $twigDirectory = "../templates/module/" . ($entity->getName());

            // Entity and Repository associated with the entity
            $entityDirectory = "../src/Entity/" . ($entity->getName()) . ".php";
            $repositoryDirectory = "../src/Repository/" . ($entity->getName()) . "Repository.php";

            // If deleteTable($entityName, $entityManager); fails, return an error response
            if (!$this->removeEntityService->deleteTable($entity->getName(), $entityManager)) {
                return new Response('Table not found!', Response::HTTP_BAD_REQUEST);
            }

            // If deleteController($controllerDirectory); fails, return an error response
            if (!$this->removeEntityService->deleteController($controllerDirectory)) {
                return new Response('Controller not found!', Response::HTTP_BAD_REQUEST);
            }

            // If deleteForm($twigDirectory); fails, return an error response
            if (!$this->removeEntityService->deleteForm($twigDirectory)) {
                return new Response('Form not found!', Response::HTTP_BAD_REQUEST);
            }

            // If deleteEntity($entityDirectory); fails, return an error response
            if (!$this->removeEntityService->deleteEntity($entityDirectory)) {
                return new Response('Entity not found!', Response::HTTP_BAD_REQUEST);
            }

            // If deleteRepository($repositoryDirectory); fails, return an error response
            if (!$this->removeEntityService->deleteRepository($repositoryDirectory)) {
                return new Response('Repository not found!', Response::HTTP_BAD_REQUEST);
            }

            // Delete the entity
            $entityManager->remove($entity);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute('home');
    }
}
