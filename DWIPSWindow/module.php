<?php
    /** @noinspection PhpExpressionResultUnusedInspection */
    /** @noinspection PhpUnused */
    /** @noinspection PhpRedundantClosingTagInspection */

    class DWIPSWindow extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            /** Properties for data storage*/
            //Property with instance id
            $this->RegisterPropertyInteger("InstID", 0);
            //Property with number of window sashes
            $this->RegisterPropertyInteger("SashesCount", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorLeftLockedID", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorRightLockedID", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorLeftOpenedID", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorRightOpenedID", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorLeftTiltedID", 0);
            //
            $this->RegisterPropertyInteger("WindowSensorRightTiltedID", 0);
            //
            $this->RegisterPropertyBoolean("SashesIndependent", 0);
            //
            $this->RegisterPropertyInteger("HandleSash", 1);



            /**Create or update VariableProfiles*/
            if (!IPS_VariableProfileExists('DWIPS.' . $this->Translate('WindowState_twin'))) {
                IPS_CreateVariableProfile('DWIPS.' . $this->Translate('WindowState_twin'), 1);
            }
            else{
                IPS_SetVariableProfileValues('DWIPS.' . $this->Translate('WindowState_twin'), 0, 63, 1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_twin"), 0, $this->Translate("locked"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_twin"), 1, $this->Translate("unlocked") . ", " . $this->Translate("closed"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_twin"), 2, $this->Translate("tilted"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_twin"), 3, $this->Translate("opened"), "", -1);
            }

            if (!IPS_VariableProfileExists('DWIPS.' . $this->Translate('WindowState_single'))) {
                IPS_CreateVariableProfile('DWIPS.' . $this->Translate('WindowState_single'), 1);
            }
            else{
                IPS_SetVariableProfileValues('DWIPS.' . $this->Translate('WindowState_single'), 0, 7, 1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_single"), 0, $this->Translate("locked"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_single"), 1, $this->Translate("unlocked") . ", " . $this->Translate("closed"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_single"), 2, $this->Translate("tilted"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState_single"), 3, $this->Translate("opened"), "", -1);
            }


            /** Create variables */
            $this->RegisterVariableInteger('state', $this->Translate('state'), 'DWIPS.'.$this->Translate('WindowState_single'),1);

		}

		/**
        * @return void
        */
        public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();


            $controlInstID = IPS_GetInstanceListByModuleID("{FA667129-D2A1-FF51-BD31-8D042F9EC8E0}")[0];
            if(isset($controlInstID)){
                /** @noinspection PhpUndefinedFunctionInspection */
                DWIPSWINDOWCONTROL_updateInstanceList($controlInstID);
            }
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

            if($this->ReadPropertyBoolean("SashesCount") == 1){
                IPS_SetVariableCustomProfile($this->GetIDForIdent("state"), 'DWIPS.' . $this->Translate("WindowState_single"));
            }elseif($this->ReadPropertyBoolean("SashesCount") == 2){
                IPS_SetVariableCustomProfile($this->GetIDForIdent("state"), 'DWIPS.' . $this->Translate("WindowState_twin"));
            }


            $MessageList = $this->GetMessageList();
            foreach($MessageList as $instID => $messages){
                foreach ($messages as $message){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->UnregisterMessage($instID, $message);
                }
            }

            $wsllID =$this->ReadPropertyInteger("WindowSensorLeftLockedID");
            if($wsllID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsllID, 10603);
            }
            $wsloID =$this->ReadPropertyInteger("WindowSensorLeftOpenedID");
            if($wsloID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsloID, 10603);
            }
            $wsltID =$this->ReadPropertyInteger("WindowSensorLeftTiltedID");
            if($wsltID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsltID, 10603);
            }
            $wsrlID =$this->ReadPropertyInteger("WindowSensorRightLockedID");
            if($wsrlID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsrlID, 10603);
            }
            $wsroID =$this->ReadPropertyInteger("WindowSensorRightOpenedID");
            if($wsroID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsroID, 10603);
            }
            $wsrtID =$this->ReadPropertyInteger("WindowSensorRightTiltedID");
            if($wsrtID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage($wsrtID, 10603);
            }

            $this->SetState();

            $controlInstID = IPS_GetInstanceListByModuleID("{FA667129-D2A1-FF51-BD31-8D042F9EC8E0}")[0];
            if(isset($controlInstID)){
                /** @noinspection PhpUndefinedFunctionInspection */
                DWIPSWINDOWCONTROL_updateInstanceList($controlInstID);
            }
		}


        public function GetConfigurationForm():string{

            $retStr = "{";
            $retStr .= "\"elements\": [";
            $retStr .= "{\"type\": \"ExpansionPanel\",\"caption\": \"Fenstereigenschaften\",\"expanded\": true, \"items\": [";
            $retStr .= "{\"type\": \"Select\", \"caption\": \"Anzahl der Flügel\", \"name\": \"SashesCount\", \"options\": [ { \"caption\": \"1\", \"value\": 1 },{ \"caption\": \"2\", \"value\": 2 }]}";
            if($this->ReadPropertyInteger("SashesCount") == 2){
                $retStr .= ",{\"type\": \"CheckBox\",\"caption\":\"Flügel unabhängig zu öffnen?\",\"name\":\"SashesIndependent\"}";
            }
            if($this->ReadPropertyInteger("SashesCount") >= 2 && !$this->ReadPropertyBoolean("SashesIndependent")){
                $retStr .= ",{\"type\": \"Select\",\"caption\":\"Griffflügel\",\"name\":\"HandleSash\", \"options\": [ { \"caption\": \"rechts\", \"value\": 1 },{ \"caption\": \"links\", \"value\": 2 }]}";
            }
            $retStr .= "]}";
            $retStr .= ",{\"type\": \"ExpansionPanel\",\"caption\": \"Fenstersensoren\",\"expanded\": false, \"items\": [";
            $retStr .= "{\"type\": \"SelectVariable\",\"name\": \"WindowSensorLeftLockedID\",\"caption\": \"Sensor links Verriegelung\",\"validVariableTypes\": [0]}";
            $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorLeftOpenedID\",\"caption\": \"Sensor links Öffnung\",\"validVariableTypes\": [0]}";
            if($this->ReadPropertyBoolean("SashesIndependent") or $this->ReadPropertyInteger("HandleSash") ==2){
                $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorLeftTiltedID\",\"caption\": \"Sensor links Kippung\",\"validVariableTypes\": [0]}";
            }
            if($this->ReadPropertyInteger("SashesCount") > 1) {
                $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightLockedID\",\"caption\": \"Sensor rechts Verriegelung\",\"validVariableTypes\": [0]}";
                $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightOpenedID\",\"caption\": \"Sensor rechts Öffnung\",\"validVariableTypes\": [0]}";
            }
            if($this->ReadPropertyBoolean("SashesIndependent") or $this->ReadPropertyInteger("HandleSash") ==1){
                $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightTiltedID\",\"caption\": \"Sensor rechts Kippung\",\"validVariableTypes\": [0]}";
            }
            $retStr .= "]}";
            $retStr .= "],";
            $retStr .= "\"actions\": [],";
            $retStr .= "\"status\": []";
            $retStr .= "}";
            return $retStr;
        }

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdateSunrise($id);
        *
        */

		public function ReceiveData($JSONString) {
			
		}

		public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {
	
			//IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message ".$Message."\r\n Data: ".print_r($Data, true));
			    $this->SetState();

		}

        public function SetState(){
            $sashCount = $this->ReadPropertyInteger("SashesCount");
            $wsllID = $this->ReadPropertyInteger("WindowSensorLeftLockedID");
            $wsloID = $this->ReadPropertyInteger("WindowSensorLeftOpenedID");
            $wsltID = $this->ReadPropertyInteger("WindowSensorLeftTiltedID");
            $wsrlID = $this->ReadPropertyInteger("WindowSensorRightLockedID");
            $wsroID = $this->ReadPropertyInteger("WindowSensorRightOpenedID");
            $wsrtID = $this->ReadPropertyInteger("WindowSensorRightTiltedID");

            $this->SendDebug("SetState", $wsllID,0);

                $lock1 = false;
                $open1 = false;
                $tilt1 = false;
                $lock2 = false;
                $open2 = false;
                $tilt2 = false;
                if($wsllID > 1) {
                    $lock1 = GetValueBoolean($wsllID);
                    $this->SendDebug("SetState", $lock1,0);
                }
                if($wsltID > 1) {
                    $tilt1 = GetValueBoolean($wsltID);
                }
                if($wsloID > 1) {
                    $open1 = GetValueBoolean($wsloID);
                }
                if($wsrlID > 1) {
                    $lock2 = GetValueBoolean($wsrlID);
                }
                if($wsrtID > 1) {
                    $tilt2 = GetValueBoolean($wsrtID);
                }
                if($wsroID > 1) {
                    $open2 = GetValueBoolean($wsroID);
                }

                $valleft = 0;
                $valleft += (int)$lock1 * 1;
                if($open1){
                    if($tilt1){
                        $valleft += 4;
                    }else{
                        $valleft += 2;
                    }
                }

                $valright = 0;
                $valright += (int)$lock2 * 8;
                if($open2){
                    if($tilt2){
                        $valright += 32;
                    }else{
                        $valright += 16;
                    }
                }

                $val = $valleft + $valright;
                //$val = (int)$lock1 * 1 + (int) $tilt1 * 2 + (int) $open1 * 4 + (int)$lock2 * 8 + (int) $tilt2 * 16 + (int) $open2 * 32;


                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->SetValue("state", $val);




            $controlInstID = IPS_GetInstanceListByModuleID("{FA667129-D2A1-FF51-BD31-8D042F9EC8E0}")[0];
            if(isset($controlInstID)){
                /** @noinspection PhpUndefinedFunctionInspection */
                DWIPSWINDOWCONTROL_updateStates($controlInstID);
            }
        }

        public function GetState() : int{
            return $this->GetValue('state');
        }
		
    }
?>