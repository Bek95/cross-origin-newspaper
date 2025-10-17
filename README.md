# cross-origin-newspaper
si vous avez docker lancer la commande docker compose up --build -d

pour communiquer avec l'api via Postman rentrer l'url http://localhost:8080/api/v1/articles
c'est un appel en GET, on peut y ajouter des "query param"
page:1
min_id:3
sort:id,asc
date:2025-10-15
created_after:2025-10-15T23:51:32+07:00
publish_date_gte:1730304142

nous pouvons créer des comptes d'utilisateurs via le formulaire d'inscription

afin d'avoir un compte admin avec tout les droits, jouer la commande "php artisan db:seed"
les idenfiants sont : 
    email : admin@example.com
    password : password
