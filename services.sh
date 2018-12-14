#!/usr/bin/env bash

i="0"

while sleep 1
do
PID=$(ps aux | grep 'daemon:price' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:price &>/dev/null &
fi

PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:signals &>/dev/null &
fi

PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:orders &>/dev/null &
fi

#PID=$(ps aux | grep 'ssh -D 1337' | grep -v grep | awk '{print $2}')
#if [[ -z $PID ]]; then
#    ssh -D 1337 -f -C -q -N root@46.4.153.191
#fi

done
