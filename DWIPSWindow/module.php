<?php
	class DWIPSWindow extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyInteger("WindowSensor1ID", 0);
            $this->RegisterPropertyInteger("WindowSensor2ID", 0);
			

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
                    echo $instID . " - " . $message;
                    $this->UnregisterMessage($instID, $message);
                }
            }
            var_dump($MessageList);
            //$this->SendDebug("Messages", var_dump($MessageList),0);

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