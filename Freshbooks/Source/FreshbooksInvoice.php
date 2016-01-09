<?php
/**********************************************************************************************************************************
											WebPal Product Suite Framework Libraries
-----------------------------------------------------------------------------------------------------------------------------------

FreshbooksInvoice.php

This file contains the FreshbooksInvoice class, which defines functionality for Freshbooks invoices.

@author     Ian Stewart <ian@palominosys.com>
@date		April 8th, 2015

(c) 2002-present: all copyrights are with Palomino System Innovations Inc. (Palomino Inc.) of Toronto, Canada

Unauthorized reproduction, licensing or disclosure of this source code will be prosecuted. WebPal is a registered trademark of
Palomino System Innovations Inc. To report misuse please contact info@palominosys.com or call +1 416 964 7333.

**********************************************************************************************************************************/

namespace Freshbooks\Source;

class FreshbooksInvoice extends FreshbooksElement
{
  /*
   * Function name: get
   * 
   * This function attempts to retrieve the Freshbooks invoice with the specified ID.
   * 
   * Inputs:
   * - $id: the Freshbooks invoice ID to check.
   * 
   * Returns: if found, the invoice details; otherwise, false.
   */
  public function get($id)
  {
    $options = array(
      'invoice_id' => $id
    );
    
    // Attempt to retrieve invoice from Freshbooks.
    $result = $this->freshbooks->send('invoice.get', $options);
    
    return $result;
  }
  
  /*
   * Function name: get_amount
   * 
   * This function attempts to retrieve the amount charged on a Freshbooks invoice with the specified ID.
   * 
   * Inputs:
   * - $id: the Freshbooks invoice ID to find.
   * 
   * Returns: if found, the amount charged on the invoice; otherwise, false.
   */
  public function get_amount($id)
  {
    // Result to return.
    $return_result = false;
    
    // Attempt to find the invoice.
    $result = $this->get($id);
    
    // If the invoice is found, retrieve the amount charged and return.
    if (!empty($result) && !empty($result->invoice))
    {
      $return_result = $result->invoice->amount->__toString();
    }
    
    return $return_result;
  }
  
  /*
   * Function name: create
   * 
   * This function attempts to generate a new invoice record in Freshbooks.
   * 
   * Inputs:
   * - $client_id: the Freshbooks ID to charge the invoice to.
   * - $amount: the amount to charge on the invoice.
   * - $exam_name: the name of the exam.
   * - $exam_description: the exam descripion.
   */
  public function create($client_id, $amount, $exam_name, $exam_description)
  {
    // Generate the invoice object and save in Freshbooks.
    $options = array(
      'invoice' => array(
        'client_id' => $client_id,
        'status' => 'sent',
        'lines' => array(
          'line' => array(
            'name' => $exam_name,
            'description' => $exam_description,
            'unit_cost' => $amount,
            'tax1_name' => 'HST',
            'tax1_percent' => '13',
            'quantity' => '1'
          )
        )
      )
    );
    
    $result = $this->freshbooks->send('invoice.create', $options);
    
    // If the invoice creation call did not fail, retrieve the invoice ID and return.
    if (!empty($result))
    {
      $result = $result->invoice_id;
    }
    
    return $result;
  }
}