<?php

namespace App\Service;

class RemoveEntityService
{
    /*
    Delete the controller folder and its contents. If the folder doesn't exist, return false
    */
    public static function deleteController($controllerDirectory)
    {
        // If the folder doesn't exist, return false
        if (!file_exists($controllerDirectory)) {
            return false;
        }

        // Delete the folder and its contents
        $files = glob($controllerDirectory . '/*');
        foreach ($files as $file) {
            is_dir($file) ? self::deleteController($file) : unlink($file);
        }
        rmdir($controllerDirectory);

        return true;
    }

    /*
    Delete the form folder and its contents. If the folder doesn't exist, return false
    */
    public static function deleteForm($twigDirectory)
    {
        // If the folder doesn't exist, return false
        if (!file_exists($twigDirectory)) {
            return false;
        }

        // Delete the folder and its contents
        $files = glob($twigDirectory . '/*');
        foreach ($files as $file) {
            is_dir($file) ? self::deleteForm($file) : unlink($file);
        }
        rmdir($twigDirectory);

        return true;
    }

    /*
    Remove the table from the database
    */
    public static function deleteTable($entityName, $entityManager)
    {
        // If the table doesn't exist, return false
        if (!$entityManager->getConnection()->getSchemaManager()->tablesExist([$entityName])) {
            return false;
        }

        // Drop the table
        $entityManager->getConnection()->getSchemaManager()->dropTable($entityName);

        return true;
    }

    /*
    Delete the entity file. If the file doesn't exist, return false
    */

    public static function deleteEntity($entityDirectory)
    {
        // If the file doesn't exist, return false
        if (!file_exists($entityDirectory)) {
            return false;
        }

        // Delete the file
        unlink($entityDirectory);

        return true;
    }

    /*
    Delete the repository file. If the file doesn't exist, return false
    */

    public static function deleteRepository($repositoryDirectory)
    {
        // If the file doesn't exist, return false
        if (!file_exists($repositoryDirectory)) {
            return false;
        }

        // Delete the file
        unlink($repositoryDirectory);

        return true;
    }
}
