<?php

namespace App\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MakeEntityService
{

    /*
        * Creates the entity using the make:entity command
        * @param string $entityName
        * @param array $attributes
    */
    public static function createEntity($entityName, $attributes)
    {
        // Escape the entity name
        $entityNameEscaped = escapeshellarg($entityName);

        // Remove the single quotes from the entity name
        $entityNameEscaped = str_replace("'", "", $entityNameEscaped);

        $path = getcwd();
        $path = str_replace("/public", "", $path);

        $process = new Process(['php', $path . '/bin/console', 'make:entity', $entityNameEscaped]);

        try {
            $input = "";

            foreach ($attributes as $attribute) {

                // If the $attribute['entity'] is not empty, skip it
                if (!empty($attribute['entity'])) {
                    continue;
                }


                $attributeName = str_replace("'", "", escapeshellarg($attribute['name']));
                $attributeType = str_replace("'", "", escapeshellarg($attribute['type']));
                $attributeNullable = escapeshellarg($attribute['nullable']);

                if ($attribute['type'] == "string") {
                    $attributeFieldLength = str_replace("'", "", escapeshellarg($attribute['fieldLength']));
                    $input .= $attributeName . "\n" . $attributeType . "\n" . $attributeFieldLength . "\n" . $attributeNullable . "\n";
                } elseif ($attribute['type'] == "decimal") {
                    $attributePrecision = str_replace("'", "", escapeshellarg($attribute['precision']));
                    $attributeScale = str_replace("'", "", escapeshellarg($attribute['scale']));
                    $input .= $attributeName . "\n" . $attributeType . "\n" . $attributePrecision . "\n" . $attributeScale . "\n" . $attributeNullable . "\n";
                } else {
                    $input .= $attributeName . "\n" . $attributeType . "\n" . $attributeNullable . "\n";
                }
            }

            $input .= "\n";

            $process->setInput($input);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }


    public static function createRelations($entityName, $relations)
    {
        // Escape the entity name
        $entityNameEscaped = escapeshellarg($entityName);

        // Remove the single quotes from the entity name
        $entityNameEscaped = str_replace("'", "", $entityNameEscaped);

        $path = getcwd();
        $path = str_replace("/public", "", $path);

        $process = new Process(['php', $path . '/bin/console', 'make:entity', $entityNameEscaped]);

        try {

            $input = "";

            foreach ($relations as $relation) {

                $relationName = str_replace("'", "", escapeshellarg($relation['name']));
                $fieldType = str_replace("'", "", escapeshellarg('relation'));
                $relationEntity = str_replace("'", "", escapeshellarg($relation['entity']));
                $relationType = str_replace("'", "", escapeshellarg($relation['type']));

                if ($relation['type'] == "ManyToOne") {
                    $nullable = str_replace("'", "", escapeshellarg($relation['nullable']));
                    $addProperty = str_replace("'", "", escapeshellarg($relation['addProperty']));
                    if ($addProperty == "yes") {
                        $fieldName = str_replace("'", "", escapeshellarg($relation['fieldName']));
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $nullable . "\n" . $addProperty . "\n" . $fieldName . "\n";
                    } else {
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $nullable . "\n" . $addProperty . "\n";
                    }
                } elseif ($relation['type'] == "OneToMany") {
                    $fieldName = str_replace("'", "", escapeshellarg($relation['fieldName']));
                    $nullable = str_replace("'", "", escapeshellarg($relation['nullable']));
                    if ($nullable == "no") {
                        $orphanRemoval = str_replace("'", "", escapeshellarg($relation['orphanRemoval']));
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $fieldName . "\n" . $nullable . "\n" . $orphanRemoval . "\n";
                    } else {
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $fieldName . "\n" . $nullable . "\n";
                    }
                } elseif ($relation['type'] == "ManyToMany") {
                    $addProperty = str_replace("'", "", escapeshellarg($relation['addProperty']));
                    if ($addProperty == "yes") {
                        $fieldName = str_replace("'", "", escapeshellarg($relation['fieldName']));
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $addProperty . "\n" . $fieldName . "\n";
                    } else {
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $addProperty . "\n";
                    }
                } elseif ($relation['type'] == "OneToOne") {
                    $nullable = str_replace("'", "", escapeshellarg($relation['nullable']));
                    $addProperty = str_replace("'", "", escapeshellarg($relation['addProperty']));
                    if ($addProperty == "yes") {
                        $fieldName = str_replace("'", "", escapeshellarg($relation['fieldName']));
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $nullable . "\n" . $addProperty . "\n" . $fieldName . "\n";
                    } else {
                        $input .= $relationName . "\n" . $fieldType . "\n" . $relationEntity . "\n" . $relationType . "\n" . $nullable . "\n" . $addProperty . "\n";
                    }
                } else {
                    // Do nothing
                    // This should never happen
                }
            }

            $input .= "\n";

            $process->setInput($input);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }

    public static function createInnerEntities($attributes)
    {
        foreach ($attributes as $attribute) {
            try {
                if (!empty($attribute['entity'])) {

                    // Escape the entity name
                    $entityName = $attribute['entity'];
                    $entityName = ucfirst($entityName);
                    $entityName = str_replace("'", "", escapeshellarg($entityName));

                    $path = getcwd();
                    $path = str_replace("/public", "", $path);

                    $process = new Process(['php', $path . '/bin/console', 'make:entity', $entityName]);


                    $input = "";

                    $attributeName = str_replace("'", "", escapeshellarg($attribute['name']));
                    $attributeType = str_replace("'", "", escapeshellarg($attribute['type']));
                    $attributeNullable = escapeshellarg($attribute['nullable']);

                    if ($attribute['type'] == "string") {
                        $attributeFieldLength = str_replace("'", "", escapeshellarg($attribute['fieldLength']));
                        $input .= $attributeName . "\n" . $attributeType . "\n" . $attributeFieldLength . "\n" . $attributeNullable . "\n";
                    } elseif ($attribute['type'] == "decimal") {
                        $attributePrecision = str_replace("'", "", escapeshellarg($attribute['precision']));
                        $attributeScale = str_replace("'", "", escapeshellarg($attribute['scale']));
                        $input .= $attributeName . "\n" . $attributeType . "\n" . $attributePrecision . "\n" . $attributeScale . "\n" . $attributeNullable . "\n";
                    } else {
                        $input .= $attributeName . "\n" . $attributeType . "\n" . $attributeNullable . "\n";
                    }

                    $input .= "\n";

                    $process->setInput($input);
                    $process->run();

                    if (!$process->isSuccessful()) {
                        throw new ProcessFailedException($process);
                    }
                }
            } catch (ProcessFailedException $exception) {
                echo $exception->getMessage();
            }
        }
    }

    /*
        * Makes the migration for the entity
        * @param string $entityName
        * @param array $attributes
    */
    public static function runMigrations()
    {
        $path = getcwd();
        $path = str_replace("/public", "", $path);

        try {
            $process = new Process(['php', $path . '/bin/console', 'make:migration', '--no-interaction']);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }

        try {
            $process = new Process(['php', $path . '/bin/console', 'doctrine:migrations:migrate', '--no-interaction']);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
        }
    }
}
