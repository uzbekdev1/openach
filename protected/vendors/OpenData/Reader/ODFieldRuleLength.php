<?php
/*********************************************************************************
 * OpenACH is an ACH payment processing platform
 * Copyright (C) 2011 Steven Brendtro, ALL RIGHTS RESERVED
 * 
 * Refer to /legal/license.txt for license information, or view the full license
 * online at http://openach.com/community/license.txt
 ********************************************************************************/


class ODFieldRuleLength extends ODFieldRuleTruncate
{
	public function __construct( $length )
	{
		parent::__construct( array( 'length' => $length ) );
	}
}


