all: run

run:
	docker-compose up --build

stop:
	docker-compose stop

clean: stop
	docker-compose down -v --remove-orphans

fclean: clean
	docker system prune -af

re: fclean all

.PHONY: all run stop clean fclean re