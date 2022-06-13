# db-course-project
 
<h1>The Places</h1>

<h2>Web project based on Symfony5 and MySQL db.</h2>
<h2>This project was created for "Database course" in "Volga State University of Technology"
<h3>Main idea of the project to show rate of the places with grouping by categories</h3>
<h3>We also can see pictures and marker of the place (cafe, bar etc.) on the map</h3>
<h3>Last step for this course was to deploy the project on Google Cloud</h3>
<h4>(use \app.yaml for deploy app on Google Cloud)</h4>
<h3>(RUS:Проект отображает рейтинг заведений, с сортировкой по категориям. Курс "Базы данных" в "ПГТУ")</h3>
<ul>
 <li>set DATABASE_URL in \.env
 <li>composer i (PHP 7.4)
 <li>php bin/console doctrine:database:create
 <li>php bin/console doctrine:migrations:migrate
 <li>Add role ('ROLE_USER', 'ROLE_ADMIN') to DB
 <li>Add category ('bar', 'cafe') to DB
 <li>Install Symfony https://symfony.com/download ('symfony-cli' (5.4.11) [64bit])
 <li>symfony server:start -d

</ul>

![ER](https://github.com/saintriko/db-project/blob/master/er_diagram.png)
![Home](https://github.com/saintriko/db-project/blob/master/places.PNG)
![PlacePage](https://github.com/saintriko/db-project/blob/master/place.png)

