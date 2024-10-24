# Blog System Sample

## Installation

1. Download and install Herd.
3. Clone this repository to ~\Herd\blogs-test folder
4. Download and install MySQL.
5. Create a database named laravel-blogs-test
6. Create a user with root access to laravel-blogs-test
7. Place this user's credentials in config/database.php file, at rows 51 and 52 (mysql configuration)
8. Launch powershell and in ~\Herd\blogs-test folder, execute:
- composer install
- php artisan migrate:fresh --seed
- npm install
- npm run build
- herd open

## Usage

Herd will serve sample system at http://blogs-test.test

You can use emails from the users table just seed to login into blogger's accounts using password "password". Or you can register new accounts. No e-mail verification required. 

System's homepage shows recent posts from all the bloggers. By clicking blogger name near the title of the post you can get to his / her blog.

On the post page you can click the name of the author of the comment to see that person's blog.

You can always post something new or edit old posts in "My blog" (link is located in top-right corner of all pages for authenticated users).

You can assign categories to your new or existing posts using categories autocomplete in post editing form.

All categories assigned to posts are clickable and open list of all the posts in the system within same category.

Search works for title and body of the posts only, as requested.

Interface is implemented using Vue.JS 3, as agreed. Old blade templates can be found in resources/views folder.
