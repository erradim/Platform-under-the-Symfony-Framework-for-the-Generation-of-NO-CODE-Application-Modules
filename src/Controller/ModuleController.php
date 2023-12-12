<?php

namespace App\Controller;

use App\Entity\Module;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EntitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModuleController extends AbstractController
{
    #[Route('/createModule', name: 'app_create_module')]
    public function createModule(Request $request, EntityManagerInterface $entityManager, ModuleRepository $repo): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // Extract module data from the request JSON
        $moduleName = $requestData['name'];
        $moduleDescription = $requestData['description'];

        // Check if the module already exists
        $moduleExists = $repo->findOneBy(['name' => $moduleName]);
        // If the entity already exists, return an error response
        if ($moduleExists) {
            return new Response('Module already exists!', Response::HTTP_BAD_REQUEST);
        }

        // Create a new Module entity
        $module = new Module();
        $module->setName($moduleName);
        $module->setDescription($moduleDescription);

        // Save the module to the database
        $entityManager->persist($module);
        $entityManager->flush();

        // Return a JSON response indicating success
        return new JsonResponse(['message' => 'Module created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/view/{name}', name: 'app_view_module')]
    public function index(ModuleRepository $modules, EntitiesRepository $repo, ?string $name = null): Response
    {
        if ($name) {
            $module = $modules->findOneBy(['name' => $name]);

            if (!$module) {
                throw $this->createNotFoundException('Module not found');
            }

            // Return the entities in the module
            $moduleEntities = $module->getEntities();

            return $this->render('module/view_module.html.twig', [
                'module' => $name,
                'entities' => $moduleEntities,
            ]);
        }

        return $this->render('module/view_module.html.twig', [
            'entities' => $repo->findAll(),
        ]);
    }

    #[Route('/viewModules', name: 'app_view_modules')]
    public function viewModules(ModuleRepository $repo): Response
    {
        return $this->render('module/view_modules_table.html.twig', [
            'modules' => $repo->findAll(),
        ]);
    }

    #[Route('/delete/{name}', name: 'app_delete_module')]
    public function deleteModule(
        EntityManagerInterface $entityManager,
        ModuleRepository $repo,
        ?string $name = null
    ): Response {
        if ($name) {
            $module = $repo->findOneBy(['name' => $name]);

            if (!$module) {
                throw $this->createNotFoundException('Module not found');
            }

            // Delete the module
            $entityManager->remove($module);
            $entityManager->flush();

            return $this->redirectToRoute('app_view_modules');
        }

        return $this->render('module/view_modules_table.html.twig', [
            'modules' => $repo->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit_module')]
    public function editModule(
        EntityManagerInterface $entityManager,
        Request $request,
        ModuleRepository $repo,
        ?int $id = null
    ): Response {
        if ($request->isMethod('POST')) {

            $name = $request->request->get('name');
            $description = $request->request->get('description');

            $module = $repo->findOneBy(['id' => $id]);

            if (!$module) {
                throw $this->createNotFoundException('Module not found');
            }

            $module->setName($name);
            $module->setDescription($description);

            $entityManager->flush();

            return $this->redirectToRoute('app_view_modules');
        }

        $module = $entityManager->getRepository(Module::class)->findOneBy([
            'id' => $id,
        ]);

        if (!$module) {
            throw $this->createNotFoundException('Module not found');
        }

        return $this->render('module/edit_module.html.twig', [
            'name' => $module->getName(), 'description' => $module->getDescription(),
        ]);
    }
}
