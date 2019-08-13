# Laravel Vue Vuetify for SISCOCA
built with Laravel 5.7, JWT Auth, Vue 2, Vue Router 3, Vuex 3, Axios, Vuetify 1.3

## Included
* [Laravel 5.7](https://laravel.com/docs/5.7)
* [Vue 2](https://vuejs.org)
* [Vue Router 3](http://router.vuejs.org)
* [Vuex 3](http://vuex.vuejs.org)
* [Axios](https://github.com/mzabriskie/axios)
* [Authentication with JWT Token](https://github.com/tymondesigns/jwt-auth)
* [Vuetify](https://vuetifyjs.com/en/getting-started/quick-start)

## Installation:
* Clone the repo
* Copy `.env.example` to `.env`
* Configure `.env`
* Make sure `storage/framework/cache`, `storage/framework/sessions`, `storage/framework/views` directories exist. Run `mkdir -p storage/framework/{sessions,views,cache}`
* Run `composer install`
* Run `php artisan key:generate`
* Run `php artisan jwt:secret`
* Run `php artisan migrate` 
* Run `npm install`
    or run `yarn`
## Usage
* Run `npm run hot` for live reloading
* Run `npm run prod` for production buid

## Bonus
* Lightweight [Vuetify Toast Snackbar](https://github.com/eolant/vuetify-toast-snackbar)
* Checkout this [Confirm Dialog Component](https://gist.github.com/eolant/ba0f8a5c9135d1a146e1db575276177d) that you can add and extend in your application
