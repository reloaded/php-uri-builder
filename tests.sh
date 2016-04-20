#!/usr/bin/env sh
./vendor/bin/codecept build -c codeception.yml -f
./vendor/bin/codecept run -c codeception.yml -f