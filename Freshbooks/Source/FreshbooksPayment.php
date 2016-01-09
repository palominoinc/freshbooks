<?php
/**********************************************************************************************************************************
											WebPal Product Suite Framework Libraries
-----------------------------------------------------------------------------------------------------------------------------------

FreshbooksPayment.php

This file contains the FreshbooksPayment class, which defines functionality for Freshbooks payments.

@author     Ian Stewart <ian@palominosys.com>
@date		April 8th, 2015

(c) 2002-present: all copyrights are with Palomino System Innovations Inc. (Palomino Inc.) of Toronto, Canada

Unauthorized reproduction, licensing or disclosure of this source code will be prosecuted. WebPal is a registered trademark of
Palomino System Innovations Inc. To report misuse please contact info@palominosys.com or call +1 416 964 7333.

**********************************************************************************************************************************/

namespace Freshbooks\Source;

class FreshbooksPayment extends FreshbooksElement
{
  /*
   * Function name: get
   * 
   * This function attempts to retrieve the Freshbooks payment with the specified ID.
   * 
   * Inputs:
   * - $id: the Freshbooks payment ID to check.
   * 
   * Returns: if found, the payment details; otherwise, false.
   */
  public function get($id)
  {
    $options = array(
      'payment_id' => $id
    );
    
    // Attempt to retrieve payment from Freshbooks.
    $result = $this->freshbooks->send('payment.get', $options);
    
    return $result;
  }
}