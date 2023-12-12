<?php

namespace App\Service;

class MakeControllerService
{
    /**
     * Generate the code for the create controller
     *
     * @param string $entityName
     * @param array $attributes
     * @param string $outputDirectory
     * @return void
     */
    public static function makeCreateControllerMethod($entityName, $attributes, $outputDirectory, $otherEntities)
    {
        //$outputDirectory = "output/" . ($entityName) . "/controllers";

        // Create the output directory if it doesn't exist
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        // Create a new file
        $file = fopen($outputDirectory . "/Create" . $entityName . "Controller.php", "w");

        // Write the namespace, class declaration, and entity instantiation code to the file
        fwrite(
            $file,
            "<?php\n\n"
                . "namespace App\Controller\\" . ($entityName) . ";\n\n"
                . "use App\Entity\\" . $entityName . ";\n"
        );

        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "use App\Entity\\" . $otherEntity . ";\n");
        }

        fwrite(
            $file,
            "use App\Repository\ModuleRepository;\n"
                . "use Symfony\Component\Routing\Annotation\Route;\n"
                . "use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;\n"
                . "use Symfony\Component\HttpFoundation\Request;\n"
                . "use Doctrine\ORM\EntityManagerInterface;\n"
                . "use Symfony\Component\HttpFoundation\Response;\n\n"
                . "\$request = Request::createFromGlobals();\n\n"
                . "class Create" . $entityName . "Controller extends AbstractController\n"
                . "{\n"
                . "    #[Route('/create" . $entityName . "', name: 'app_create_" . ($entityName) . "')]\n"
                . "    public function create" . $entityName . "(EntityManagerInterface \$em, Request \$request, ModuleRepository \$repo): Response\n"
                . "    {\n"
                . "        \$" . ($entityName) . " = new $entityName();\n\n"
        );

        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "        \$" . ($otherEntity) . " = new $otherEntity();\n");
        }

        fwrite(
            $file,
            "\n        if(\$request->isMethod('POST') ) {\n\n"
        );

        // Initialize the attribute string
        $attributeString = '';

        // Loop through the attributes array and generate code to instantiate the attributes
        foreach ($attributes as $attribute) {
            if ($attribute['entity'] == '') {
                $attributeOwner = $entityName;
            } else {
                $attributeOwner = ($attribute['entity']);
            }

            if ($attribute['type'] == 'date' || $attribute['type'] == 'time' || $attribute['type'] == 'datetime' || $attribute['type'] == 'datetimetz') {

                $attributeName = $attribute['name'];

                // Write the code to instantiate the attribute
                fwrite($file, "            // Get the " . $attributeName . " from the request\n");
                fwrite($file, "            \$" . $attributeName . " = new \DateTime(\$request->request->get('" . $attributeName . "'));\n");
            } elseif ($attribute['type'] == 'dateinterval') {

                $attributeName = $attribute['name'];

                // Write the code to instantiate the attribute
                fwrite($file, "            // Get the " . $attributeName . " from the request\n");
                fwrite($file, "            \$" . $attributeName . " = new \DateInterval(\$request->request->get('" . $attributeName . "'));\n");
            } else {

                $attributeName = $attribute['name'];

                // Write the code to instantiate the attribute
                fwrite($file, "            // Get the " . $attributeName . " from the request\n");
                fwrite($file, "            \$" . $attributeName . " = \$request->request->get('" . $attributeName . "');\n");
            }

            if ($attribute['type'] == 'simple_array') {
                // Write the code to set the attribute on the entity
                //  $test->setSimpleArray(explode(',', $request->request->get('simple_array')));
                fwrite($file, "            // Set the $attributeName on the entity\n");
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(explode(',', \$" . $attributeName . "));\n\n");
            } elseif ($attribute['type'] == 'json') {
                // Write the code to set the attribute on the entity
                // $test->setJson(json_decode($request->request->get('json'), true));
                fwrite($file, "            // Set the $attributeName on the entity\n");
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(json_decode(\$" . $attributeName . ", true));\n\n");
            } else {
                // Write the code to set the attribute on the entity
                fwrite($file, "            // Set the $attributeName on the entity\n");
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(\$" . $attributeName . ");\n\n");
            }
            // Build the string to render the attribute in the view using a string builder
            $attributeString = $attributeString . "'" . $attributeName . "' => " . "''" . ",\n                ";
        }

        fwrite($file, "            // Set the entity's otherEntities\n");
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "            \$" . ($entityName) . "->set" . ($otherEntity) . "(\$" . ($otherEntity) . ");\n");
        }
        fwrite($file, "\n");

        // Write the persistence and response code
        fwrite(
            $file,
            "            // Persist and flush the entity\n"
                . "            \$em->persist($" . ($entityName) . ");\n"
        );
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "            \$em->persist($" . ($otherEntity) . ");\n");
        }
        fwrite(
            $file,
            "            \$em->flush();\n\n"
                . "            // Return a success response\n"
                . "            return \$this->render(" . "'module/" . ($entityName) . "/create_" . ($entityName) . ".html.twig', [\n"
                . "                'modules' => \$repo->findAll(),\n"
                . "                " . $attributeString
                . "            ]);\n"
                . "        }\n\n"
        );

        // Write the code to render the form. The attributes must be initialized to empty values.
        fwrite($file, "        // Return a form\n");
        fwrite($file, "        return \$this->render(" . "'module/" . ($entityName) . "/create_" . ($entityName) . ".html.twig', [\n");

        // Render 'modules' => $repo->findAll(),
        fwrite($file, "            'modules' => \$repo->findAll(),\n");

        // Loop through the attributes array and generate code to render the form
        foreach ($attributes as $attribute) {
            $attributeName = $attribute['name'];

            // Write the code to render the attribute in the view
            fwrite($file, "            '" . $attributeName . "' => '',\n");
        }

        // Close the array and the render method
        fwrite($file, "        ]);\n    }\n}\n");

        // Close the file
        fclose($file);
    }

    /**
     * Generate the code for the update controller
     *
     * @param string $entityName
     * @param array $attributes
     * @param string $outputDirectory
     * @return void
     */
    public static function makeReadControllerMethod($entityName, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/controllers";

        // Create a new file
        $file = fopen($outputDirectory . "/Read" . $entityName . "Controller.php", "w");

        // Write the namespace and class declaration code to the file
        fwrite(
            $file,
            "<?php\n\n"
                . "namespace App\Controller\\" . ($entityName) . ";\n\n"
                . "use App\Entity\\" . $entityName . ";\n"
        );
        /*
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "use App\Entity\\" . $otherEntity . ";\n");
        }
        */

        fwrite(
            $file,


            "use App\Repository\ModuleRepository;\n"
                . "use Dompdf\Dompdf;\n"
                . "use Symfony\Component\Routing\Annotation\Route;\n"
                . "use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;\n"
                . "use Doctrine\ORM\EntityManagerInterface;\n"
                . "use Symfony\Component\HttpFoundation\Response;\n\n"
                . "class Read" . $entityName . "Controller extends AbstractController\n"
                . "{\n"
        );

        // Write the code for the read controller method
        fwrite(
            $file,
            "    #[Route('/read" . ($entityName) . "', name: 'app_list_" . ($entityName) . "')]\n"
                . "    public function read" . ($entityName) . "(EntityManagerInterface \$em, ModuleRepository \$repo): Response\n"
                . "    {\n"
                . "        \$" . ($entityName) . " = \$em->getRepository(" . ($entityName) . "::class)->findAll();\n"
                . "        return \$this->render('module/" . ($entityName) . "/read_" . ($entityName) . ".html.twig', [\n"
                . "            'modules' => \$repo->findAll(),\n"
                . "            '" . ($entityName) . "' => $" . ($entityName) . ",\n"
        );

        fwrite(
            $file,
            "        ]);\n"
                . "    }\n\n"
        );

        // Write the code for the pdf download method
        fwrite($file, "    #[Route('/read" . ($entityName) . "/{id}', name: 'app_read_" . ($entityName) . "')]\n");
        fwrite($file, "    public function read" . ($entityName) . "Details(EntityManagerInterface \$em, \$id, ModuleRepository \$repo)\n");
        fwrite($file, "    {\n");
        fwrite($file, "        \$" . ($entityName) . "Details = \$em->getRepository(" . ($entityName) . "::class)->findOneBy(['id' => \$id]);\n");
        fwrite($file, "        \$html = \$this->render('module/" . ($entityName) . "/read_" . ($entityName) . ".html.twig', [\n");
        fwrite($file, "            'modules' => \$repo->findAll(),\n");
        fwrite($file, "            '" . ($entityName) . "' => [\$" . ($entityName) . "Details],\n");

        fwrite($file, "        ]);\n\n");

        // Create a new Dompdf instance
        fwrite($file, "        // Create a new Dompdf instance\n");
        fwrite($file, "        \$dompdf = new Dompdf();\n\n");

        // Load the HTML into Dompdf
        fwrite($file, "        // Load the HTML into Dompdf\n");
        fwrite($file, "        \$dompdf->loadHtml(\$html);\n\n");

        // Set the paper size and orientation
        fwrite($file, "        // Set the paper size and orientation\n");
        fwrite($file, "        \$dompdf->setPaper('A2', 'landscape');\n\n");

        // Render the PDF
        fwrite($file, "        // Render the PDF\n");
        fwrite($file, "        \$dompdf->render();\n\n");

        // Generate the PDF file name
        fwrite($file, "        // Generate the PDF file name\n");
        fwrite($file, "        \$fileName = '" . ($entityName) . "_details_' . \$id . '.pdf';\n\n");

        // Output the PDF as a downloadable response
        fwrite($file, "        // Output the PDF as a downloadable response\n");
        fwrite($file, "        return new Response(\n");
        fwrite($file, "            \$dompdf->output(),\n");
        fwrite($file, "            200,\n");
        fwrite($file, "            [\n");
        fwrite($file, "                'Content-Type' => 'application/pdf',\n");
        fwrite($file, "                'Content-Disposition' => 'attachment; filename=\"' . \$fileName . '\"',\n");
        fwrite($file, "            ]\n");
        fwrite($file, "        );\n");
        fwrite($file, "    }\n");
        fwrite($file, "}\n");


        // Close the file
        fclose($file);
    }

    /**
     * Generate the code for the update controller
     *
     * @param string $entityName
     * @param array $attributes
     * @param string $outputDirectory
     * @return void
     */
    public static function makeUpdateControllerMethod($entityName, $attributes, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/controllers";

        // Create a new file
        $file = fopen($outputDirectory . "/Update" . $entityName . "Controller.php", "w");

        // Write the namespace and class declaration code to the file
        fwrite(
            $file,
            "<?php\n\n"
                . "namespace App\Controller\\" . ($entityName) . ";\n\n"
                . "use App\Entity\\" . $entityName . ";\n"
                . "use App\Repository\ModuleRepository;\n"
                . "use Symfony\Component\Routing\Annotation\Route;\n"
                . "use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;\n"
                . "use Symfony\Component\HttpFoundation\Request;\n"
                . "use Doctrine\ORM\EntityManagerInterface;\n"
                . "use Symfony\Component\HttpFoundation\Response;\n\n"
                . "class Update" . $entityName . "Controller extends AbstractController\n"
                . "{\n"
                . "    #[Route('/update" . $entityName . "/{id}', name: 'app_update_" . ($entityName) . "')]\n"
                . "    public function update" . $entityName . "(EntityManagerInterface \$em, Request \$request, ModuleRepository \$repo, \$id = null): Response\n"
                . "    {\n"
                . "        if (\$request->isMethod('POST')) {\n\n"
        );

        // Loop through the attributes to get the attribute names
        foreach ($attributes as $attribute) {
            $attributeName = $attribute['name'];

            if ($attribute['type'] == 'date' || $attribute['type'] == 'datetime' || $attribute['type'] == 'time' || $attribute['type'] == 'datetimetz') {
                fwrite($file, "            $" . ($attributeName) . " = new \DateTime(\$request->request->get('" . $attributeName . "'));\n");
            } elseif ($attribute['type'] == 'dateinterval') {
                fwrite($file, "            $" . ($attributeName) . " = new \DateInterval(\$request->request->get('" . $attributeName . "'));\n");
            } else {
                fwrite($file, "            $" . ($attributeName) . " = \$request->request->get('" . $attributeName . "');\n");
            }
        }

        // $attributeName = $attributes[0]['name'];
        // Write the code to get the entity from the database
        fwrite($file, "\n            \$" . ($entityName) . " = \$em->getRepository(" . $entityName . "::class)->findOneBy([\n");
        fwrite($file, "                '" . "id" . "' =>" . "\$id" . ",\n");
        fwrite($file, "            ]);\n\n");

        // Write the code in case the entity is not found
        fwrite($file, "            if (!\$" . ($entityName) . ") {\n");
        fwrite($file, "                throw \$this->createNotFoundException(\n");
        fwrite($file, "                    'No " . ($entityName) . " found for id ' . \$id" . "\n");
        fwrite($file, "                );\n");
        fwrite($file, "            }\n\n");

        // Write the code to set the attributes of the entity
        foreach ($attributes as $attribute) {
            if ($attribute['entity'] == '') {
                $attributeOwner = $entityName;
            } else {
                $attributeOwner = $entityName . "->get" . ($attribute['entity']) . "()";
            }

            $attributeName = $attribute['name'];
            $attributeType = $attribute['type'];

            if ($attributeType == 'simple_array') {
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(explode(',', \$" . ($attributeName) . "));\n");
            } elseif ($attributeType == 'json') {
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(json_decode(\$" . ($attributeName) . ", true));\n");
            } else {
                fwrite($file, "            \$" . ($attributeOwner) . "->set" . ($attributeName) . "(\$" . ($attributeName) . ");\n");
            }
        }

        fwrite($file, "\n            \$em->flush();\n\n");

        // Render all the attributes in the view
        fwrite($file, "            return \$this->redirectToRoute('app_list_" . ($entityName) . "');\n");
        /*
        // Loop through the attributes array and generate code to render the form
        foreach ($attributes as $attribute) {
            $attributeName = $attribute['name'];
            fwrite($file, "                '" . $attributeName . "' => '',\n");
        }
        */

        //fwrite($file, "            ]);\n");
        fwrite($file, "        }\n");

        // Get the attribute from the repository by id
        fwrite($file, "\n            \$" . ($entityName) . " = \$em->getRepository(" . $entityName . "::class)->findOneBy([\n");
        fwrite($file, "                '" . "id" . "' =>" . "\$id" . ",\n");
        fwrite($file, "            ]);\n\n");

        //If the entity is not found, throw an exception
        fwrite($file, "        if (!\$" . ($entityName) . ") {\n");
        fwrite($file, "            throw \$this->createNotFoundException(\n");
        fwrite($file, "                'No " . ($entityName) . " found for id ' . \$id" . "\n");
        fwrite($file, "            );\n");
        fwrite($file, "        }\n\n");

        // Render all the attributes in the view
        fwrite($file, "        return \$this->render(" . "'module/" . ($entityName) . "/update_" . ($entityName) . ".html.twig', [\n");

        // Render 'modules' => $repo->findAll(),
        fwrite($file, "            'modules' => \$repo->findAll(),\n");

        // Loop through the attributes array and generate code to render the form
        foreach ($attributes as $attribute) {
            if ($attribute['entity'] == '') {
                $attributeOwner = $entityName;
            } else {
                $attributeOwner = $entityName . "->get" . ($attribute['entity']) . "()";
            }

            $attributeName = $attribute['name'];
            $attributeType = $attribute['type'];

            // Write the code to render the attribute in the view
            if ($attributeType == 'date') {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->get" . $attributeName . "()->format('Y-m-d'),\n");
            } elseif ($attributeType == 'datetime' || $attributeType == 'datetimetz') {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->get" . $attributeName . "()->format('Y-m-d H:i:s'),\n");
            } elseif ($attributeType == 'time') {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->get" . $attributeName . "()->format('H:i:s'),\n");
            } elseif ($attributeType == 'dateinterval') {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->get" . $attributeName . "()->format('P%YY%MM%DDT%HH%MM%SS'),\n");
            } elseif ($attributeType == 'boolean') {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->is" . $attributeName . "(),\n");
            } else {
                fwrite($file, "            '" . $attributeName . "' => \$" . ($attributeOwner) . "->get" . $attributeName . "(),\n");
            }
        }
        fwrite($file, "            ]);\n");

        fwrite($file, "    }\n");
        fwrite($file, "}\n");

        // Close the file
        fclose($file);
    }

    /**
     * Generate the code for the delete controller
     *
     * @param string $entityName
     * @param array $attributes
     * @param string $outputDirectory
     */
    public static function makeDeleteControllerMethod($entityName, $outputDirectory, $otherEntities)
    {
        //$outputDirectory = "output/" . ($entityName) . "/controllers";

        // Create a new file
        $file = fopen($outputDirectory . "/Delete" . $entityName . "Controller.php", "w");

        // Write the namespace and class declaration code to the file
        fwrite(
            $file,
            "<?php\n\n"
                . "namespace App\Controller\\" . ($entityName) . ";\n\n"
                . "use App\Entity\\" . $entityName . ";\n"
        );

        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "use App\Entity\\" . $otherEntity . ";\n");
        }

        fwrite(
            $file,
            "use App\Repository\ModuleRepository;\n"
                . "use Symfony\Component\Routing\Annotation\Route;\n"
                . "use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;\n"
                . "use Symfony\Component\HttpFoundation\Request;\n"
                . "use Doctrine\ORM\EntityManagerInterface;\n"
                . "use Symfony\Component\HttpFoundation\Response;\n\n"
                . "class Delete" . $entityName . "Controller extends AbstractController\n"
                . "{\n"

                . "    #[Route('/delete" . $entityName . "', name: 'app_delete_" . ($entityName) . "_by_ID')]\n"
                . "    public function delete" . $entityName . "(EntityManagerInterface \$em, Request \$request, ModuleRepository \$repo): Response\n"
                . "    {\n"
                . "        if (\$request->isMethod('POST')) {\n\n"

        );

        // Get the first attribute name
        //$attributeName = $attributes[0]['name'];
        fwrite($file, "            \$" . "id" . "= \$request->request->get('" . "id" . "');\n\n");

        // Write the code to get the entity from the database
        fwrite($file, "            \$" . ($entityName) . " = \$em->getRepository(" . $entityName . "::class)->findOneBy([\n");
        fwrite($file, "                '" . "id" . "' => \$" . "id" . "\n");
        fwrite($file, "            ]);\n\n");

        // Write the code if the entity is not found
        fwrite($file, "            if (!\$" . ($entityName) . ") {\n");
        fwrite($file, "                throw \$this->createNotFoundException(\n");
        fwrite($file, "                    'No " . ($entityName) . " found for " . "id" . " ' . \$request->request->get('" . "id" . "')\n");
        fwrite($file, "                );\n");
        fwrite($file, "            }\n\n");

        // Get entityName's otherEntities
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "            \$" . ($otherEntity) . " = \$" . ($entityName) . "->get" . ($otherEntity) . "();\n");
        }
        fwrite($file, "\n");

        // Write the code to delete the entity
        fwrite($file, "            \$em->remove(\$" . ($entityName) . ");\n");

        // Remove the associated entities
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "            if (\$" . ($otherEntity) . ") {\n");
            fwrite($file, "                \$em->remove(\$" . ($otherEntity) . ");\n");
            fwrite($file, "            }\n");
        }

        fwrite($file, "            \$em->flush();\n\n");

        fwrite($file, "            return \$this->render(" . "'module/" . ($entityName) . "/delete_" . ($entityName) . ".html.twig', [\n");
        fwrite($file, "            'modules' => \$repo->findAll(),\n");
        fwrite($file, "            '" . "id" . "' => \$" . "id" . ",\n");
        fwrite($file, "            ]);\n");
        fwrite($file, "        }\n\n");

        fwrite($file, "            return \$this->render(" . "'module/" . ($entityName) . "/delete_" . ($entityName) . ".html.twig', [\n");
        fwrite($file, "            'modules' => \$repo->findAll(),\n");
        fwrite($file, "            '" . "id" . "' => '',\n");
        fwrite($file, "            ]);\n");

        fwrite($file, "    }\n\n");

        // Deleting an entity by id
        fwrite($file, "    #[Route('/delete" . $entityName . "/{id}', name: 'app_delete_" . ($entityName) . "')]\n");
        fwrite($file, "    public function delete" . $entityName . "ById(EntityManagerInterface \$em, \$id)\n");
        fwrite($file, "    {\n");
        fwrite($file, "        \$" . ($entityName) . " = \$em->getRepository(" . $entityName . "::class)->find(\$id);\n\n");

        // Write the code if the entity is not found
        fwrite($file, "        if (!\$" . ($entityName) . ") {\n");
        fwrite($file, "            throw \$this->createNotFoundException(\n");
        fwrite($file, "                'No " . ($entityName) . " found for id ' . \$id\n");
        fwrite($file, "            );\n");
        fwrite($file, "        }\n\n");

        // Get the entityName's otherEntities
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "        \$" . ($otherEntity) . " = \$" . ($entityName) . "->get" . ($otherEntity) . "();\n");
        }
        fwrite($file, "\n");

        // Write the code to delete the entity
        fwrite($file, "        \$em->remove(\$" . ($entityName) . ");\n");

        // Remove the associated entities
        foreach ($otherEntities as $otherEntity) {
            fwrite($file, "        if (\$" . ($otherEntity) . ") {\n");
            fwrite($file, "            \$em->remove(\$" . ($otherEntity) . ");\n");
            fwrite($file, "        }\n");
        }

        fwrite($file, "        \$em->flush();\n\n");

        // Write the code to redirect to the list page
        fwrite($file, "        return \$this->redirectToRoute('app_list_" . ($entityName) . "');\n");
        fwrite($file, "    }\n");
        fwrite($file, "}\n");

        // Close the file
        fclose($file);
    }
}
