#!/usr/bin/env bash

if [ $1 = "daemon" ]; then
    echo "daemon"
    while sleep 1
    do
    PID=$(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        php artisan daemon:ticker &>/dev/null &
    fi

    PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        php artisan daemon:signals &>/dev/null &
    fi

    PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        php artisan daemon:orders &>/dev/null &
    fi

#    PID=$(ps aux | grep 'ssh -D 1337' | grep -v grep | awk '{print $2}')
#    if [[ -z $PID ]]; then
#        ssh -D 1337 -f -C -q -N root@149.28.135.20
#    fi

    done

fi

if [ $1 = "status" ]; then
    echo "status ..."
    PID=$(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        echo "ticker Daemon Stopped"
        else
        echo "ticker Daemon Running"
    fi

    PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        echo "Signals Daemon Stopped"
        else
        echo "Signals Daemon Running"
    fi

    PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        echo "Orders Daemon Stopped"
        else
        echo "Orders Daemon Running"
    fi

    PID=$(ps aux | grep 'ssh -D 1337' | grep -v grep | awk '{print $2}')
    if [[ -z $PID ]]; then
        echo "Tunnel Daemon Stopped"
        else
        echo "Tunnel Daemon Running"
    fi

fi

if [ $1 = "restart" ]; then
    pkill -f "php artisan daemon:"
    echo "restarted"
fi

if [ $1 = "stop" ]; then
    pkill -f "php artisan daemon:"
    pkill -f "services.sh"
    echo "stopped"
fi

