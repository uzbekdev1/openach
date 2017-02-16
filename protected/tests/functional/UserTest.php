<?php
/*********************************************************************************
 * OpenACH is an ACH payment processing platform
 * Copyright (C) 2011 Steven Brendtro, ALL RIGHTS RESERVED
 * 
 * Refer to /legal/license.txt for license information, or view the full license
 * online at http://openach.com/community/license.txt
 ********************************************************************************/

class UserTest extends WebTestCase
{
	public $fixtures=array(
		'users'=>'User',
	);

	public function testShow()
	{
		$this->open('?r=user/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=user/create');
	}

	public function testUpdate()
	{
		$this->open('?r=user/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=user/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=user/index');
	}

	public function testAdmin()
	{
		$this->open('?r=user/admin');
	}
}
