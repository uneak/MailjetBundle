Uneak Mailjet Bundle
==================================

The Uneak Mailjet Bundle is a bundle used to help us to exploit the Mailjet API:

It includes an refactored version of Mailjet API client (found on https://www.mailjet.com/plugin/php-mailjet.class.php) with:

* PSR-0 standards
* A bundle configuration

## Prerequisites

This version of the bundle requires Symfony 2.1+.

## Installation

### Download UneakMailjetBundle using composer

Add UneakMailjetBundle in your composer.json:

```js
{
    "require": {
        "uneak/mailjet-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update uneak/mailjet-bundle
```

Composer will install the bundle to your project's `vendor/uneak` directory.

### Enable the Bundle


Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Uneak\MailjetBundle\MailjetBundle(),
    );
}
```

### Configure Mailjet

In your config.yml:

``` yml
mailjet:
    api_key: %mailjet_api_key%
    api_secret: %mailjet_api_secret%
```


### Usage

The API is available with the "mailjet.email" service.
In your controller (or elsewhere):

``` php
		$email = $this->get("mailjet.email");
		$email
				->setSender(new EmailUser("some@email.com", "Some Name"))
				->addReceiver(new EmailUser("some@email.com", "Some Name"))
				->addReceiver(new EmailUser("some@email.com", "Some Name"))
				->addCCReceiver(new EmailUser("some@email.com", "Some Name"))
				->setSubject("{{ hello }} {{ user.name }} at {{ user.email }}")
				->setBody("SomeBundle:Mail:test.html.twig")
				//->setBody("Hi {{ user.name }} at {{ user.email }}")
				->addParameter('hello', 'Salut')
				->setHtml(true)
				->sendOneByOne()
				;
```
or
``` php
		$email = $this->get("mailjet.email");
		$email
				->setSender(new EmailUser("some@email.com", "Some Name"))
				->addReceiver(new EmailUser("some@email.com", "Some Name"))
				->addReceiver(new EmailUser("some@email.com", "Some Name"))
				->addCCReceiver(new EmailUser("some@email.com", "Some Name"))
				->setSubject("{{ hello }} everybody")
				->setBody("SomeBundle:Mail:test.html.twig")
				//->setBody("Hello everybody")
				->addParameter('hello', 'Hi')
				->setHtml(true)
				->send()
				;
```
