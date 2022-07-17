FROM THIS:
https://github.com/lubo13/opentag-exchange-rate


1.
git clone https://github.com/Gogeruk/junior_php_dev_OpenTag_test_task_mykyta_korsikov

2.
docker-compose up -d --build

3
docker ps

4.
get id
>>>>899776579d9b<<<<   junior_php_dev_opentag_test_task_mykyta_korsikov_php

5.
docker exec -it <id> /bin/bash

6.
cp .env.example .env

7.
composer install

8.
npm install --force
(npm run watch&)

9.
bin/console doctrine:migrations:migrate

10.
chmod 777 cache


NOTES:
As per task, trend will not show until there are 10 rows in db
As per task, trend calculation is simple
As per task, API returns just <RATE> <TREND>


UI:
http://localhost:8080/currency/index
http://localhost:8080/currency/new

API:
bare bones api

curl \
  --request POST \
  --data '{"from":"USD","to":"CNY"}' \
  http://localhost:8080/api/currency/new

TESTS:
I want to sleep, maybe will add later. You know, to feel accomplished and stuff...

BONUS POINTS:
Check cache/ directory
Again, the cache mechanism is bare-bones.



