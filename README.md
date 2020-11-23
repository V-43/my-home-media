<h1>MyHomeMedia</h1>
MyHomeMedia - демо-проект для создания своей БД любимых фильмов.
<h2>Как пользоваться</h2>
<ul>
    <li>Вводите желаемый фильм в поисковую строку, запрос уходит на kinopoisk.ru и выводится список найденных фильмов.</li>
    <li>При выборе фильма отображается либо страница для добавления фильма (если такого фильма еще нет в базе), либо страница для просмотра.</li>
    <li>На странице добавления файла можно все параметры фильма указать вручную</li>
</ul>
<h2>Установка</h2>
Ниже приведен пример установки проекта на OS Ubuntu.

<pre><code># клонируем исходный код проекта
git clone git@github.com:V-43/my-home-media.git
cd my-home-media/

# устанавливаем зависимости
composer install
npm install

# производим базовую настройку
cp .env.example .env
php artisan key:generate
php artisan storage:link

# в файле .env следует задать параметры для подключения к БД, например, так:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=my_home_media
DB_USERNAME=root
DB_PASSWORD=masterkey

# наполняем БД:
php artisan migrate --seed

# запускаем проект на http://localhost:8000/
php artisan serve</code></pre>
