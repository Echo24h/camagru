all: run

run:
	docker-compose up --build

stop:
	docker-compose stop
	docker-compose down -v --remove-orphans

clean: stop
	docker system prune -af
	sudo rm -rf frontend/node_modules
	sudo rm -rf frontend/.angular
	sudo rm -rf backend/node_modules
	sudo rm -rf backend/dist

re: clean all

.PHONY: all run stop clean re