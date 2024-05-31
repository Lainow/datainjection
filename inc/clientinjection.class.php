<?php

use Glpi\Application\View\TemplateRenderer;

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

class PluginDatainjectionClientInjection
{
    public static $rightname = "plugin_datainjection_use";

    public const STEP_UPLOAD  = 0;
    public const STEP_PROCESS = 1;
    public const STEP_RESULT  = 2;

    //Injection results
    private $results = [];

    //Model used for injection
    private $model;

    //Overall injection results
    private $global_results;


    /**
    * Print a good title for group pages
    *
    *@return void nothing (display)
   **/
    public function title(): void
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $buttons =  [];
        $title   = "";

        if (Session::haveRight(static::$rightname, UPDATE)) {
            $url           = Toolbox::getItemTypeSearchURL('PluginDatainjectionModel');
            $buttons[$url] = PluginDatainjectionModel::getTypeName();
            $title         = "";
            Html::displayTitle(
                Plugin::getWebDir('datainjection') . "/pics/datainjection.png",
                PluginDatainjectionModel::getTypeName(),
                $title,
                $buttons
            );
        }
    }


    public function showForm($ID, $options = [])
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $models = PluginDatainjectionModel::getModels(
            Session::getLoginUserID(),
            'name',
            $_SESSION['glpiactive_entity'],
            false
        );

        if (count($models) < 0) {
            $text = __('No model currently available', 'datainjection');

            if (Session::haveRight('plugin_datainjection_model', CREATE)) {
                $text = sprintf(
                    __('%1$s %2$s'),
                    $text . ". ",
                    sprintf(
                        __('%1$s: %2$s'),
                        __(
                            'You can start the model creation by hitting the button',
                            'datainjection'
                        ),
                        PluginDatainjectionModel::getTypeName()
                    )
                );
            }
        }

        if (PluginDatainjectionSession::getParam('models_id')) {
            $p['models_id'] = PluginDatainjectionSession::getParam('models_id');

            switch (PluginDatainjectionSession::getParam('step')) {
                case self::STEP_UPLOAD:
                     $url = Plugin::getWebDir('datainjection') . "/ajax/dropdownSelectModel.php";
                     Ajax::updateItem("span_injection", $url, $p);
                    break;

                case self::STEP_RESULT:
                    $url = Plugin::getWebDir('datainjection') . "/ajax/results.php";
                    Ajax::updateItem("span_injection", $url, $p);
                    break;
            }
        }

        TemplateRenderer::getInstance()->display('@datainjection/clientinjection.html.twig', [
            'form_url'  => Toolbox::getItemTypeFormURL(__CLASS__),
            'models'    => $models,
            'content'   => $text ?? "",
            'url'       => $url ?? "",
        ]);
    }


    /**
    * @param $options array
   **/
    public static function showUploadFileForm($options = [])
    {

        $add_form = (isset($options['add_form']) && $options['add_form']);
        $confirm  = (isset($options['confirm']) ? $options['confirm'] : false);
        $url      = (($confirm == 'creation') ? Toolbox::getItemTypeFormURL('PluginDatainjectionModel')
                                         : Toolbox::getItemTypeFormURL(__CLASS__));

        $size = sprintf(__(' (%1$s)'), Document::getMaxUploadSize());

        $alert = "";
        if ($confirm) {
            if ($confirm == 'creation') {
                $message = __s('Warning : existing mapped column will be overridden', 'datainjection');
            } else {
                $message = __s(
                    "Watch out, you're about to inject data into GLPI. Are you sure you want to do it ?",
                    'datainjection'
                );
            }
            $alert = "OnClick='return window.confirm(\"$message\");'";
        }
        if (!isset($options['submit'])) {
            $options['submit'] = __('Launch the import', 'datainjection');
        }

        TemplateRenderer::getInstance()->display('@datainjection/uploadfile.html.twig', [
            'form_url'  => $url,
            'size'      => $size,
            'add_form'  => $add_form,
            'options'   => $options,
            'alert'     => $alert,
            'cancel_bt' => _sx('button', 'Cancel'),
        ]);
    }


    /**
    * @param $model        PluginDatainjectionModel object
    * @param $entities_id
   **/
    public static function showInjectionForm(PluginDatainjectionModel $model, $entities_id)
    {

        if (!PluginDatainjectionSession::getParam('infos')) {
            PluginDatainjectionSession::setParam('infos', []);
        }
        TemplateRenderer::getInstance()->display('@datainjection/injection.html.twig', [
            'model_name' => $model->fields['name'],
        ]);
        self::processInjection($model, $entities_id);
    }


    /**
    * @param $model        PluginDatainjectionModel object
    * @param $entities_id
   **/
    public static function processInjection(PluginDatainjectionModel $model, $entities_id)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

       // To prevent problem of execution time during injection
        ini_set("max_execution_time", "0");

       // Disable recording each SQL request in $_SESSION
        $CFG_GLPI["debug_sql"] = 0;

        $nblines         = PluginDatainjectionSession::getParam('nblines');
        $clientinjection = new PluginDatainjectionClientInjection();

       //New injection engine
        $engine = new PluginDatainjectionEngine(
            $model,
            PluginDatainjectionSession::getParam('infos'),
            $entities_id
        );
        $backend = $model->getBackend();
        $model->loadSpecificModel();

       //Open CSV file
        $backend->openFile();

        $index = 0;

       //Read CSV file
        $line = $backend->getNextLine();

       //If header is present, then get the second line
        if ($model->getSpecificModel()->isHeaderPresent()) {
            $line = $backend->getNextLine();
        }

       //While CSV file is not EOF
        $prev = '';
        $deb  = time();
        while ($line != null) {
            //Inject line
            $injectionline              = $index + ($model->getSpecificModel()->isHeaderPresent() ? 2 : 1);
            $clientinjection->results[] = $engine->injectLine($line[0], $injectionline);

            $pos = number_format($index * 100 / $nblines, 1);
            if ($pos != $prev) {
                $prev = $pos;
                $fin  = time() - $deb;
                //TODO yllen
                Html::changeProgressBarPosition(
                    $index,
                    $nblines,
                    sprintf(
                        __('%1$s (%2$s)'),
                        sprintf(
                            __(
                                'Injection of the file... %d%%',
                                'datainjection'
                            ),
                            $pos
                        ),
                        Html::timestampToString(time() - $deb, true)
                    )
                );
            }
            $line = $backend->getNextLine();
            $index++;
        }

         //EOF : change progressbar to 100% !
         Html::changeProgressBarPosition(
             100,
             100,
             sprintf(
                 __('%1$s (%2$s)'),
                 __('Injection finished', 'datainjection'),
                 Html::timestampToString(time() - $deb, true)
             )
         );

         // Restore
         $CFG_GLPI["debug_sql"] = 1;

         //Close CSV file
         $backend->closeFile();

         //Delete CSV file
         $backend->deleteFile();

         //Change step
         $_SESSION['datainjection']['step'] = self::STEP_RESULT;

         //Display results form
         PluginDatainjectionSession::setParam('results', json_encode($clientinjection->results));
         PluginDatainjectionSession::setParam('error_lines', json_encode($engine->getLinesInError()));
         $p['models_id'] = $model->fields['id'];
         $p['nblines']   = $nblines;

         unset($_SESSION['datainjection']['go']);

         $url = Plugin::getWebDir('datainjection') . "/ajax/results.php";
         Ajax::updateItem("span_injection", $url, $p);
    }


    /**
    * to be used instead of  to reduce memory usage
    * execute stripslashes in place (no copy)
    *
    * @param $value array of value
    */
    public static function stripslashes_array(&$value) // phpcs:ignore
    {

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                self::stripslashes_array($value[$key]);
            }
        } elseif (!is_null($value)) {
            $value = stripslashes($value);
        }
    }


    /**
    * @param $model  PluginDatainjectionModel object
   **/
    public static function showResultsForm(PluginDatainjectionModel $model)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $results     = json_decode(PluginDatainjectionSession::getParam('results'), true);
        self::stripslashes_array($results);
        $error_lines = json_decode(PluginDatainjectionSession::getParam('error_lines'), true);
        self::stripslashes_array($error_lines);

        $ok = true;

        foreach ($results as $result) {
            if ($result['status'] != PluginDatainjectionCommonInjectionLib::SUCCESS) {
                $ok = false;
                break;
            }
        }

        $di_base_url = Plugin::getWebDir('datainjection');

        $url = "$di_base_url/front/popup.php?popup=log&amp;models_id=" . $model->fields['id'];

        $plugin = new Plugin();
        TemplateRenderer::getInstance()->display('@datainjection/result.html.twig', [
            'url'           => html_entity_decode($url),
            'di_base_url'   => $di_base_url,
            'ok'            => $ok,
            'pdf_activated' => $plugin->isActivated('pdf'),
            'model'         => $model->fields,
            'error'         => !empty($error_lines),
        ]);
    }


    public static function exportErrorsInCSV()
    {

        $error_lines = json_decode(PluginDatainjectionSession::getParam('error_lines'), true);
        self::stripslashes_array($error_lines);

        if (!empty($error_lines)) {
            $model = unserialize(PluginDatainjectionSession::getParam('currentmodel'));
            $file  = PLUGIN_DATAINJECTION_UPLOAD_DIR . PluginDatainjectionSession::getParam('file_name');

            $mappings = $model->getMappings();
            $tmpfile  = fopen($file, 'w');

           //If headers present
            if ($model->getBackend()->isHeaderPresent()) {
                $headers = PluginDatainjectionMapping::getMappingsSortedByRank($model->fields['id']);
                fputcsv($tmpfile, $headers, $model->getBackend()->getDelimiter());
            }

           //Write lines
            foreach ($error_lines as $line) {
                fputcsv($tmpfile, $line, $model->getBackend()->getDelimiter());
            }
            fclose($tmpfile);

            $name = "Error-" . PluginDatainjectionSession::getParam('file_name');
            $name = str_replace(' ', '', $name);
            header('Content-disposition: attachment; filename=' . $name);
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: fichier');
            header('Content-Length: ' . filesize($file));
            header('Pragma: no-cache');
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            readfile($file);
            unlink($file);
        }
    }
}
