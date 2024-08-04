<?php declare(strict_types = 1);

// phpcs:ignoreFile

is_string(getenv('TEST_TOKEN'))
	? define('TEMP_DIR', __DIR__ . '/../var/tools/PHPUnit/tmp/' . getmypid() . '-' . md5((string) time()) . '-' . getenv('TEST_TOKEN') ?? '')
	: define('TEMP_DIR', __DIR__ . '/../var/tools/PHPUnit/tmp/' . getmypid() . '-' . md5((string) time()));

if (@!include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Tester using `composer update --dev`';
	exit(1);
}
