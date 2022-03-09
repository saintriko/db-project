# db-course-project
 
<h1>The Places</h1>

<h2>Web project based on symfony and MySQL db.</h2>
<h3>Main idea of the project to show rate of the places with grouping by categories</h3>
<h3>We also can see pictures and marker on the map</h3>
<h3>(RUS:Проект отображает рейтинг заведений, с сортировкой по категориям)</h3>
<ul>
<h3>Last step of this course was to deploy it on Google Cloud</h3>
 <li>(use \app.yaml for deploy app on Google Cloud)

<li>set DATABASE_URL in \.env
<li>php bin/console doctrine:database:create
<li>php bin/console doctrine:migrations:migrate
<li>ROLES ('ROLE_USER', 'ROLE_ADMIN')
 
![Иллюстрация к проекту](https://github.com/saintriko/db-project/blob/master/places.PNG)
