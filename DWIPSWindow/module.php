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

            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorLeft1", 0);
            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorLeft2", 0);
            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorLeft3", 0);
            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorRight1", 0);
            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorRight2", 0);
            //
            $this->RegisterPropertyBoolean("LockedStateWindowSensorRight3", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorLeft1", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorLeft2", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorLeft3", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorRight1", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorRight2", 0);
            //
            $this->RegisterPropertyBoolean("ClosedStateWindowSensorRight3", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorLeft1", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorLeft2", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorLeft3", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorRight1", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorRight2", 0);
            //
            $this->RegisterPropertyBoolean("TiltedStateWindowSensorRight3", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorLeft1", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorLeft2", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorLeft3", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorRight1", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorRight2", 0);
            //
            $this->RegisterPropertyBoolean("OpenedStateWindowSensorRight3", 0);


            /**Create or update VariableProfiles*/
            if (!IPS_VariableProfileExists('DWIPS.' . $this->Translate('WindowState'))) {
                IPS_CreateVariableProfile('DWIPS.' . $this->Translate('WindowState'), 1);
            }
            else{
                IPS_SetVariableProfileValues('DWIPS.' . $this->Translate('WindowState'), 0, 3, 1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState"), 0, $this->Translate("locked"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState"), 1, $this->Translate("unlocked") . ", " . $this->Translate("closed"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState"), 2, $this->Translate("tilted"), "", -1);
                IPS_SetVariableProfileAssociation('DWIPS.' . $this->Translate("WindowState"), 3, $this->Translate("opened"), "", -1);
            }


            /** Create variables */
            $this->RegisterVariableInteger('state', $this->Translate('state'), 'DWIPS.'.$this->Translate('WindowState'),1);

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

            IPS_SetProperty($this->InstanceID, "InstID", $this->InstanceID);

            $MessageList = $this->GetMessageList();
            foreach($MessageList as $instID => $messages){
                foreach ($messages as $message){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->UnregisterMessage($instID, $message);
                }
            }

            /** @noinspection PhpExpressionResultUnusedInspection */
			$this->RegisterMessage($this->ReadPropertyInteger("WindowSensor1ID"),10603);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterMessage($this->ReadPropertyInteger("WindowSensor2ID"),10603);


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
            $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorLeftTiltedID\",\"caption\": \"Sensor links Kippung\",\"validVariableTypes\": [0]}";
            $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightLockedID\",\"caption\": \"Sensor rechts Verriegelung\",\"validVariableTypes\": [0]}";
            $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightOpenedID\",\"caption\": \"Sensor rechts Öffnung\",\"validVariableTypes\": [0]}";
            $retStr .= ",{\"type\": \"SelectVariable\",\"name\": \"WindowSensorRightTiltedID\",\"caption\": \"Sensor rechts Kippung\",\"validVariableTypes\": [0]}";
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
			if ($SenderID == $this->ReadPropertyInteger("WindowSensor1ID") or $SenderID == $this->ReadPropertyInteger("WindowSensor2ID")){
                $this->SetState();
            }
		}

        public function SetState(){
            $sens1ID = $this->ReadPropertyInteger("WindowSensor1ID");
            $sens2ID = $this->ReadPropertyInteger("WindowSensor2ID");

            if ($sens1ID > 1 && $sens2ID > 1) {
                $sens1 = GetValueBoolean($sens1ID);
                $sens2 = GetValueBoolean($sens2ID);

                if ($sens1 == $this->ReadPropertyBoolean("ClosedStateWindowSensor1")) {
                    if ($sens2 == $this->ReadPropertyBoolean("ClosedStateWindowSensor2")) {
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->SetValue("state", 0);
                    }
                }
                if ($sens1 == $this->ReadPropertyBoolean("TiltedStateWindowSensor1")) {
                    if ($sens2 == $this->ReadPropertyBoolean("TiltedStateWindowSensor2")) {
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->SetValue("state", 1);
                    }
                }
                if ($sens1 == $this->ReadPropertyBoolean("OpenedStateWindowSensor1")) {
                    if ($sens2 == $this->ReadPropertyBoolean("OpenedStateWindowSensor2")) {
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->SetValue("state", 2);
                    }
                }
            }
            elseif($sens1ID > 1){
                $sens1 = GetValueBoolean($sens1ID);
                if($sens1){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue("state", 2);
                }else{
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue("state", 0);
                }
            }

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