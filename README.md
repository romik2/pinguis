Pinguis
=========

INSTALLATION
------------
    sudo apt-get update
    sudo apt install docker.io docker-compose 
    git clone git@github.com:romik2/pinguis.git
    sudo docker-composer up --build
    sudo docker-compose exec php /app/bin/console migrate
    sudo docker-compose exec php /app/bin/console app:create-user <email> <password>

UPDATE
------------
    git fetch --all
    git checkout <version>
    sudo docker-compose exec php /app/bin/console migrate
    sudo docker-composer up --build