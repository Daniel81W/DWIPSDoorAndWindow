<?php
	class DWIPSWindow extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            if(!IPS_VariableProfileExists('DWIPS.'.$this->Translate('WindowState'))){
                IPS_CreateVariableProfile('DWIPS.'.$this->Translate('WindowState'), 1);
                IPS_SetVariableProfileValues('DWIPS.'.$this->Translate('WindowState'),0,2,1);
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),0, $this->Translate("closed"),"", -1);
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),1, $this->Translate("tilted"),"", -1);
                IPS_SetVariableProfileAssociation('DWIPS.'.$this->Translate("WindowState"),2, $this->Translate("opened"),"", -1);
            }

			$this->RegisterPropertyInteger("WindowSensor1ID", 0);
            $this->RegisterPropertyInteger("WindowSensor2ID", 0);
			
            $this->RegisterVariableInteger('state', $this->Translate('state'), 'DWIPS.'.$this->Translate('WindowState'),1);
		}

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
                    $this->UnregisterMessage($instID, $message);
                }
            }

			$this->RegisterMessage($this->ReadPropertyInteger("WindowSensor1ID"),10603);
            $this->RegisterMessage($this->ReadPropertyInteger("WindowSensor2ID"),10603);
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdateSunrise($id);
        *
        */

		public function ReceiveData($JSONString) {
			
		}

		public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {
	
			//IPS_LogMessage("MessageSink", "Message from SenderID ".$SenderID." with Message ".$Message."\r\n Data: ".print_r($Data, true));


		
			
		}
		
	}
	?>