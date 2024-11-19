all: run

run:
	docker-compose up --build

stop:
	docker-compose stop

clean: stop
	docker-compose down -v --remove-orphans
	docker system prune -af

re: clean all

.PHONY: all run stop clean re