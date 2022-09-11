# API Server Setup
- clone the git repo
- run ‘composer install’
- copy .env.example file to .env
- configure DB
- run php artisan:migrate -seed to generate dummy database
- run 'php artisan:test' to run feature test cases

# Sync data from packt API
- Run 'php artisan queue:listen'
- Run 'php artisan packt:sync' to run sync scripts. it will fetch data from packt API

Postman API collection resource and Documentation: https://documenter.getpostman.com/view/2234657/VVBXvjuW

Change enviorment variables of postman if needed

# Frontend Setup
Repo Link https://github.com/varuog/packt_demo_ui
- clone the git repo
- run ‘npm i’
- run ‘npm run start’