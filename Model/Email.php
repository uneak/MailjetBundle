<?php

namespace Uneak\MailjetBundle\Model;

use Uneak\MailjetBundle\Model\EmailUserInterface;
use Uneak\MailjetBundle\Mailjet\Mailjet;

/**
 * Description of Email
 *
 * @author marc
 */
class Email {

	protected $twig;
	protected $sender;
	protected $receiver;
	protected $cc_receiver;
	protected $body;
	protected $subject;
	protected $isHtml;
	protected $parameters;
	protected $apiKey;
	protected $secretKey;

	public function __construct($twig) {
		$this->receiver = array();
		$this->cc_receiver = array();
		$this->parameters = array();
		$this->isHtml = false;
		$this->twig = $twig;
	}

	public function setApiKey($apiKey) {
		$this->apiKey = $apiKey;
		return $this;
	}

	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;
		return $this;
	}

	public function setSender(EmailUserInterface $emailUser) {
		$this->sender = $emailUser;
		return $this;
	}

	public function getSender() {
		return $this->sender;
	}

	public function addReceiver(EmailUserInterface $emailUser) {
		array_push($this->receiver, $emailUser);
		return $this;
	}

	public function removeReceiver(EmailUserInterface $emailUser) {
		$key = array_search($emailUser, $this->receiver);
		if ($key !== false) {
			array_splice($this->receiver, $key, 1);
		}
		return $this;
	}

	public function setReceivers($receivers) {
		$this->receiver = $receivers;
		return $this;
	}

	public function getReceivers() {
		return $this->receiver;
	}

	public function addCCReceiver(EmailUserInterface $emailUser) {
		array_push($this->cc_receiver, $emailUser);
		return $this;
	}

	public function removeCCReceiver(EmailUserInterface $emailUser) {
		$key = array_search($emailUser, $this->cc_receiver);
		if ($key !== false) {
			array_splice($this->cc_receiver, $key, 1);
		}
		return $this;
	}

	public function setCCReceivers($receivers) {
		$this->cc_receiver = $receivers;
		return $this;
	}

	public function getCCReceivers() {
		return $this->cc_receiver;
	}

	public function addParameter($key, $value) {
		$this->parameters[$key] = $value;
		return $this;
	}

	public function removeParameter($key) {
		unset($this->parameters[$key]);
		return $this;
	}

	public function setParameters($parameters) {
		$this->parameters = array_merge($this->parameters, $parameters);
		return $this;
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function setHtml($isHtml) {
		$this->isHtml = $isHtml;
		return $this;
	}

	public function isHtml() {
		return $this->isHtml;
	}

	public function setBody($body) {
		$this->body = $body;
		return $this;
	}

	public function getPlainBody() {
		return $this->body;
	}

	public function getBody($parameters = array()) {
		return $this->twig->render($this->body, array_merge($parameters, $this->parameters));
	}

	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}

	public function getPlainSubject() {
		return $this->subject;
	}

	public function getSubject($parameters = array()) {
		return $this->twig->render($this->subject, array_merge($parameters, $this->parameters));
	}

	public function send() {
		$params = array();
		$params["method"] = "POST";
		$params["from"] = $this->getSender()->getEmailString();

		if (count($this->receiver) > 1) {
			foreach ($this->receiver as $receiver) {
				$params["to"][] = $receiver->getEmailString();
			}
		} else {
			$params["to"] = $this->receiver[0]->getEmailString();
		}

		if (count($this->cc_receiver) > 1) {
			foreach ($this->cc_receiver as $receiver) {
				$params["cc"][] = $receiver->getEmailString();
			}
		} else {
			$params["cc"] = $this->cc_receiver[0]->getEmailString();
		}

		$params["subject"] = $this->getSubject();
		$params[($this->isHtml) ? 'html' : 'text'] = $this->getBody();

		$mj = new Mailjet($this->apiKey, $this->secretKey);
		$mj->sendEmail($params);
		$success = ($mj->_response_code == 200);
		return $success;
	}

	public function sendOneByOne() {
		$success = array();
		$receivers = array_merge($this->receiver, $this->cc_receiver);
		foreach ($receivers as $receiver) {
			$params = array();
			$params["method"] = "POST";
			$params["from"] = $this->getSender()->getEmailString();
			$params["to"] = $receiver->getEmailString();
			$params["subject"] = $this->getSubject(array('user' => $receiver));
			$params[($this->isHtml) ? 'html' : 'text'] = $this->getBody(array('user' => $receiver));

			$mj = new Mailjet($this->apiKey, $this->secretKey);
			$mj->sendEmail($params);
			$success[$receiver->getEmail()] = ($mj->_response_code == 200);
		}
		return $success;
	}

}
