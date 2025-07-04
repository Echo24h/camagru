all: run

run:
	docker-compose up --build

stop:
	docker-compose stop

clean: stop
	docker system prune

fclean: clean
	docker-compose down -v --remove-orphans

re: clean all

.PHONY: all run stop clean fclean re
