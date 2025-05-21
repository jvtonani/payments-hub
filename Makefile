# 1. Sobe os containers com build
up:
	docker compose up -d --build

# 2. Executa os testes unitários
test:
	php vendor/bin/co-phpunit --prepend test/bootstrap.php -c phpunit.xml --colors=always

# 3. Executa testes com cobertura de código (Xdebug)
coverage:
	php -d xdebug.mode=coverage vendor/bin/co-phpunit \
		--prepend test/bootstrap.php \
		-c phpunit.xml \
		--colors=always \
		--coverage-html coverage/html \
		--coverage-clover coverage/clover.xml

# 4. Executa análise estática com PHP Mess Detector
static:
	docker run -it --rm -v $(PWD):/project -w /project jakzal/phpqa \
		phpmd app text cleancode,codesize,controversial,design,naming,unusedcode

# 5. Instala as dependências via Composer
install:
	composer install

# 6. Inicia o servidor Hyperf com hot reload (watcher)
start:
	php bin/hyperf.php server:watch

# 7. Acessa container da aplicação
container:
	docker exec -it payments-hub-app bash

#8. Roda migration
migration-fresh:
	php bin/hyperf.php migrate:fresh

#9. Stop all containers
stop:
	docker rm -v -f $(docker ps -qa)