```
composer install --ignore-platform-reqs
```
```
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
sail up -d
```
```
sail composer install
cp .env.example .env
sail artisan key:generate
sail artisan migrate:fresh --seed
```

- app: http://localhost
- phpmyadmin: http://localhost:8080
