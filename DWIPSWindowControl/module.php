<?php
    /** @noinspection PhpUnused */
    /** @noinspection PhpRedundantClosingTagInspection */

    class DWIPSWindowControl extends IPSModule {

        private function getWindowGUID() : string{
            return "{D6FB6A9C-7085-0ABC-8700-D41390B35F41}";
        }

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyString("windows", '');

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterVariableInteger('count', $this->Translate('count'), '',1);

            /** @noinspection PhpExpressionResultUnusedInspection */
            /** @noinspection SpellCheckingInspection */
            $this->RegisterVariableInteger('OpenCount', $this->Translate('opened'), '',1);
		}


        /**
         * @return void
         */
        public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

        /** @noinspection PhpRedundantMethodOverrideInspection */
        public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

            /**$arrString = $this->ReadPropertyString("windows");
            $arr = json_decode($arrString);
            $this->SendDebug( "Liste", "".$arr[0], 0);*/

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

        public function updateInstanceList(){
            $windows = IPS_GetInstanceListByModuleID($this->getWindowGUID());
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->SetValue('count', count($windows));
        }

        public function updateStates(){
            $windows = IPS_GetInstanceListByModuleID($this->getWindowGUID());
            $openCount = 0;
            for($i = 0; $i < count($windows);$i++){
                if(GetValueInteger($windows[$i]) > 0){
                    $openCount += 1;
                }
            }
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->SetValue('OpenCount', $openCount);

        }
		
	}
	?>