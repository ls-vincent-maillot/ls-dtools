build:
	docker-compose up --build -d

up:
	docker-compose up --force-recreate -V -d

down:
	docker-compose down

clean-files:
	rm -r ./public/files/*.csv

