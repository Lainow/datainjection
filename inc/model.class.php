<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
*/

class PluginDatainjectionModel extends CommonDBTM {

   //Store mappings informations
   private $mappings;

   //Store backend used to collect informations
   private $backend;

   //Store informations related to the model
   protected $infos;

   //Do history (CommonDBTM)
   public $dohistory = true;

   //Store specific backend parameters
   public $specific_model;

   //Data to inject
   public $injectionData = false;

   //Store field of type 'multline_text' several times mapped
   public $severaltimes_mapped = array();

   //Port unicity constants
   const UNICITY_NETPORT_LOGICAL_NUMBER            = 0;
   const UNICITY_NETPORT_NAME                      = 1;
   const UNICITY_NETPORT_MACADDRESS                = 2;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME       = 3;
   const UNICITY_NETPORT_LOGICAL_NUMBER_MAC        = 4;
   const UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC   = 5;

   //Private or public model
   const MODEL_PRIVATE                             = 1;
   const MODEL_PUBLIC                              = 0;

   //Step constants
   const INITIAL_STEP                              = 1;
   const FILE_STEP                                 = 2;
   const MAPPING_STEP                              = 3;
   const OTHERS_STEP                               = 4;
   const READY_TO_USE_STEP                         = 5;

   const PROCESS                                   = 0;
   const CREATION                                  = 1;

   function __construct()
   {
      $this->mappings = new PluginDatainjectionMappingCollection;
      $this->infos = new PluginDatainjectionInfoCollection;
   }

   function canCreate() {
      return plugin_datainjection_haveRight('model','w');
   }

   function canView() {
      return plugin_datainjection_haveRight('model','r');
   }

   function saveMappings() {
      $this->mappings->saveAllMappings($this->fields['id']);
   }

   //Loading methods
   function loadMappings() {
      $this->mappings->load($this->fields['id']);
   }

   //Loading methods
   function loadInfos() {
      $this->infos->load($this->fields['id']);
   }

   //---- Getters -----//
   function getMandatoryMappings()
   {
      return $this->mappings->getMandatoryMappings();
   }
   //---- Getters -----//
   function getMappings()
   {
      return $this->mappings->getAllMappings();
   }

   function getInfos()
   {
      return $this->infos->getAllInfos();
   }

   function getBackend()
   {
      return $this->backend;
   }

   function getMappingByName($name)
   {
      return $this->mappings->getMappingByName($name);
   }

   function getMappingByRank($rank)
   {
      return $this->mappings->getMappingByRank($rank);
   }

   function getMappingByValue($value)
   {
      return $this->mappings->getMappingsByField("value",$value);
   }

   function getModelInfos()
   {
      return $this->fields;
   }

   function getModelName()
   {
      return $this->fields["name"];
   }

   function getFiletype()
   {
      return $this->fields["filetype"];
   }

   function getModelComments()
   {
      return $this->fields["comment"];
   }

   function getBehaviorAdd()
   {
      return $this->fields["behavior_add"];
   }

   function getBehaviorUpdate()
   {
      return $this->fields["behavior_update"];
   }

   function getModelID()
   {
      return $this->fields["id"];
   }

   function getItemtype()
   {
      return $this->fields["itemtype"];
   }

   function getEntity()
   {
      return $this->fields["entities_id"];
   }

   function getCanAddDropdown()
   {
      return $this->fields["can_add_dropdown"];
   }

   function getCanOverwriteIfNotEmpty()
   {
      return $this->fields["can_overwrite_if_not_empty"];
   }

   function getUserID()
   {
      return $this->fields["users_id"];
   }

   function getPerformNetworkConnection()
   {
      return $this->fields["perform_network_connection"];
   }

   function getDateFormat()
   {
      return $this->fields["date_format"];
   }

   function getFloatFormat()
   {
      return $this->fields["float_format"];
   }

   function getRecursive()
   {
      return $this->fields["is_recursive"];
   }

   function getPrivate()
   {
      return $this->fields["is_private"];
   }

   function getPortUnicity()
   {
      return $this->fields["port_unicity"];
   }

   //---- Save -----//
   function setModelType($type)
   {
      $this->fields["filetype"] = $type;
   }

   function setName($name)
   {
      $this->fields["name"] = $name;
   }

   function setComments($comments)
   {
      $this->fields["comment"] = $comments;
   }

   function setBehaviorAdd($add)
   {
      $this->fields["behavior_add"] = $add;
   }

   function setBehaviorUpdate($update)
   {
      $this->fields["behavior_update"] = $update;
   }

   function setModelID($ID)
   {
      $this->fields["id"] = $ID;
   }

   function setMappings($mappings)
   {
      $this->mappings = $mappings;
   }

   function setInfos($infos)
   {
      $this->infos = $infos;
   }

   function setBackend($backend)
   {
      $this->backend = $backend;
   }

   function setDeviceType($device_type)
   {
      $this->fields["itemtype"] = $device_type;
   }

   function setEntity($entity)
   {
      $this->fields["entities_id"] = $entity;
   }

   function setCanAddDropdown($canadd)
   {
      $this->fields["can_add_dropdown"] = $canadd;
   }

   function setCanOverwriteIfNotEmpty($canoverwrite)
   {
      $this->fields["can_overwrite_if_not_empty"] = $canoverwrite;
   }

   function setPrivate($private)
   {
      $this->fields["is_private"] = $private;
   }

   function setUserID($user)
   {
      $this->fields["users_id"] = $user;
   }

   function setDateFormat($df)
   {
      $this->fields["date_format"] = $df;
   }

   function setFloatFormat($ff)
   {
      $this->fields["float_format"] = $ff;
   }

   function setPerformNetworkConnection($perform)
   {
      $this->fields["perform_network_connection"] = $perform;
   }

   function setRecursive($recursive)
   {
      $this->fields["is_recursive"] = $recursive;
   }

   function setSpecificModel($specific_model) {
      $this->specific_model = $specific_model;
   }

   function setPortUnicity($unicity)
   {
      $this->fields["port_unicity"]=$unicity;
   }

   function getSpecificModel() {
      return  $this->specific_model;
   }

   static function dropdown($options = array()) {
      global $CFG_GLPI;

      $models = self::getModels(getLoginUserID(),'name',$_SESSION['glpiactive_entity'],false);
      $models[0] = '-----';
      $p = array('models_id' => '__VALUE__');
      if (isset($_SESSION['glpi_plugin_datainjection_models_id'])) {
         $value = $_SESSION['glpi_plugin_datainjection_models_id'];
      }
      else {
         $value = 0;
      }
      $rand = Dropdown::showFromArray('models',$models,array('value'=>$value));
      $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownSelectModel.php";
      ajaxUpdateItemOnSelectEvent("dropdown_models$rand",
                                  "span_injection",
                                  $url,$p);
   }

   static function getModels($user_id, $order = "name", $entity = -1, $can_write = false) {
      global $DB;

      $models = array ();
      $query = "SELECT `id`, `name` FROM `glpi_plugin_datainjection_models` WHERE ";
      $query.= "`step`='".self::READY_TO_USE_STEP."' ";
      $query.= "AND (`is_private`=" . PluginDatainjectionModel::MODEL_PUBLIC;
      $query.= getEntitiesRestrictRequest(" AND",
                                          "glpi_plugin_datainjection_models",
                                          "entities_id", $entity,true) . ")";
      $query.= " OR (`is_private`='" . PluginDatainjectionModel::MODEL_PRIVATE;
      $query.= "' AND `users_id`='$user_id') ";
      $query.= " ORDER BY `entities_id`, " . ($order == "`name`" ? "`name`" : $order);

      foreach ($DB->request($query) as $data) {
         $models[$data['id']] = $data['name'];
      }

      return $models;
   }

   //Standard functions
   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG["datainjection"]["model"][0];

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[2]['table']        = $this->getTable();
      $tab[2]['field']        = 'id';
      $tab[2]['linkfield']    = '';
      $tab[2]['name']         = $LANG['common'][2];

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'behavior_add';
      $tab[3]['linkfield'] = '';
      $tab[3]['name']      = $LANG["datainjection"]["model"][6];
      $tab[3]['datatype'] = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'behavior_update';
      $tab[4]['linkfield'] = '';
      $tab[4]['name']      = $LANG["datainjection"]["model"][7];
      $tab[4]['datatype'] = 'bool';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'itemtype';
      $tab[5]['linkfield'] = '';
      $tab[5]['name']      = $LANG["common"][17];
      $tab[5]['datatype'] = 'itemtypename';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'can_add_dropdown';
      $tab[6]['linkfield'] = '';
      $tab[6]['name']      = $LANG["datainjection"]["model"][8];
      $tab[6]['datatype'] = 'bool';

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'date_format';
      $tab[7]['linkfield'] = 'date_format';
      $tab[7]['name']      = $LANG["datainjection"]["model"][21];
      $tab[7]['datatype'] = 'text';

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'float_format';
      $tab[8]['linkfield'] = 'float_format';
      $tab[8]['name']      = $LANG["datainjection"]["model"][28];
      $tab[8]['datatype'] = 'text';

      $tab[9]['table']     = $this->getTable();
      $tab[9]['field']     = 'perform_network_connection';
      $tab[9]['linkfield'] = 'perform_network_connection';
      $tab[9]['name']      = $LANG["datainjection"]["model"][20];
      $tab[9]['datatype'] = 'bool';

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'port_unicity';
      $tab[10]['linkfield'] = 'port_unicity';
      $tab[10]['name']      = $LANG["datainjection"]["mappings"][7];
      $tab[10]['datatype'] = 'text';

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = $LANG['common'][25];
      $tab[16]['datatype']  = 'text';


      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['linkfield'] = 'entities_id';
      $tab[80]['name']      = $LANG['entity'][0];

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = $LANG['entity'][9];
      $tab[86]['datatype']  = 'bool';
      return $tab;
   }

   function showForm($ID, $options = array()) {
      global $DB,$LANG;
      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
         echo "<input type='hidden' name='step' value='1'>";
      }
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";

      echo "<input type='hidden' name='users_id' value='".getLoginUserID()."'>";
      echo "<td>".$LANG['common'][16].": </td>";
      echo "<td>";
      autocompletionTextField($this,"name");
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][18].": </td>";
      echo "<td>";
      Dropdown::showYesNo("is_private",$this->fields['is_private']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][6].": </td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_add",$this->fields['behavior_add']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][7].": </td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_update",$this->fields['behavior_update']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][4].": </td>";
      echo "<td>";
      if ($this->fields['step'] == '' || $this->fields['step'] == self::INITIAL_STEP) {
         //Get only the primary types
         PluginDatainjectionInjectionType::dropdown($this->fields['itemtype'],true);
      }
      else {
         $itemtype = new $this->fields['itemtype'];
         echo $itemtype->getTypeName();
      }

      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][12].": </td>";
      echo "<td>";
      Dropdown::showYesNo("can_overwrite_if_not_empty",$this->fields['can_overwrite_if_not_empty']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][5].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownFileTypes($this->fields['filetype']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][8].": </td>";
      echo "<td>";
      Dropdown::showYesNo("can_add_dropdown",$this->fields['can_add_dropdown']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td colspan='3' class='middle'>";
      echo "<textarea cols='45' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['state'][0].": </td>";
      echo "<td>";
      echo $this->getStatusLabel();
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

   function showAdvancedForm($ID, $options = array()) {
      global $DB,$LANG;
      $candedit = $this->check($ID,'r');

      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'><th colspan='4'>".$LANG["datainjection"]["model"][15]."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][28].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownFloatFormat($this->fields['float_format']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][21].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownDateFormat($this->fields['date_format']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG["datainjection"]["model"][20].": </td>";
      echo "<td>";
      Dropdown::showYesNo("perform_network_connection",$this->fields['perform_network_connection']);
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["mappings"][7].": </td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownPortUnicity($this->fields['port_unicity']);
      echo "</td>";
      echo "</tr>";
      $this->showFormButtons(array('candel'=>false));

      return true;
   }

   function showValidationForm() {
      global $LANG;
      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'><th colspan='4'>".$LANG["datainjection"]["model"][37]."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "<input type='submit' class='submit'
                                 name='validate' value='".$LANG["datainjection"]["model"][38]."'>";
      echo "<input type='hidden' name='id' value='".$this->fields['id']."'>";
      echo "</td>";
      echo "</tr>";
      echo "</table></form>";

      return true;
   }

   function defineTabs($options=array()) {
      global $LANG;

      $ong[1] = $LANG['title'][26];
      $ong[2] = $LANG["datainjection"]["model"][15];
      if ($this->fields['id'] > 0) {
         $ong[3] = $LANG["datainjection"]["tabs"][3];
         $ong[4] = $LANG["datainjection"]["tabs"][0];
         if ($this->fields['step'] > PluginDatainjectionModel::MAPPING_STEP) {
            $ong[5] = $LANG["datainjection"]["tabs"][1];
            //$ong[6] = $LANG["datainjection"]["tabs"][2];
            if ($this->fields['step'] != self::READY_TO_USE_STEP) {
               $ong[7] = $LANG["datainjection"]["model"][37];
            }
         }
         $ong[12] = $LANG['title'][38];
      }
      return $ong;
   }

   function cleanDBonPurge() {
      global $DB;
      $tables = array("glpi_plugin_datainjection_modelcsvs","glpi_plugin_datainjection_mappings",
                      "glpi_plugin_datainjection_infos");
      foreach ($tables as $table) {
         $query = "DELETE FROM `$table` WHERE `models_id`='".$this->fields['id']."'";
         $DB->query($query);
      }
   }

   static function changeStep($models_id,$step) {
      $model = new PluginDatainjectionModel;
      if ($model->getFromDB($models_id)) {
         $model->dohistory = false;
         $tmp['id'] = $models_id;
         $tmp['step'] = $step;
         $model->update($tmp);
         $model->dohistory = false;
      }
   }

   function prepareInputForAdd($input) {
      global $LANG;
      //If no behavior selected
      if (!isset($input['name']) || $input['name'] == '') {
         addMessageAfterRedirect($LANG["datainjection"]["model"][30],true,ERROR,true);
         return false;
      }
      if (!$input['behavior_add'] && !$input['behavior_update']) {
         addMessageAfterRedirect($LANG["datainjection"]["model"][31],true,ERROR,true);
         return false;
      }
      return $input;
   }

   /*
    * Get the backend implementation by type
    */
   static function getInstance($type)
   {
      $class = 'PluginDatainjectionModel'.$type;
      if (class_exists($class)) {
         return new $class();
      }
      else {
         return false;
      }
   }

   function readUploadedFile($options = array()) {
     global $LANG;

      $file_encoding = (isset($options['file_encoding'])?$options['file_encoding']:
                                                        PluginDatainjectionBackend::ENCODING_AUTO);
      $webservice = (isset($options['webservice'])?$options['webservice']:false);
      $original_filename = (isset($options['original_filename'])?$options['original_filename']:false);
      $unique_filename = (isset($options['unique_filename'])?$options['unique_filename']:false);
      $injectionData = false;
      $only_header = (isset($options['only_header'])?$options['only_header']:false);
      $delete_file = (isset($options['delete_file'])?$options['delete_file']:true);

      //Get model & model specific fields
      $this->loadSpecificModel();

      if (!$webservice) {
         //Get and store uploaded file
         $original_filename = $_FILES['filename']['name'];
         $temporary_uploaded_filename = $_FILES["filename"]["tmp_name"];
         $unique_filename = tempnam (realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR), "PWS");
         if (!move_uploaded_file($temporary_uploaded_filename, $unique_filename)) {
            return array('status'=>PluginDatainjectionCommonInjectionLib::FAILED,
                         'message'=>$LANG["datainjection"]["fileStep"][8].' '.
                                                         realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR));
         }
      }

      //If file has not the right extension, reject it and delete if
      if($this->specific_model->checkFileName($original_filename)) {
         $message = $LANG["datainjection"]["fileStep"][5];
         $message.="<br />".$LANG["datainjection"]["fileStep"][6]." csv ";
         $message.=$LANG["datainjection"]["fileStep"][7];
         if (!$webservice) {
            addMessageAfterRedirect($message,true,ERROR,false);
         }
         //unlink($temporary_uniquefilename);
         return array('status'=>ERROR,'message'=>$message);
      }
      else {
         //Initialise a new backend
          $backend = PluginDatainjectionBackend::getInstance($this->fields['filetype']);
         //Init backend with needed values
         $backend->init($unique_filename,$file_encoding);
         $backend->setHeaderPresent($this->specific_model->fields['is_header_present']);
         $backend->setDelimiter($this->specific_model->fields['delimiter']);

         //Read 1 line from the CSV file
         $injectionData = $backend->read(1);

         //Read the whole file and store the number of lines found
         $backend->storeNumberOfLines();

         if ($delete_file) {
            $backend->deleteFile();
         }
         $this->setBackend($backend);
      }
      $this->injectionData = $injectionData;
   }

   /**
    * Load specific model
    */
   function loadSpecificModel() {
      $specific_model = PluginDatainjectionModel::getInstance($this->getFiletype());
      $specific_model->getFromDBByModelID($this->fields['id']);
      $this->specific_model = $specific_model;
   }

   /**
    * Once file is uploaded, process it
    * @param options an array of possible options
    *   - file_encoding
    *   - mode
    * @ return boolean
    */
   function processUploadedFile($options = array()) {
      global $LANG;

      $file_encoding=(isset($options['file_encoding'])?$options['file_encoding']
                                                        :PluginDatainjectionBackend::ENCODING_AUTO);
      $mode = (isset($options['mode'])?$options['mode']:self::PROCESS);
      $return_status = true;

      //Get model & model specific fields
      $this->loadSpecificModel();
      $this->readUploadedFile($options);

      if (!$this->injectionData) {
         return false;
      }
      else {
         if ($mode == self::PROCESS) {
            $this->loadMappings();
            $check = $this->isFileCorrect();
         }
         else {
            $check['status'] = PluginDatainjectionCommonInjectionLib::SUCCESS;
         }
         //There's an error
         if ($check['status']!= PluginDatainjectionCommonInjectionLib::SUCCESS) {
            if ($mode == self::PROCESS) {
               addMessageAfterRedirect($check['error_message'],true,ERROR);
            }
            return false;
         }
         else {
            $mappingCollection = new PluginDatainjectionMappingCollection;

            //Delete existing mappings only in model creation mode !!
            if ($mode == self::CREATION) {

               //If mapping still exists in DB, delete all of them !
               $mappingCollection->deleteMappingsFromDB($this->fields['id']);
            }

            $rank = 0;
            //Build the mappings list
            foreach (PluginDatainjectionBackend::getHeader($this->injectionData,
                                                           $this->specific_model->isHeaderPresent()
                                                           ) as $data) {
               $mapping = new PluginDatainjectionMapping;
               $mapping->fields['models_id'] = $this->fields['id'];
               $mapping->fields['rank'] = $rank;
               $mapping->fields['name'] = $data;
               $mapping->fields['value'] = PluginDatainjectionInjectionType::NO_VALUE;
               $mapping->fields['itemtype'] = PluginDatainjectionInjectionType::NO_VALUE;
               $mappingCollection->addNewMapping($mapping);
               $rank++;
            }

            if ($mode == self::CREATION) {
               //Save the mapping list in DB
               $mappingCollection->saveAllMappings();
               PluginDatainjectionModel::changeStep($this->fields['id'],
                                                    PluginDatainjectionModel::MAPPING_STEP);

               //Add redirect message
               addMessageAfterRedirect($LANG["datainjection"]["model"][32],true,INFO);
            }
         }
      }
      return $return_status;
   }

   /*
    * Try to parse an input file
    * @return true if the file is a CSV file
    */
   function isFileCorrect() {
      global $LANG;

      $field_in_error = false;

      //Get CSV file first line
      $header= PluginDatainjectionBackend::getHeader($this->injectionData,
                                                     $this->specific_model->isHeaderPresent());

      //If file columns don't match number of mappings in DB
      if(count($this->getMappings()) != count($header)) {
         $error_message  = $LANG["datainjection"]["saveStep"][11]."\n";
         $error_message .= count($this->getMappings())." ";
         $error_message .=$LANG["datainjection"]["saveStep"][16]."\n";
         $error_message .=count($header)." ".$LANG["datainjection"]["saveStep"][17];
         return array('status'=>PluginDatainjectionCommonInjectionLib::FAILED,
                      'field_in_error'=>false,
                      'error_message'=>$error_message);
      }

      //If no header in the CSV file, exit method
      if(!$this->specific_model->isHeaderPresent()) {
         return array('status'=>PluginDatainjectionCommonInjectionLib::FAILED,
                      'field_in_error'=>false,
                      'error_message'=>'');
      }

      $error = array('status'=>PluginDatainjectionCommonInjectionLib::SUCCESS,
                     'field_in_error'=>false,
                     'error_message'=>'');

      //Check each mapping to be sure it has exactly the same name
      foreach($this->getMappings() as $key => $mapping) {
         if(!isset($header[$key])) {
            $error['status'] = PluginDatainjectionCommonInjectionLib::FAILED;
            $error['field_in_error'] = $key;
         }
         else {
            //If name of the mapping is not equal in the csv file header and in the DB
            $name_from_file = trim(strtoupper(stripslashes($header[$mapping->getRank()])));
            $name_from_db   = trim(strtoupper(stripslashes($mapping->getName())));
            if($name_from_db != $name_from_file) {
               if ($error['error_message'] == '') {
                  $error['error_message'] = $LANG["datainjection"]["saveStep"][12];
               }
               $error['status']         = PluginDatainjectionCommonInjectionLib::FAILED;
               $error['field_in_error'] = false;
               $error['error_message'] .= $LANG["datainjection"]["saveStep"][18];
               $error['error_message'] .= $name_from_file."\n";
               $error['error_message'] .=$LANG["datainjection"]["saveStep"][19];
               $error['error_message'] .= $name_from_db."\n";
            }
         }
      }
      if (PluginDatainjectionCommonInjectionLib::FAILED) {
         $error['error_message'].= $LANG["datainjection"]["saveStep"][12].$error_message;
      }
      return $error;
   }

   function checkMandatoryFields($fields) {
      //Load infos associated with the model
      $this->loadInfos();
      $check = true;

      foreach ($this->infos->getAllInfos() as $info) {
         if ($info->isMandatory()) {
            if (!isset($fields[$info->getValue()]) || $fields[$info->getValue()] == '') {
               $check = false;
               break;
            }
         }
      }
      return $check;
   }

   /**
    * Model is now ready to be used
    */
   function switchReadyToUse() {
      $tmp = $this->fields;
      $tmp['step'] = self::READY_TO_USE_STEP;
      $this->update($tmp);
   }

   /**
    * Return current status of the model
    * @return nothing
    */
   function getStatusLabel() {
      global $LANG;
      if ($this->fields['step'] == self::READY_TO_USE_STEP) {
         return $LANG["datainjection"]["model"][36];
      }
      else {
         return $LANG["datainjection"]["model"][35];
      }
   }

   function populateSeveraltimesMappedFields() {
      $this->severaltimes_mapped = PluginDatainjectionMapping::getSeveralMappedField($this->fields['id']);
   }

   function getSeveraltimesMappedFields() {
      return $this->severaltimes_mapped;
   }
}
?>