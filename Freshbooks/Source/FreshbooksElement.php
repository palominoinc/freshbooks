<?php
/**********************************************************************************************************************************
											WebPal Product Suite Framework Libraries
-----------------------------------------------------------------------------------------------------------------------------------

FreshbooksElement.php

This file contains the FreshbooksElement class, which is the parent class to specific Freshbooks elements.

@author     Ian Stewart <ian@palominosys.com>
@date		April 8th, 2015

(c) 2002-present: all copyrights are with Palomino System Innovations Inc. (Palomino Inc.) of Toronto, Canada

Unauthorized reproduction, licensing or disclosure of this source code will be prosecuted. WebPal is a registered trademark of
Palomino System Innovations Inc. To report misuse please contact info@palominosys.com or call +1 416 964 7333.

**********************************************************************************************************************************/

namespace Freshbooks\Source;

class FreshbooksElement
{
  // The existing Freshbooks helper class object.
  protected $freshbooks;
  
  /*
   * Function name: __construct
   * 
   * This function populates the Freshbooks helper class object for the Freshbooks element.
   */
  public function __construct($freshbooks)
  {
    $this->freshbooks = $freshbooks;
  }
}