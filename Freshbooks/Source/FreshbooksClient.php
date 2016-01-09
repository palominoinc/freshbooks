<?php
/**********************************************************************************************************************************
											WebPal Product Suite Framework Libraries
-----------------------------------------------------------------------------------------------------------------------------------

FreshbooksClient.php

This file contains the FreshbooksClient class, which defines functionality for Freshbooks clients.

@author     Ian Stewart <ian@palominosys.com>
@date		April 8th, 2015

(c) 2002-present: all copyrights are with Palomino System Innovations Inc. (Palomino Inc.) of Toronto, Canada

Unauthorized reproduction, licensing or disclosure of this source code will be prosecuted. WebPal is a registered trademark of
Palomino System Innovations Inc. To report misuse please contact info@palominosys.com or call +1 416 964 7333.

**********************************************************************************************************************************/

namespace Freshbooks\Source;

class FreshbooksClient extends FreshbooksElement
{
  /*
   * Function name: get
   * 
   * This function returns a client based on a provided name.
   * 
   * Inputs:
   * - $client_name: the name of the client to find.
   * 
   * Returns: if found, the client ID; otherwise, false.
   */
  public function get($client_name)
  {
    // Check one page at a time, with the maximum allowed 100 results in each page.
    $options = array(
      'page' => 1,
      'per_page' => 100
    );
    
    // Default client ID to false.
    $client_id = false;
    
    // Keep retrieving more client results from Freshbooks until client is found or results run out.
    while (empty($client_id) && $client_list = $this->freshbooks->send('client.list', $options))
    {
      // If results have run out, exit loop.
      if (empty($client_list->clients->client))
      {
        break;
      }
      
      // Increase the page number each time.
      $options['page']++;
      
      // Iterate through each returned client. If a match is found, return the client ID.
      foreach ($client_list->clients->client as $client)
      {
        if ($client->organization == $client_name)
        {
          $client_id = $client->client_id;
          break;
        }
      }
    }
    
    return $client_id;
  }
  
  /*
   * Function name: check_id_name_match
   * 
   * This function checks that the provided Freshbooks client ID and name still match in Freshbooks.
   * 
   * Inputs:
   * - $id: the Freshbooks client ID to check.
   * - $client_name: the Freshbooks client name to check.
   * 
   * Returns: if found, the client ID; otherwise, false.
   */
  public function check_id_name_match($id, $client_name)
  {
    $options = array(
      'client_id' => $id
    );
    
    // Retrieve the client with the provided ID in Freshbooks.
    $freshbooks_client = $this->freshbooks->send('client.list', $options);
    
    // If the client exists in Freshbooks and has the correct name, return true; otherwise, return false.
    return !empty($freshbooks_client) && $freshbooks_client->client->organization == $client_name;
  }
  
  /*
   * Function name: get_all
   * 
   * This function returns a list of all clients in Freshbooks. Used primarily for debugging/assignment purposes.
   * 
   * Returns: a list of all Freshbooks clients.
   */
  public function get_all()
  {
    // Retrieve one page at a time, with the maximum allowed 100 results in each page.
    $options = array(
      'page' => 1,
      'per_page' => 100
    );
    
    $clients = array();
    
    // Retrieve 100 results from Freshbooks at a time, until results run out.
    while ($client_list = $this->freshbooks->send('client.list', $options))
    {
      // If results have run out, exit loop.
      if (empty($client_list->clients->client))
      {
        break;
      }
      
      // Increase the page number each time.
      $options['page']++;
      
      // Iterate through each Freshbooks client and populate their information into an array.
      foreach ($client_list->clients->client as $client)
      {
        $clients[] = array(
          'id' => $client->client_id,
          'organization' => $client->organization,
          'city' => $client->p_city,
          'postal_code' => $client->p_code,
          'email' => $client->email
        );
      }
    }
    
    return $clients;
  }
}