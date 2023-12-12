<?php

namespace App\Service;

class MakeFormService
{
    /*
        * Makes the create form for the entity
        * @param string $entityName
        * @param array $attributes
        * @param string $outputDirectory
    */
    public static function makeCreateForm($entityName, $attributes, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/twigs";

        // Create the output directory if it doesn't exist
        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        // Create a new file
        $file = fopen($outputDirectory . "/create_" . ($entityName) . ".html.twig", "w");

        // Write the code to the file
        fwrite(
            $file,
            "{% extends 'base.html.twig' %}\n\n"
                . "{% block title %}Create " . ($entityName) . "{% endblock %}\n\n"
                . "{% block body %}\n\n"
                . "    <div class='flex flex-wrap -mx-3 justify-left'>" . "\n"
                . "        <div class='w-full md:w-1/2 px-3 mb-6 md:mb-0'>" . "\n"
                . "            <div class='relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border'>" . "\n"
                . "                <div class='p-4'>" . "\n"
                . "    <h3>Please enter the " . ($entityName) . " information:</h3>\n\n"
                . "    <form method='post' action='{{ path('app_create_" . ($entityName) . "') }}'>\n\n"
        );

        foreach ($attributes as $attribute) {
            $attributeName = $attribute['name'];
            if ($attribute['nullable'] == 'No') {
                $required = 'required';
            } else {
                $required = '';
            }
            fwrite($file, "        <label for='" . ($attributeName) . "'>" . ($attributeName) . "(" . $attribute['type'] . "):</label>\n");
            if ($attribute['type'] == 'date') {
                fwrite($file, "        <input type='date' name='" . ($attributeName) . "' class='focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none' value='{{ " . ($attributeName) . " }}'" . ($required) . " >\n");
            } elseif ($attribute['type'] == 'datetime' || $attribute['type'] == 'datetimetz') {
                fwrite($file, "        <input type='datetime-local' name='" . ($attributeName) . "' class='focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            } elseif ($attribute['type'] == 'time') {
                fwrite($file, "        <input type='time' name='" . ($attributeName) . "' class='focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            } else {
                fwrite($file, "        <input type='text' name='" . ($attributeName) . "' class='focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            }
            fwrite($file, "        <br><br>\n\n");
        }

        fwrite($file, "        <button class='inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25' type='submit'>Submit</button>\n\n");

        fwrite($file, "    </form>\n\n");
        fwrite($file, "                </div></div></div></div>\n");

        fwrite($file, "{% endblock %}\n");

        // Close the file
        fclose($file);
    }

    /*
        * Makes the read form for the entity
        * @param string $entityName
        * @param array $attributes
        * @param string $outputDirectory
    */
    public static function makeReadForm($entityName, $attributes, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/twigs";

        // Create a new file
        $file = fopen($outputDirectory . "/read_" . ($entityName) . ".html.twig", "w");

        // Write the code to the file
        fwrite(
            $file,
            "{% extends 'base.html.twig' %}\n\n"
                . "{% block title %}Read " . ($entityName) . "{% endblock %}\n\n"
                . "{% block body %}\n"
                . "    <div class='flex flex-wrap -mx-3'>\n"
                . "        <div class='flex-none w-full max-w-full px-3'>\n"
                . "            <div class='relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border'>\n"
                . "                <div class='p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent'>\n"
                . "                    <h6>" . ($entityName) . "</h6>\n"
                . "                </div>\n"
                . "                <div class='flex-auto px-0 pt-0 pb-2'>\n"
                . "                    <div class='p-0 overflow-x-auto'>\n"
                . "                        <table class='items-center w-full mb-0 align-top border-gray-200 text-slate-500'>\n"
                . "                            <thead class='align-bottom'>\n"
                . "                                <tr>\n"
        );

        foreach ($attributes as $attribute) {
            fwrite($file, "                                    <th class='px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70'>\n");
            fwrite($file, "                                        " . ($attribute['name']) . "\n");
            fwrite($file, "                                    </th>\n");
        }

        fwrite($file, "                                </tr>\n");
        fwrite($file, "                            </thead>\n");

        fwrite($file, "                            <tbody>\n");
        fwrite($file, "                                {% for element in " . ($entityName) . " %}\n");
        fwrite($file, "                                    <option value='{{ element.ID }}'>{{ element.ID }}</option>\n");
        fwrite($file, "                                <tr>\n");
        fwrite($file, "                                    <td class='p-2 align-middle bg-transparent border-b shadow-transparent'>\n");
        fwrite($file, "                                        <a class='text-xs font-semibold leading-tight text-slate-400'>{{ element.ID }}</a>\n");
        fwrite($file, "                                    </td>\n");

        foreach ($attributes as $attribute) {
            if ($attribute['entity'] == '') {
                $attributeOwner = "element";
            } else {
                $attributeOwner = "element" . "." . ($attribute['entity']);
            }

            fwrite($file, "                                    <td class='p-2 align-middle bg-transparent border-b shadow-transparent'>\n");
            if ($attribute['type'] == 'date') {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | format_date() }}</span>\n");
            } elseif ($attribute['type'] == 'datetime' || $attribute['type'] == 'datetimetz') {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | format_datetime() }}</span>\n");
            } elseif ($attribute['type'] == 'time') {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | format_datetime('none', 'short') }}</span>\n");
            } elseif ($attribute['type'] == 'dateinterval') {
                // Maximum options: <td>{{ element.datzinterval | date("%Y year(s), %M month(s), %D day(s), %H hour(s), %M minute(s), %S second(s), %F microsecond(s)") }}</td>
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | date(\"%Y year(s), %M month(s), %D day(s), %H hour(s), %M minute(s), %S second(s), %F microsecond(s)\") }}</span>\n");
            } elseif ($attribute['type'] == 'simple_array') {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | join(', ') }}</span>\n");
            } elseif ($attribute['type'] == 'json') {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " | json_encode() }}</span>\n");
            } else {
                fwrite($file, "                                        <span class='text-xs font-semibold leading-tight text-slate-400'>{{" . $attributeOwner . "." . ($attribute['name']) . " }}</span>\n");
            }
            fwrite($file, "                                    </td>\n");
        }

        fwrite($file, "                                    <td class='p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent'>\n");
        fwrite($file, "											<div class='dropdown'>\n");

        fwrite($file, "                                               <a href='{{ path('app_read_" . ($entityName) . "', {'id': element.ID}) }}' class='inline-block px-6 py-3 mb-0 text-xs font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none leading-pro ease-soft-in bg-150 tracking-tight-soft bg-x-25 text-slate-400' data-bs-toggle='dropdown'>\n");
        fwrite($file, "                                                   <i class='text-xs leading-tight fa fa-ellipsis-v'></i>\n");
        fwrite($file, "                                               </a>\n");

        fwrite($file, "                                                <ul class='dropdown-menu ml-auto text-right'>\n");
        fwrite($file, "                                                    <li>\n");
        fwrite($file, "                                                        <a href='{{ path('app_update_" . ($entityName) . "', {'id': element.ID}) }}' class='inline-block px-4 py-3 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border-0 rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in bg-150 hover:scale-102 active:opacity-85 bg-x-25 text-slate-700'>\n");
        fwrite($file, "                                                            <i class='mr-2 fas fa-pencil-alt text-slate-700' aria-hidden='true'></i>Edit</a>\n");
        fwrite($file, "                                                    </li>\n");

        fwrite($file, "                                                    <li>\n");
        fwrite($file, "                                                        <a href='{{ path('app_delete_" . ($entityName) . "', {'id': element.ID}) }}' class='relative z-10 inline-block px-4 py-3 mb-0 font-bold text-center text-transparent uppercase align-middle transition-all border-0 rounded-lg shadow-none cursor-pointer leading-pro text-xs ease-soft-in bg-150 bg-gradient-to-tl from-red-600 to-rose-400 hover:scale-102 active:opacity-85 bg-x-25 bg-clip-text' onclick='return confirm('Are you sure you want to delete this" . ($entityName) . "?');'>\n");
        fwrite($file, "                                                            <i class='mr-2 far fa-trash-alt bg-150 bg-gradient-to-tl from-red-600 to-rose-400 bg-x-25 bg-clip-text'></i>Delete</a>\n");
        fwrite($file, "                                                    </li>\n");

        fwrite($file, "                                                </ul>\n");
        fwrite($file, "                                            </div>\n");
        fwrite($file, "                                        </td>\n");
        fwrite($file, "                                    </tr>\n");
        fwrite($file, "                                {% endfor %}\n");
        fwrite($file, "                            </tbody>\n");
        fwrite($file, "                        </table>\n");
        fwrite($file, "                    </div>\n");
        fwrite($file, "                </div>\n");
        fwrite($file, "            </div>\n");
        fwrite($file, "        </div>\n");
        fwrite($file, "    </div>\n");
        fwrite($file, "{% endblock %}\n");

        // Close the file
        fclose($file);
    }

    /*
        * Makes the update form for the entity
        * @param string $entityName
        * @param array $attributes
        * @param string $outputDirectory
    */
    public static function makeUpdateForm($entityName, $attributes, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/twigs";

        // Create a new file
        $file = fopen($outputDirectory . "/update_" . ($entityName) . ".html.twig", "w");

        // Write the code to the file
        fwrite(
            $file,
            "{% extends 'base.html.twig' %}\n\n"
                . "{% block body %}\n\n"
                . "{% block title %}Update " . ($entityName) . "{% endblock %}\n\n"
                . "    <h3>Please enter the " . ($entityName) . " information:</h3>\n\n"
                . "    <!-- Get the id from the url -->\n"
                . "    {% set id = app.request.get('id') %}\n\n"
                . "    <form method='post' action='{{ path('app_update_" . ($entityName) . "', {'id': id}) }}'>\n"
        );

        foreach ($attributes as $attribute) {
            $attributeName = $attribute['name'];
            if ($attribute['nullable'] == 'No') {
                $required = 'required';
            } else {
                $required = '';
            }
            fwrite($file, "        <label for='" . ($attributeName) . "'>" . ($attributeName) . "(" . $attribute['type'] . "):</label>\n");
            if ($attribute['type'] == 'date') {
                fwrite($file, "        <input type='date' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            } elseif ($attribute['type'] == 'datetime' || $attribute['type'] == 'datetimetz') {
                fwrite($file, "        <input type='datetime-local' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            } elseif ($attribute['type'] == 'time') {
                fwrite($file, "        <input type='time' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            } elseif ($attribute['type'] == 'simple_array') {
                fwrite($file, "        <input type='text' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " | join(', ') }}' " .  $required . ">\n");
            } elseif ($attribute['type'] == 'json') {
                fwrite($file, "        <input type='text' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " | json_encode() }}' " .  $required . ">\n");
            } else {
                fwrite($file, "        <input type='text' name='" . ($attributeName) . "' value='{{ " . ($attributeName) . " }}' " .  $required . ">\n");
            }
            fwrite($file, "        <br><br>\n\n");
        }

        fwrite($file, "        <button type='submit'>Submit</button>\n\n");

        fwrite($file, "    </form>\n\n");

        fwrite($file, "{% endblock %}\n");

        // Temporary fix for encountered error
        fwrite($file, "{% block stylesheets %}\n");
        fwrite($file, "    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet'/>\n");
        fwrite($file, "    <!-- Font Awesome Icons -->\n");
        fwrite($file, "    <script src='https://kit.fontawesome.com/42d5adcbca.js' crossorigin='anonymous'></script>\n");
        fwrite($file, "    <!-- Nucleo Icons -->\n");
        fwrite($file, "    <link href='../assets/css/nucleo-icons.css' rel='stylesheet'/>\n");
        fwrite($file, "    <link href='../assets/css/nucleo-svg.css' rel='stylesheet'/>\n");
        fwrite($file, "    <!-- Popper -->\n");
        fwrite($file, "    <script src='https://unpkg.com/@popperjs/core@2'></script>\n");
        fwrite($file, "    <!-- Main Styling -->\n");
        fwrite($file, "    <link href='../assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5' rel='stylesheet'/>\n");
        fwrite($file, "    <!-- Bootstrap CSS -->\n");
        fwrite($file, "    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/"
            . "bootstrap.min.css' rel='stylesheet'>\n");
        fwrite($file, "{% endblock %}\n\n");

        // Temporary fix for encountered error
        fwrite($file, "{% block javascripts %}\n");
        fwrite($file, "    <!-- plugin for charts  -->\n");
        fwrite($file, "    <script src='../assets/js/plugins/chartjs.min.js' async></script>\n");
        fwrite($file, "    <!-- plugin for scrollbar  -->\n");
        fwrite($file, "    <script src='../assets/js/plugins/perfect-scrollbar.min.js' async></script>\n");
        fwrite($file, "    <!-- github button -->\n");
        fwrite($file, "    <script async defer src='https://buttons.github.io/buttons.js'></script>\n");
        fwrite($file, "    <!-- main script file  -->\n");
        fwrite($file, "    <script src='../assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5' async></script>\n");
        fwrite($file, "    <!-- Bootstrap JS (Popper.js and Bootstrap.js) -->\n");
        fwrite($file, "    <script src='https://cdn.jsdelivr.net/npm/"
            . "@popperjs/core@2.9.1/dist/umd/popper.min.js'></script>\n");
        fwrite($file, "    <script src='https://cdn.jsdelivr.net/npm/"
            . "bootstrap@5.3.0/dist/js/bootstrap.min.js'></script>\n");
        fwrite($file, "{% endblock %}\n\n");

        // Close the file
        fclose($file);
    }

    /*
        * Makes the delete form for the entity
        * @param string $entityName
        * @param array $attributes
        * @param string $outputDirectory
    */
    public static function makeDeleteForm($entityName, $outputDirectory)
    {
        //$outputDirectory = "output/" . ($entityName) . "/twigs";

        // Create a new file
        $file = fopen($outputDirectory . "/delete_" . ($entityName) . ".html.twig", "w");

        // Write the code to the file
        fwrite(
            $file,
            "{% extends 'base.html.twig' %}\n\n"
                . "{% block body %}\n\n"
                . "{% block title %}Delete " . ($entityName) . "{% endblock %}\n\n"
                . "    <h3>Please enter the " . ($entityName) . " information:</h3>\n\n"
                . "    <form method='post' action='{{ path('app_delete_" . ($entityName) . "_by_ID') }}'>\n\n"
        );

        //Get first attribute
        //$attributeName = $attributes[0]['name'];
        //$attributeType = $attributes[0]['type'];

        fwrite($file, "        <label for='" . "id" . "'>" . "id" . "(" . "int" . "):</label>\n");
        fwrite($file, "        <input type='text' name='" . "id" . "' class='focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none' value='{{ " . "id" . " }}' required>\n");
        fwrite($file, "        <br><br>\n\n");


        fwrite($file, "        <button class='inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25' type='submit'>Submit</button>\n\n");

        fwrite($file, "    </form>\n\n");

        fwrite($file, "{% endblock %}\n");

        // Close the file
        fclose($file);
    }
}
