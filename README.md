<!--
 * @Author: Anwarul
 * @Date: 2025-11-17 14:53:56
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-05 15:06:56
 * @Description: Innova IT
-->
## Instruction Install Process 
1. composer update
2. cp .env.example .env
3. php artisan key:generate
4. configure database on .env file(create a database and give database credential)
5. php artisan migrate
6. php artisan db:seed
7. npm install npm run dev php artisan serve(If you Need)

php artisan make:fullmodel Course
