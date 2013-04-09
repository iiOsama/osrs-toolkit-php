=================================================
|    	  Official OpenSRS PHP Toolkit		    |
|      	     Date:  December 02, 2009	  	    |
|         Website:  www.opensrs.com	 	        |
|						                        |
|	Last Modified: 	Feb 19, 2013		        |
=================================================

================
1.0 Introduction
================
The purpose in building out these libraries is to help ease the implementation
of the OpenSRS API.  Not only does it give a starting point in developing an
application to allow for quick integration, but also incorporates new
communication markup languages such as JSON and YAML.  This documentation is
provided to augment the already existing API documentation which is available at:
 
    http://www.opensrs.com/site/resources/documentation/api

Table of Contents:

1.0 - Introduction
	1.1 - Live Beta Demo Environment
2.0 - Setting up the connection
	2.1 - Generating your API Private Key and whitelisting your IP address
		2.1.0 - Generatng a private key
		2.1.1 - Whitelisting an IP address for API access
	2.2 - Setting up a reseller account
        2.2.1 - Domain services
        2.2.2 - OpenSRS Mail API (OMA) services
        2.2.3 - APP services
	2.3 - Additional Configuration for customized implementations
3.0 - Quick Start Guide 
4.0 - General Use
5.0 - Returned Data
	5.1 Results Formated
6.0 - Errors
    6.1 - Toolkit Errors
    6.2 - OpenSRS Errors
7.0 - Library Functions (excerpt)
	7.1 - Lookup Domain
	7.2 - Fast Lookup
	7.3 - Get Domain Price
	7.4 - Personal Name Suggestion
	7.5 - Register a Domain
8.0 - OpenSRS Mail API (OMA)
    8.1 - Requirements
    8.2 - Configurations
    8.3 - Syntax
    8.4 - Available OMA class (commands) names 
9.0 - Library and API command translations
	9.1 - Domain Function list and corresponding Action and Object in OpenSRS XML API
	9.2 - Mail Function list and corresponding Command in OpenSRS Mail APP

==============================
1.1 Live Beta Demo Environment
==============================
A permanent demo is available at your view here:

        http://opensrsphpdemo.com
        
==============================
2.0 Setting up the connection
==============================
This PHP library currently supports data being passed in JSON and YAML (it is
also being extended to pass data in XML and Array format as well).

The OpenSRS PHP Tookit requires:
    * PHP 5
    * OpenSSL
    * PEAR - http://pear.php.net/
    * mcrypt - http://www.php.net/manual/en/book.mcrypt.php
    * getmypid() enabled
    * cURL - required for OMA
    * php-curl - required for OMA - http://www.php.net/manual/en/book.curl.php 

NOTE:  It's best to use the PHP 5.2+ as json_encode and json_decode are standard
on that version and above. If an earlier version of PHP 5 is needed, the php-json
libraries at http://pecl.php.net/package/json will be required.

Why not use the PEAR JSON functions? Well php-json is much faster! There's a great
comparison that was done a couple of years ago at:

    http://gggeek.altervista.org/sw/article_20061113.html
		
NOTE:  While we are using JSON to call the functions, making it more human
readable, the backend processing is still done in XML and thus the speed of the
calls will not be affected within the API layer.

To use the Library, an application will require the following lines:
//The loader is the key in including all of the libraries that are supported in the toolkit
require_once ("opensrs/openSRS_loader.php");

//The callstring specifies the markup to input
$callstring = json_encode($callArray);
//$callstring = Spyc::YAMLDump($callArray); // YAML output only

//The handler processes the input
$osrsHandler = processOpenSRS ("json", $callstring);
//$osrsHandler = processOpenSRS ("yaml", $callstring); // YAML output only

--------------------------------------------------------------------
2.1 Generating your API Private Key and whitelisting your IP address
--------------------------------------------------------------------
To properly use the API functionality in this toolkit (and anytime you use the
API), it's necessary to know what your Private Key is as it's the gateway to
communicating to our API servers.  If you are using a live key already, you
can reuse this key for your purposes.  If you generate a new key, there is
some lag time on the servers for proper communications to occur.  Therefore,
it's wise, especially if you're on a live system, to reuse the Private Key
rather than regenerate it.  If you are moving to a new server, it is necessary
to whitelist the IP address and open up the port for API communications to be
successful.

If you do not have a key, please follow the steps below.  

+----------------------------+
2.1.0 Generating a private key
+----------------------------+
To generate a key, login into the RWI by going to the following address:

    LIVE:  https://rr-n1-tor.opensrs.net/resellers/
    TEST:  https://horizon.opensrs.net/resellers/

*NOTE:  The TEST system is rather different than the LIVE system.  Therefore,
during testing, the results that you receive are VERY different than you would
get on the LIVE system.  Both Personal Names and Domain searching will yield
different results.  

Once authenticated, scroll down to the bottom of the page where the heading
shows "Profile Management".  Here, there is a link called "Generate New
Private Key".  If you are sure you wish to continue, click on the 'OK' button
on the warning window.  This window ensure that you wish to generate a new
key.  On refresh, you will see a key similiar to this structure:

a2e308f4df69c969de9ada09c39bb43d0e4d0a88ce83fd2c0a193328d16e8bf5080ee7e0ef388fd8aa0dfb42d33dcd02d3de321c9af1f06b

This is essentially the authentication key that you'll be using to working
with the API.  It's essential to keep this key to yourself!

+---------------------------------------------+
2.1.1 Whitelisting an IP address for API access
+---------------------------------------------+
Once you've generated your key (as above), you'll also need to whitelist your
IP address.  If this is not done, an error will be received and continuing
with the API will not possible.  To whitelist your IP address, login to either
LIVE or TEST environment in the RWI (like in section 2.1.0).  Scroll down to
the bottom of the screen under the "Profile Management" section, there is a
link called "Add IPs for Script/API Access".  Once you've clicked on that, you
can "Add New Rule" by typing in the subnet for where your application will be
hosted.  A total of 5 addresses can be added by default. 

**IMPORTANT NOTE:  Adding a new IP address can take up to 15 minutes to
propagate into our system.   

===================================
2.2 Setting up the Reseller account
===================================

In the directory /opensrs,

Copy openSRS_config.php.template to openSRS_config.php, then edit the following required values.

---------------------
2.2.1 Domain Services
---------------------
define('OSRS_HOST', ''); /* LIVE => rr-n1-tor.opensrs.net, TEST => horizon.opensrs.net */
define('OSRS_USERNAME', ''); /* OpenSRS reseller name */
define('OSRS_KEY', ''); /* OpenSRS private key */
define('OSRS_ENV', ''); /* OpenSRS environment. LIVE or TEST. */

define('OSRS_DEBUG', 0); /* If set to 1, the Toolkit will spit out the raw XML request/response. */

-------------------------------------
2.2.2 OpenSRS Mail API (OMA) Services
-------------------------------------
define('MAIL_HOST', 'https://admin.hostedemail.com'); /* LIVE => https://admin.hostedemail.com, TEST => https://admin.test.hostedemail.com
define('MAIL_USERNAME', ''); /* Company level username */
define('MAIL_PASSWORD', ''); /* Company level password */
define('MAIL_ENV', '');  /* OMA environment. LIVE or TEST. */
define('MAIL_CLIENT', 'OpenSRS PHP Toolkit'); /* Optional */


--------------------------------------------------
2.2.3 Account Provisioning Protocol (APP) Services
--------------------------------------------------
This APP libs will eventually be deprecated and be replaced by OMA.

NOTE: The APP_MAIL_USERNAME, APP_MAIL_PASSWORD and APP_MAIL_DOMAIN are all elements of a mailbox
that has company level access.

The mailbox would be broken down as:

Mailbox address:  APP_MAIL_USERNAME@APP_MAIL_DOMAIN
Mailbox password: APP_MAIL_PASSWORD

define('APP_MAIL_HOST', 'ssl://admin.hostedemail.com'); /*  LIVE => ssl://admin.test.hostedemail.com, TEST => ssl://admin.hostedemail.com */
define('APP_MAIL_USERNAME', '');
define('APP_MAIL_DOMAIN', '');


-----------------------------------------------------------
2.3 Additional Configuration for customized implementations
-----------------------------------------------------------
If there are plans to change the include folder name, additional changes need to
made to the file:

	opensrs/openSRS_config.php

Changes to the OPENSRSURI constant will need to be made as appropriate.  Using
the full path name may be necessary on some servers.

	define ("OPENSRSURI", "/path/to/opensrs/");

Further changes can be made to function folders:

// Application core configurations.

    define("OPENSRSCONFINGS", OPENSRSURI . DS . "configurations");
    define("OPENSRSDOMAINS", OPENSRSURI . DS . "domains");
    define("OPENSRSTRUST", OPENSRSURI . DS . "trust");
    define("OPENSRSPUBLISHING", OPENSRSURI . DS . "publishing");
    define("OPENSRSMAIL", OPENSRSURI . DS . "mail");
    define("OPENSRSFASTLOOKUP", OPENSRSURI . DS . "fastlookup");
    define("OPENSRSOMA", OPENSRSURI . DS . "OMA");

NOTE:  It's recommended to keep these folder names constant as upgrades to the
library may cause files to be put into the wrong folder thus possibily affecting
future enhancements.


=====================
3.0 Quick Start Guide
=====================
Additional information below will assist in setting up specific calls made to
the implementation.  However, all of the information is also included in the
testcase/ directory within this tarball.  Examples of calls can be seen and interacted
with by going to index.php on the web server, all the standardized calls have
been documented in a listed variety categorized by the type of functionality.
The test-*.php example, will provide a good idea of how everything fits into the new
implementation.  To get started in 5 minutes or less, after set-up of the connection
string (see section 2.1), all that is required are the following lines of code:

<? 
//The loader is very important here, it loads all of the libraries into the implementation 
require_once ("opensrs/openSRS_loader.php"); 

$callArray = array ( 
        //This is for the function calls, they can be found on the above website 
        "func" => "suggestDomain", 
		//"func" => "premiumDomain",
		//"func" => "lookupDomain",
        "data" => array ( 
                "domain" => "example.com", 
                // These are optional 
                "selected" => ".com;.net", // These are the selected TLDs to search with 
                "alldomains" => ".com;.net;.org;.ca" // These are the TLDs that the implementation
							supports
        ) 
); 

//This specifies what type of markup to output 
$callstring = json_encode($callArray); 
$osrsHandler = processOpenSRS ("json", $callstring); 

// these are optional depending on required output 
//$callstring = Spyc::YAMLDump($callArray); 
//$osrsHandler = processOpenSRS ("yaml", $callstring); 

// This is to print out the results 
echo (" In: ". $callstring ."<br>"); 
echo ("Out: ". $osrsHandler->resultFormated); 
?> 

===============
4.0 General Use
===============

In the openSRS_Loader there is a processOpenSRS function which will take in the
JSON data and run the appropriate function.

	$returnedResult = processOpenSRS($data_type, $call_string)

The returnedResult variable will be an object that has the result in a string using
the specified data type (json or yaml) and in an array.  There is more information
about what is returned from these functions later in this documentation.  The call
string is the JSON or YAML data that is being passed to the library. All examples
will be using JSON call strings from here on out.

The call string is broken into two parts:

func:
* This is where the system will look for what function it to be executed from the library

data
* This is the information that is being passed to the function
* Most times this is formated in a similar way to the XML, which is found in the API documentation.

An example of a call string for looking up the domain "example.com" would be:

Sample code:
{
	"func": "lookupDomain",
	"data": { 
			"domain": "example.com",
			"alldomains": ".com,.net,.org",
			"selected": ".com,.net"
			}
}

Any application will probably use json_encode($array) and json_decode($json)
instead of constructing the raw json calls itself, but this is a good way to see
it broken down to the essentials.

Here is an example of the call string being used:

Sample code:
<?
require_once("./opensrs/openSRS_loader.php");
require_once("./opensrs/spyc.php");

$openSRS_results=processOpensrs("json",'
	{
		"func": "lookupDomain",
		"data": {
			"domain": "example.com",
			"alldomains": ".com;.net;.org",
			"selected": ".com;.net"
		}
	}');
echo $openSRS_results->resultFormated . "\n";
?>

NOTE:  The example above only gives the raw JSON output.

This example shows it all put together with associative arrays:

Sample code:
<?php
require_once("./opensrs/openSRS_loader.php");
require_once("./opensrs/spyc.php");

$callString["func"]="lookupDomain";
$callString["data"]["domain"]="example.com";
$callString["data"]["alldomains"]=".com;.net;.org";
$callString["data"]["selected"]=".com;.net";

$openSRS_results=processOpensrs("json",json_encode($callString));

// Remember to have the "Associative Array" option set to true after passing the json
$result = json_decode($openSRS_results->resultFormated, true);

foreach ($result as $domain_result)
	echo $domain_result['domain'] . " is " . $domain_result['status'] . "\n";

?>

=================
5.0 Returned Data
=================
The processOpensrs function returns a full stdClass object.  This object has four
public variables that contain the returned data

---------------------
5.1 Results Formated
---------------------
resultFormated
* The result formated in the data type that is originally passed to the function
* This only returns the result and no error codes
* This will be referred to as the "condensed" response

resultFullFormated
* The result in the same data type as resultFormated
* FullFormated returns the result in the format of the XML returned from OpenSRS,
including error codes and details
* This will be referred to as the "full" response

resultRaw
* The result in an associative array instead of the data type originally passed
to the function
* Does not include any error messages, just the essentials of the result

resultFullRaw
* The result in an associative array
* Includes error messages and full data from the OpenSRS system in the format
of the XML returned from OpenSRS

In the Full Raw and Full Formated results there are the following:

	"_OPS_version"
	"protocol"
	"response_text"
	"action"
	"response_code"
	"is_success"
	"attributes"

NOTE: The attributes object will differ from function to function

The application can check is_success and response_code for any trouble with the
request and output details from response_text.  The only exception is the Fast
Lookup, as it does not go through the XML API. 

Call the processOpensrs function:

	$resultObject = processOpensrs($dataType, $callString);

Then reference the result that is easiest to use:

	echo $resultObject->resultRaw["domain"] . "is" . $resultObject->resultRaw["status"];

So why not use the Raw result all of the time and forget about the JSON results?
* The application may need to pass this information to another system and JSON
is great for transmitting data
* The JSON data can be plugged directly into any javascript or AJAX app using
"eval" or, to be more secure, using a JSON parser - http://json.org/

Here is an AJAX example that will see if the passed string has matching domains
in com/net/org/info/biz and output it to an HTML element with the ID "output".
Once the application gets the JSON response back it evaluates the JSON and will
have an object that can be use easily.

Sample AJAX example:

        function domainLookup(name) {
            var jsonResponse = {} ;
            var url="lookupGTLDDomain.php?domain=" + name ;
            var http_request

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                http_request=new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                // code for IE6, IE5
                http_request=new ActiveXObject("Microsoft.XMLHTTP");
            } else {
                alert("Your browser does not support XMLHTTP!");
            }

            http_request.open( "GET", url, true );
            http_request.send(null);

            http_request.onreadystatechange = function () {

				if (http_request.readyState == 4 && http_request.status == 200) {

					jsonResponse=eval("(" + http_request.responseText + ")");

					document.getElementById("output").innerHTML = "";

					for (var i=0;i < jsonResponse.attributes.lookup.count; i=i+1) {
						document.getElementById("output").innerHTML +=jsonResponse.attributes.lookup.items[i].domain + " is " +jsonResponse.attributes.lookup.items[i].status + "<br />";
					}
				}
			}
		}

===============
6.0 Errors
===============

Errors will happen in two different ways:

===============
6.1 Toolkit Errors
===============
These errors are generated before the toolkit is able to contact the OpenSRS API.
The errors are produced as PHP warnings or notices which would need to be captured.

To capture and deal with these PHP warnings and notices use set_error_handler:
    http://us.php.net/manual/en/function.set-error-handler.php

This will set up a function to process the errors and either log them
or display a custom error to the user.

Error types:

Incomplete config file
* Required information is missing in the config file.  The error will include
what parameter is missing.

Unable to establish socket
* Cannot create a network socket, probably due to network config error.

Authentication Error
* Unable to authenticate with OpenSRS API, check to make sure that the private
key and reseller username are correct

Reseller username or osrs_key is incorrect, please check the config file.
* Same as above

Please check the osrs_key value in the config file, it contains a non hexidecimal character.
* The OpenSRS private key only contains hex characters.  If the config file has
a key that contains non hex characters during a CBC connection, this error will appear.

Read buffer is empty.  Please make sure IP is whitelisted in RWI and check the osrs_key in the config file.
* The OpenSRS system did not return any information.  If the application is using
SSL to connect then there may be a network issue where the data cannot be read.
Under a CBC connection then it is likely that the osrs_key or reseller username is
incorrect. This is also caused by not having the machine IP whitelisted for the API.
Please see section 2.1.1 for information on how to whitelist an IP.

UNEXPECTED READ: Unable to parse HTTP response code.
* Response was not provided in HTTP 1.1 format.  Usually this is caused by the
same reasons as above.

UNEXPECTED READ: Error reading HTTP header.
* Could not read the HTTP header at all, possibly due to dropped connection during
return.

No Content-Length header returned.
* Content length header was blank.  Usually this is due to using a CBC connection
and having an incorrect key or username.  This could also happen if the IP address
of the machine is not whitelisted in OpenSRS.  See section 2.1.1 for information
on whitelisting an IP.
* This will be the most common case of this error

UNEXPECTED READ: No Content-Length.
* Generated by an unexpected read of the content length header.
* Most likely caused by the same issues as the above error, or could be an issue
with the header being corrupted.

UNEXPECTED ERROR: No Content-Length header provided!
* Same causes as above
* Generated when the Content-Lengt should always be provided

UNEXPECTED READ: No CRLF
* No carriage return provided after the header, possibly corrupted information
due to a dropped connection.

Unable to find library directory DIRECTORY,
* Caused usually by an incorrect path in openSRS_config.php.
* Double check all paths in that file to make sure they are correct.
* Directory that is affected will be listed in the error instead of DIRECTORY.

Unable to find library file FILE,
* Same as above, but for a specific library file.
* Filename will be listed in the error instead of FILE.

Unable to find the function passed.
* The Toolkit could not find the function that was passed in the call string.
This could be caused by a misspelling of the function or the configuration paths
are not set correctly.  Check the spelling of the function against the list in
Section 8 of this document and check the paths in openSRS_config.php.

PARAMETER not defined
* This is the most common error.
* Caused by the required parameters not being passed through to the function.
Please check the OpenSRS API documentation to see what is required for each
function.  The page numbers for each function are listed in Section 8 of this
document

Incorrect call
* At least one required parameter was not passed, the call will not be made to
the OpenSRS API.  There will be at least one not defined error before this error.

unknown cipher
* The toolkit only supports CBC connections encrypted with:
DES
BLOWFISH
BLOWFISH-COMPAT
* This will not display if connecting via SSL.

mcrypt_generic_init failed
* Check installation of the mycrypt package, could be corrupted

no initialization vector
* Problem with the CBC connection
* IV was not returned to the toolkit in the response header, could be caused by
a corrupted returned messge.
* This will not display if connecting via SSL.

Mail System Write Error
* The toolkit was unable to write to the server specified, please check
that the mail server name is correct in the config and that the network will
allow sending data to that server.

==================
6.2 OpenSRS Errors
==================

If the error happened on the OpenSRS API side, information will be passed back
in the returned object.

If the command was successful then resultRaw['is_success'] on the returned object will be set
to '1'.  If there is an error then it will be set to 0. The exact error code is
avaliable under resultRaw['response_code'] and the full error text under
resultRaw['response_text']. The response codes from the OpenSRS API are listed
in the API documentation at:

    http://opensrs.com/docs/opensrs_xmlapi.pdf

The response codes from the Mail APP are listed in the documentation at:

    http://opensrs.com/docs/OpenSRS_APP_Dev_Guide.pdf

Example:

$openSRS_results=processOpensrs("json",json_encode($callString));

if ($openSRS_result->resultFullRaw['is_success']=="0"){
    echo "There was an OpenSRS error.  The error code is: ";
    echo $openSRS_results->resultFullRaw['response_code'];
    echo " The error text is: ";
    echo $openSRS_results->resultFullRaw['response_text'] . "\n";
} else {
    echo $openSRS_result->resultFormated;
}

=====================
7.0 Library Functions
=====================

For every type of function, there are matching specific data requirements that
needs to be passed for the function to post the results.  As such, while this
documentation is to assist in augmenting the OpenSRS documentation, all of the
examples are also posted in the testcase/test-*.php files for reference.  Please
 note that not all of the functions are documented in this section.  For further
information on calls, please refer to the XML API documentation:
    http://opensrs.com/resources/documentation/opensrs_xmlapi.pdf

--------------------------------------------------------------
7.1 Lookup Domain (domainLookup, premiumDomain, suggestDomain)
--------------------------------------------------------------
Purpose:  To query a domain (domainLookup), suggest alternative names using a
name spinner (suggestDomain) or query a premium domain (premiumDomain)

NOTE:  The functions for domainLookup, premiumDomain and suggestDomain all have
the same format as below.  Please change the "func" name to make the different calls.

* domain - the domain to query
* alldomains - all of the tld(s) this application supports (semi-colon separated) [OPTIONAL]
* selected - the tld(s) to obtain results for (best for integration with multiple
selection checkboxes) [OPTIONAL]

Sample JSON call:
{
   "func": "lookupDomain",
   "data": {
            "domain": "example.net",
            "alldomains": ".com;.net;.org",
            "selected": ".com;.net"
           }
}

Returned Data:
"_OPS_version":"0.9",
"protocol":"XCP",
"response_text":"Command completed successfully",
"action":"REPLY",
"response_code":"200",
"is_success":"1"
"attributes":{

"lookup":{

     "count":"2",
     "response_text":"",
     "response_code":"0",
     "is_success":"1",

     "items":[
       {
         "domain":"example.com",
         "status":"available"
       },
       {
         "domain":"example.net",
         "status":"available"
       }
     ]
   }
},

In the condensed version it returns the items in an array:

[
  {
  "domain":"example.com",
  "status":"available"
  },
  {
  "domain":"example.net",
  "status":"available"
  }
]

---------------
7.2 Fast Lookup
---------------
Purpose:  To open a socket and lookup the availability of domains

* domain - the domain to query
* alldomains - all of the tld(s) the application supports (semi-colon separated) [OPTIONAL]
* selected - the tlds(s) to query for (best for integration with multiple selection
checkboxes) [OPTIONAL]

Sample JSON Call
{
 "func": "fastDomainLookup",
 "data": {
         "domain": "example",
         "selected": ".com",
         "alldomains": ".com;.net"
         }
}

Returned Data (full and condensed response):
[
  {
  "domain":"example",
  "tld":".com",
  "result":"Taken"
  },
  {
  "domain":"example",
  "tld":".net",
  "result":"Taken"
  }
]

--------------------
7.3 Get Domain Price
--------------------
Purpose:  The price associated to the domain in US Dollars

* domain - the domain to query

Sample JSON call:
{
 "func": "lookupGetPrice",
 "data": {
         "domain": "example.com"
         }
}

Returned Data:
{
 "price":"20"
}

----------------------------
7.4 Personal Name Suggestion
----------------------------
Purpose:  To suggest more selections when querying the Personal Name system

* Search String:  The first and last name of the user to find more options for

Example JSON call:
{
     "func": "persNameSuggest",
     "data": {
             "searchstring": "John Smith"
             }
}

Returned Data:
{
"suggestion":
     {
     "count":"40",
     "response_text":"Command completed successfully.",
     "response_code":"200",
     "items":[
             {"domain":"john.smith.net","status":"taken"},
             {"domain":"johnny.smith.net","status":"taken"},
             {"domain":"johnnie.smith.net","status":"available"},
             {"domain":"johnie.smith.net","status":"available"},
             {"domain":"johnn.smith.net","status":"available"},
             {"domain":"jonnie.smith.net","status":"available"}
             ],
     "is_success":"1"
     }
}

---------------------
7.5 Register a Domain
---------------------
Purpose:  This will allow registeration of all GTLDs 

NOTE:  CCTLDs are not yet supported with this call

* domain:  The domain to register
* custom_tech_contact:  Customized technical contact for that domain (option of 0|1)
* custom_nameservers:  Customized nameservers for that domain (option of 0|1)
	NOTE:  Dependent on an object called "TNS"
	- TNS
		~ Includes the list of custom name servers
		~ Each TNS entry contains:
			+ Name:  the hostname of the nameserver
			+ Sort order:  The position of the server which will show
                        up in the nameserver list for the domain
			  NOTE:  Sort order starts at 1 and _cannot_ skip numbers
* reg_username:  The registrant profile username
* reg_password:  The registrant profile password
* contact_set
	- first_name
	- last_name
	- phone
	- fax
	- org_name
	- address1
	- address2
	- address3
	- postal_code
	- city
	- state
	- country
	- lang_perf

The rest of the calls are OPTIONAL:

* Data
	- handle: if the system should handle the order by processing (process)
        it or saving it to process later (save)
	- affiliate_id:  Allows tracking of orders coming through different affiliates
	  NOTE:  100 character maximum
	- auto_renew:  If the domain should autorenew or not (option of 0|1)
	  NOTE:  By default, this is set to 0 (disabled)
	- encoding_type:  Submitting a 3 character language tag if the domain is in PUNYCODE
	  NOTE:  By default, ASCII is used, please see XML API documentatin (Appendix E)
	- f_lock_domain:  Whether the domain should be locked on registration or not (option of 0|1)
	  NOTE:  By default, this is set to 0 (disabled)
	- f_whois_privacy:  Whether the domain requires whois privacy (option of 0|1)
	  NOTE: By default, this is set to 0 (disabled)
	  ***IMPORTANT NOTE***:  By setting whois privacy to 1, users are able to switch it off in the MWI  
        If whois privacy is *not* set, users do not have the ability to turn it on in MWI
	- period:  The number of years the domain would be registered for 
	  NOTE:  The minimum number of years differ from TLD to TLD, the maximum number of years is 10  
	Please refer to the XML API Documentation 
			 
Sample JSON call:
{
  "func": "provSWregister",
  "data": {
       "domain": "example.com",
       "custom_tech_contact": "1",
       "custom_nameservers": "0",
       "reg_username": "username",
       "reg_password": "password",
       "handle": "process"
       },
       "personal": {
          "first_name": "First",
          "last_name": "Last",
          "phone": "+1.555123456789",
          "fax": "",
          "email": "email@email.com",
          "org_name": "Organization Name",
          "address1": "Address 1",
          "address2": "Address 2",
          "address3": "",
          "postal_code": "A1A2B2",
          "city": "City",
          "state": "ON",
          "country": "CA",
          "lang_pref": "EN"
      }
}

Returned Data:

"object":"DOMAIN",
"response_text":"Domain registration successfully completed",
"action":"REPLY",
"attributes":{
  "registration_text":"Domain registration successfully completed",
  "admin_email":"email@email.com",
  "registration_code":"200",
  "id":"83002651"
},
"response_code":"200",
"is_success":"1"
}

================================
8.0 OpenSRS Mail API (OMA) 
================================

The PHP toolkit now supports OMA (OpenSRS Mail API).
The OMA API is the provisioning interface for the OpenSRS email system. It allows you to create, 
modify and delete mailboxes, aliases, domains and other aspects of the service.
The interaction between the API and a client consists of a single HTTP POST request and 
HTTP response. The HTTP request and response bodies are formatted as JSON data. 

-------------------------------
8.1 Requirements
-------------------------------

* cURL
* PHP curl library - http://www.php.net/manual/en/book.curl.php

-------------------------------
8.2 Configurations
-------------------------------

In opensrs/openSRS_config.php, edit the following lines,

define("OMA_HOST", "https://admin.test.hostedemail.com/api");
define("OMA_USERNAME", "YOUR_COMPANY_LEVEL_ADMIN_USERNAME");
define("OMA_PASSWORD", "YOUR_COMPANY_LEVEL_ADMIN_PASSWORD");
define("OMA_ENV", "OMA_ENVIRONMENT"); // LIVE OR TEST
define("OMA_CLIENT", ""); // Optional

OMA_HOST should be,
TEST: https://admin.test.hostedemail.com/api
LIVE: https://admin.hostedemail.com/api

----------
8.3 Syntax
----------

Create an array of data, then call ClassName::call($data_array) returns the JSON formatted response. 

Sample code:
The following will create a new user or change the attributes of an existing user,

    require_once("opensrs/openSRS_loader.php");
    $data_array = array(
        "user" => "sample@sample.com",
        "attributes" => array(
           "name" => "Janet User",
            "password" => "star-trek1966",
            "delivery_forward" => true,
            "forward_recipients" => array(
                "janet.user@someothersample.com"
            )
        )
    );
    $response = ChangeUser::call($data_array);


JSON Request:
{
    "credentials": {
        "user": "sample@sample.com",
        "password": "password123"
    },
    "user": "jane_user@sample.com",
    "attributes": {
        "name": "Janet User",
        "password": "star-trek1966",
        "delivery_forward": true,
        "forward_recipients": [
            "janet.user@someothersample.com"
        ]
    }
}

JSON Response:
{
   "success" : true
}


----------------------------------------
8.4 Available OMA class (commands) names 
----------------------------------------
AddRole
Authenticate
ChangeCompany
ChangeCompanyBulletin
ChangeDomain
ChangeDomainBulletin
ChangeUser
CreateWorkgroup
DeleteDomain
DeleteUser
DeleteWorkgroup
EchoOMA
GenerateToken
GetCompany
GetCompanyBulletin
GetCompanyChanges
GetDeletedContacts
GetDeletedMessages
GetDomain
GetDomainBulletin
GetDomainChanges
GetUser
GetUserAttributeHistory
GetUserChanges
GetUserFolders
GetUserMessages
LogoutUser
MigrationAdd
MigrationJobs
MigrationStatus
MigrationTrace
MoveUserMessages
PostCompanyBulletin
PostDomainBulletin
Reindex
RenameUser
RestoreDeletedContacts
RestoreDeletedMessages
RestoreDomain
RestoreUser
SearchAdmins
SearchBrands
SearchDomains
SearchUsers
SearchWorkgroups
SetRole
StatsList
StatsSnapshot

================================
9.0 Library and API translations
================================

This area assists users who are already fimiliar with the OpenSRS API to match the corresponding 
commands in the library.  

---------------------------------------------------------------------------------
9.1 - Domain Function list and corresponding Action and Object in OpenSRS XML API
---------------------------------------------------------------------------------

PHP Function Name			XML Action				XML Object		XML Doc Page	PDF Page

premiumDomain				NAME_SUGGEST				DOMAIN			117				132
allinoneDomain				NAME_SUGGEST				DOMAIN			117				132
suggestDomain				NAME_SUGGEST				DOMAIN			117				132

NOTE:	There is a difference between how NAME_SUGGEST is used by each function
above.  The function premiumDomain will only run NAME_SUGGEST for Premium domain
suggestions.  The suggestDomain function will only run NAME_SUGGEST for name
suggestions.  Finally, allinoneDomain will run NAME_SUGGEST for a TLD lookup,
name suggestions and premium domains all at the same time.

authAuthenticateUser                    AUTHENTICATE				USER
authChangeOwnership			CHANGE					OWNERSHIP		533				548
authChangePassword			CHANGE					PASSWORD		536				551
authSendAuthcode			SEND_AUTHCODE				DOMAIN			539				554
authSendPassword			SEND_PASSWORD				DOMAIN			542				557
sendCiraEmailPwd			CIRA_EMAIL_PWD				DOMAIN			23				38
bulkChange				SUBMIT					BULK_CHANGE		391				406
bulkTransfer				BULK_TRANSFER				DOMAIN			385				400
cookieDelete				DELETE					COOKIE			546				561
cookieSet				SET					COOKIE			551				566
cookieUpdate				UPDATE					COOKIE			556				571
dnsCreate				CREATE_DNS_ZONE                         DOMAIN			455				470
dnsDelete				DELETE_DNS_ZONE                         DOMAIN			465				480
dnsForce				FORCE_DNS_NAMESERVERS                   DOMAIN			468				483
dnsGet					GET_DNS_ZONE                            DOMAIN			471				486
dnsReset				RESET_DNS_ZONE                          DOMAIN			478				493
dnsSet					SET_DNS_ZONE                            DOMAIN			488				503
fwdCreate				CREATE_DOMAIN_FORWARDING                DOMAIN			499				514
fwdDelete				DELETE_DOMAIN_FORWARDING                DOMAIN			501				516
fwdGet					GET_DOMAIN_FORWARDING                   DOMAIN			504				519
fwdSet					SET_DOMAIN_FORWARDING                   DOMAIN			508				523
lookupBelongsToRsp			BELONGS_TO_RSP				DOMAIN			19				34
lookupDomain				LOOKUP					DOMAIN			112				127
lookupGetBalance			GET_BALANCE				BALANCE			26				41
lookupGetCaBlockerList                  GET_CA_BLOCKER_LIST			DOMAIN			29				44
lookupGetDeletedDomains                 GET_DELETED_DOMAINS			DOMAIN			33				48
lookupGetDomain				GET					DOMAIN			40				55
lookupGetDomainByExpiry                 GET_DOMAINS_BY_EXPIREDATE               DOMAIN			88				103
lookupGetDomainContacts                 GET_DOMAIN_CONTACTS			DOMAIN			84				99
lookupGetNotes				GET_NOTES				DOMAIN			93				108
lookupGetOrderInfo			GET_ORDER_INFO				DOMAIN			97				112
lookupGetOrdersByDomain                 GET_ORDERS_BY_DOMAIN                    DOMAIN			104				119
lookupGetPrice				GET_PRICE				DOMAIN			109				124
nsAdvancedUpdt				ADVANCED_UPDATE_NAMESERVERS             DOMAIN			420				435
nsCreate				REGISTRY_ADD_NS				NAMESERVER		425				440
nsDelete				DELETE					NAMESERVER		432				447
nsGet					GET					NAMESERVER		435				450
nsModify				MODIFY					NAMESERVER		440				455
nsRegistryAdd				REGISTRY_ADD_NS				NAMESERVER		443				458
nsRegistryCheck				REGISTRY_CHECK_NAMESERVER               NAMESERVER		449				464
persDelete				DELETE					SURNAME			336				351
persNameSuggest				NAME_SUGGEST				SURNAME			307				322
persQuery				QUERY					SURNAME			324				339
persSUregister				SU_REGISTER				SURNAME			313				328
persUpdate				UPDATE					SURNAME			329				344
provActivate				ACTIVATE				DOMAIN			147				162
provCancelActivate			CANCEL_ACTIVE_PROCESS                   DOMAIN			150				165
provCancelPending			CANCEL_PENDING_ORDERS                   ORDER			153				168
provModify				MODIFY					DOMAIN			157				172
provProcessPending			PROCESS_PENDING				DOMAIN			197				212
provQueryQueuedRequest                  QUERY_QUEUED_REQUEST                    DOMAIN			202				217
provRenew				RENEW                               	DOMAIN			205				220
provRevoke				REVOKE					DOMAIN			211				226
provSendCIRAapproval                    SEND_CIRA_APPROVAL_EMAIL                DOMAIN			215				230
provSWregister				SW_REGISTER				DOMAIN			218				233
provUpdateAllInfo			UPDATE_ALL_INFO				DOMAIN			274				289
provUpdateContacts			UPDATE_CONTACTS				DOMAIN			278				293
subresCreate				CREATE					SUBRESELLER		287				302
subresGet				GET					SUBRESELLER		296				311
subresModify				MODIFY					SUBRESELLER		292				307
subresPay				PAY					SUBRESELLER		303				318
subuserAdd				ADD					SUBUSER			514				529
subuserDelete				DELETE					SUBUSER			517				532
subuserGet				GET					SUBUSER			520				535
subuserGetInfo				GET					USERINFO		523				538
subuserModify				MODIFY					SUBUSER			529				544
transCancel				CANCEL_TRANSFER				TRANSFER		340				355
transCheck				CHECK_TRANSFER				DOMAIN			344				359
transGetAway				GET_TRANSFERS_AWAY			DOMAIN			350				365
transGetIn				GET_TRANSFERS_IN			DOMAIN			360				375
transProcess				PROCESS_TRANSFER			TRANSFER		369				384
transRsp2Rsp				RSP2RSP_PUSH_TRANSFER                   DOMAIN			373				388
transSendPass				SEND_PASSWORD				TRANSFER		377				392
transTradeDomain			TRADE_DOMAIN				DOMAIN			380				395

CBC Authentication Commands:

authAuthenticateUser                    AUTHENTICATE				USER
authCheckVersion                        CHECK					VERSION
cookieQuit				QUIT					SESSION			549				564

======================================================================
9.2 - Mail Function list and corresponding Command in OpenSRS Mail APP
======================================================================
PHP Function Name				APP Command				APP Doc Page	PDF Page
mailAuthentication				LOGIN					7				17
mailChangeDomain				CHANGE_DOMAIN				18				28
mailCreateDomain				CREATE_DOMAIN				9				19
mailCreateDomainAlias                           CREATE_DOMAIN_ALIAS			10				20
mailCreateDomainWelcomeEmail                    CREATE_DOMAIN_WELCOME_EMAIL		12				22
mailCreateMailbox				CREATE_MAILBOX				32				42
mailDeleteDomain				DELETE_DOMAIN				11				21
mailDeleteDomainAlias                           DELETE_DOMAIN_ALIAS			11				21
mailDeleteDomainWelcomeEmail                    DELETE_DOMAIN_WELCOME_EMAIL		15				25
mailDeleteMailbox				DELETE_MAILBOX				47				57
mailGetCompanyDomains                           GET_COMPANY_DOMAINS			14				24
mailGetDomain					GET_DOMAIN				16				26
mailGetDomainAllowList                          GET_DOMAIN_ALLOW_LIST			26				36
mailGetDomainBlockList                          GET_DOMAIN_BLOCK_LIST			27				37
mailGetDomainMailboxes                          GET_DOMAIN_MAILBOXES			23				33
mailGetDomainMailboxLimits                      GET_DOMAIN_MAILBOX_LIMITS		24				34
mailGetNumDomainMailboxes                       GET_NUM_DOMAIN_MAILBOXES		22				32
mailSetDomainAdmin				SET_DOMAIN_ADMIN			19				29
mailSetDomainAllowList                          SET_DOMAIN_ALLOW_LIST			25				35
mailSetDomainBlockList                          SET_DOMAIN_BLOCK_LIST			26				36
mailSetDomainDisabledStatus                     SET_DOMAIN_DISABLED_STATUS		22				32
mailSetDomainMailboxLimits                      SET_DOMAIN_MAILBOX_LIMITS		24				34