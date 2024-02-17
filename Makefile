report:
	XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-html coverage tests && xdg-open coverage/index.html

test:
	./vendor/bin/phpunit --colors --testdox

current:
	./vendor/bin/phpunit --colors --group current --testdox