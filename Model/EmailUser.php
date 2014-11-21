<?php

namespace Uneak\MailjetBundle\Model;

use Uneak\MailjetBundle\Model\EmailUserInterface;
/**
 * Description of EmailUser
 *
 * @author marc
 */
class EmailUser implements EmailUserInterface {
	
	protected $name;
	protected $email;
	
	public function __construct($email, $name = "") {
		$this->email = $email;
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getEmail() {
		return $this->email;
	}
}
