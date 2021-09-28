all: build install

build:
	@docker-compose build
	@docker-compose up -d
	@docker ps
install:
	@docker-compose exec -T service_php composer install
	@docker-compose exec -T service_mysql mysql -u root -p phalcon < ./.docker/mysql/phalcon.sql
clean:
	@docker-compose down
	@docker system prune -af
	@docker volume prune -f
