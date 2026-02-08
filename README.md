# Laravel + Vue + Inertia

[Database structure](https://docs.google.com/document/d/1mU6k54LqMx3Na3sltwMysSk21pkYcIHSkAk1bopcOUQ/edit?usp=sharing)

Drop all tables and re-run all migrations with seeders

``php artisan migrate:fresh --seed``

To create new admin run console command `php artisan create-admin {login} {password}`, where {login} - your login, {password} - your password.