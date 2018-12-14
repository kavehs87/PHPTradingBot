# PHPTradingBot


PHPTradingBot is a modular platform written in php using Laravel to automatically trade on popuplar cryptocurrency exchanges

# Features

  - Trade Signals API (Mining Hamster)
  - Floating StopLoss/TakeProfit
  - Binance Exchange support
  - Daemonized (order Daemon, Price Daemon, Signal Daemon, optional socks5 proxy daemon for binance)
  - Module Hook Functions
  -- onTick()
  --  OnSignalReceived()
  -- beforeSell()
  -- beforeBuy()
  -- AfterSell()
  -- AfterBuy()
    See /App/Modules/ProfitClone.php for example usage



### Installation

PHPTradingBot requires PHP v7.X to run.

follow these command sequence below to install PHPTradingBot

```sh
$ git clone https://github.com/kavehs87/PHPTradingBot.git
$ cd PHPTradingBot
$ composer install
$ cp .env.example .env
# set your database parameters in the .env file if you get error about APP_KEY try running this command 
$ php artisan key:generate 
```

To Start Daemons

```sh
$ cd PHPTradingBot
$ sh services.sh
# runs and watches following commands
# php artisan daemon:signals
# php artisan daemon:price
# php artisan daemon:orders
```

To Run Development Server

```sh
php artisan serve
```

Verify the deployment by navigating to your server address in your preferred browser.

```sh
127.0.0.1:8000
```

### Todos

 - More exchanges support
 - More Trade Signal provider support

License
----

MIT


**Free Software, Hell Yeah!**
