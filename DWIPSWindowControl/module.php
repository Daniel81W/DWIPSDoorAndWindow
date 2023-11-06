<?php /** @noinspection PhpRedundantClosingTagInspection */

class DWIPSWindowControl extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyString("windows", '');

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterVariableInteger('count', $this->Translate('count'), '',1);
		}


        /**
         * @return void
         */
        public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

            $arrString = $this->ReadPropertyString("windows");
            $arr = json_decode($arrString);
            var_dump($arr);
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
			
		}
		
	}
	?>