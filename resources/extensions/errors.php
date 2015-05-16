<?php

//  author:   David Tucker
//  reviewed: 8-8-2011
//  modified: 10-9-2011
//  status:   beta (debugged, untested)
//
//  Description
//      allows for fine-tune error-handling
//
//
//  Constants
//      none
//
//  Database
//      none
//
//  Functions (1)
//      report_error
//
//  Requirements
//      none
//
//
//  Notes
//      none

/**********************************************************
Description
	logs a custom error

Order Θ(1)

Parameters
	[message]
		the message to report
		If unspecified, a generic message will be reported.

Return Values
	No value is returned.

**********************************************************/
function report_error ($message=FALSE)
{
	//pre-condition
	assert (is_string($message));
	
	if ($message === FALSE) $message = "An error has occurred.";
	if (!error_log($message)) throw new Exception("An attempt to log an error has failed. Error: ".$message);
}

?>