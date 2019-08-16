build:
	docker-compose up --build -d

up:
	docker-compose up --force-recreate -V -d \
	&& open http://127.0.0.1:7331

down:
	docker-compose down

clean-files:
	rm -r ./public/files/*.csv

