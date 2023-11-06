<?php /** @noinspection PhpUnused */
    /** @noinspection PhpRedundantClosingTagInspection */

    class DWIPSWindow extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            if(!IPS_VariableProfileExists('DWIPS.'.$this->Translate('WindowState'))){
                /** @noinspection PhpExpressionResultUnusedInspection */
                IPS_CreateVariableProfile('DWIPS.'.$this->Translate('WindowState'), 1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                IPS_SetVariableProfileValues('DWIPS.'.$this->Translate('WindowState'),0,2,1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),0, $this->Translate("closed"),"", -1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),1, $this->Translate("tilted"),"", -1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),2, $this->Translate("opened"),"", -1);
            }

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("WindowSensor1ID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("WindowSensor2ID", 0);

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("ClosedStateWindowSensor1", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("ClosedStateWindowSensor2", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("TiltedStateWindowSensor1", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("TiltedStateWindowSensor2", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("OpenedStateWindowSensor1", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("OpenedStateWindowSensor2", 0);

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterVariableInteger('state', $this->Translate('state'), 'DWIPS.'.$this->Translate('WindowState'),1);
		}

		/**
        * @return void
        */
        /** @noinspection PhpExpressionResultUnusedInspection */
        public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();
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

            $this->setState();
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
                $this->setState();
            }
		}

        public function setState(){
            $sens1 = GetValueBoolean($this->ReadPropertyInteger("WindowSensor1ID"));
            $sens2 = GetValueBoolean($this->ReadPropertyInteger("WindowSensor2ID"));

            if( $sens1 == $this->ReadPropertyBoolean("ClosedStateWindowSensor1")){
                if( $sens2 == $this->ReadPropertyBoolean("ClosedStateWindowSensor2")){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue("state", 0);
                }
            }
            if ( $sens1 == $this->ReadPropertyBoolean("TiltedStateWindowSensor1")){
                if ( $sens2 == $this->ReadPropertyBoolean("TiltedStateWindowSensor2")){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue("state", 1);
                }
            }if ( $sens1 == $this->ReadPropertyBoolean("OpenedStateWindowSensor1")){
                if ( $sens2 == $this->ReadPropertyBoolean("OpenedStateWindowSensor2")){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue("state", 2);
                }
            }
        }
		
    }
?>