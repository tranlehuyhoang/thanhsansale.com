<?php

namespace Paulwscom\Lazada;
use App\Core\Logger;


class LazopLogger
{
	public function log($logData)
	{
		$logger = new Logger();
		$logger->log($logData);
	}
}
?>
