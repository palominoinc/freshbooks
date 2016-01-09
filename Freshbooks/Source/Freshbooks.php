<?php
/**********************************************************************************************************************************
											WebPal Product Suite Framework Libraries
-----------------------------------------------------------------------------------------------------------------------------------

Freshbooks.php

This file contains the Freshbooks class, which is used to connect to Freshbooks and send requests.

@author     Ian Stewart <ian@palominosys.com>
@date		April 8th, 2015

(c) 2002-present: all copyrights are with Palomino System Innovations Inc. (Palomino Inc.) of Toronto, Canada

Unauthorized reproduction, licensing or disclosure of this source code will be prosecuted. WebPal is a registered trademark of
Palomino System Innovations Inc. To report misuse please contact info@palominosys.com or call +1 416 964 7333.

**********************************************************************************************************************************/

namespace Freshbooks\Source;

class Freshbooks
{
  private $url; // The URL for the relevant Freshbooks install.
  private $token; // The token for the relevant Freshbooks install.
  private $curl_connection; // The current cURL connection to the Freshbooks install.
  
  /*
   * Function name: __construct
   * 
   * This function populates the defaults for this Freshbooks install and initiates the cURL connection that will be used for subsequent calls.
   */
  public function __construct()
  {
    $this->url = 'https://YOURCOMPANY.freshbooks.com/api/2.1/xml-in';
    $this->token = '*************************';
    $this->connect();
  }
  
  /*
   * Function name: connect
   * 
   * This function initiates a cURL connection to the Freshbooks install.
   */
  public function connect()
  {
    $this->curl_connection = curl_init($this->url);
    curl_setopt($this->curl_connection, CURLOPT_HEADER, false);
    curl_setopt($this->curl_connection, CURLOPT_NOBODY, false);
    curl_setopt($this->curl_connection, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($this->curl_connection, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($this->curl_connection, CURLOPT_USERPWD, $this->token);
    curl_setopt($this->curl_connection, CURLOPT_TIMEOUT, 20);
    curl_setopt($this->curl_connection, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($this->curl_connection, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($this->curl_connection, CURLOPT_USERAGENT, "FreshBooks API - NACC");
  }
  
  /*
   * Function name: send
   * 
   * This function sends a request to Freshbooks. If successful, the result is returned. Otherwise, false is returned.
   * 
   * Inputs:
   * - $command: the Freshbooks command to execute.
   * - $options: an array of the options to send as part of the request.
   * 
   * Returns: if successful, the XML result is returned; otherwise, false is returned.
   */
  public function send($command, $options)
  {
    // Build the request XML string.
    $request = $this->build_request($command, $options);
    
    // Post the request XML string.
    curl_setopt($this->curl_connection, CURLOPT_POSTFIELDS, $request);
    $result = curl_exec($this->curl_connection);
    
    // If the call fails, return false.
    if ($result === false)
    {
      return false;
	}
    // Otherwise, return the XML response.
	else
    {
	  return simplexml_load_string($result);
    }
  }
  
  /*
   * Function name: build_request
   * 
   * This function builds an XML request string that can be sent to Freshbooks.
   * 
   * Inputs:
   * - $command: the Freshbooks command to execute.
   * - $options: an array of the options to send as part of the request.
   * 
   * Returns: an XML request string.
   */
  private function build_request($command, $options)
  {
    $request = '<?xml version="1.0" encoding="utf-8"?>';
    $request .= "<request method=\"{$command}\">";
    
    // Iterate through each option and build the associated XML string.
    foreach($options as $label => $option)
    {
      $request .= $this->build_options($label, $option);
    }
    
    $request .= "</request>";
    
    return $request;
  }
  
  /*
   * Function name: build_options
   * 
   * This function recursively builds out the XML tree for an option.
   * 
   * Inputs:
   * - $label: the name of the option being set.
   * - $options: either a value that is assigned to this option, or an array of options to recursively place within this label.
   * 
   * Returns: the XML for an option within a request.
   */
  private function build_options($label, $options)
  {
    $request = '';
    
    // If an array of options has been passed, print the label and recursively fill in the options.
    if (is_array($options))
    {
      $xml_label = htmlspecialchars($label);
      $request .= "<{$xml_label}>";
      
      // Iterate through the array of options contained within this one and add to the XML string.
      foreach ($options as $option_label => $option_value)
      {
        $request .= $this->build_options($option_label, $option_value);
      }
      
      $request .= "</{$xml_label}>";
    }
    // Otherwise, just assign the value to the option.
    else
    {
      $xml_label = htmlspecialchars($label);
      $xml_value = htmlspecialchars($options);
      $request .= "<{$xml_label}>{$xml_value}</{$xml_label}>";
    }
    
    return $request;
  }
}