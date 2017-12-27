 <?php
 
$p =  exec('crontab -l');
var_dump($p);
       //$connectionInfo = array( "Database"=>$this->databaseConnection2->DatabaseName, "UID"=> $this->databaseConnection2->Username, "PWD"=>$this->databaseConnection2->Password);
      // $db = sqlsrv_connect( $this->databaseConnection2->ServerName, $connectionInfo);

        // $db = mssql_connect('gw1.unifun.com', 'sa', 'i7D130MQQe');
         //$db = mssql_connect('10.8.1.81', 'sa', 'i7D130MQQe');
         $db = mssql_connect('192.168.1.95', 'sa', 'i7D130MQQe');

		 
        if (!$db) {
            die('Could not connect: ' . mssql_get_last_message());
        }
		// mssql_select_db($this->databaseConnection2->DatabaseName, $db) or die('Could not select database.');
		      