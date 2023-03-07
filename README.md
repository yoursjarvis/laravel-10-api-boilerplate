<a id="readme-top"></a>

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

# 🏢 OMS Project Overview

This is a complete application for managing everything in a Company. Like Manage Employees, Clients, Projects, live chat, etc. And this is totally API-based project. That means no frontend or client-side view available on this application. We will start building its front end using Nuxt 3 when its first stable version will release. For proper setup please follow all the steps mentioned below.

---

# 💾 Installation

Please check the official Laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x/)

-   Minimum PHP version required `^8.1`

Clone the repository

    git clone git@github.com:TanmoyDey777/oms-dev.git

Switch to the repo folder

    cd oms-dev

Switch to Develop branch before starting the development

    git checkout develop

Install all the dependencies using the composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations with some dummy data (**Set the database connection in [.env](.env) before migrating**)

    php artisan migrate --seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

---

# 🔑 Laravel Passport Installation

To get started, install Passport via the Composer package manager:

    composer require laravel/passport

Passport's service provider registers its own database migration directory, so you should migrate your database after installing the package. The Passport migrations will create the tables your application needs to store OAuth2 clients and access tokens:

    php artisan migrate

Next, you should execute the `passport:install` Artisan command. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create personal access and "password grant" clients which will be used to generate access tokens:

    php artisan passport:install

---

# 🎬 Generating Action Class

#### 🤖 Introduction

We use Action Class for simple and small reusable actions or functions.

To get started, let's create an Action. Actions typically live in the `app/Http/Action` directory. You may use the `make:action` Artisan command to generate a new Action:

    php artisan make:action TestAction

# ⚒️ Generating Service Class

#### 🤖 Introduction

We use Service Classes to avoid code clutters in the controller. Service classes are used for writing all kinds of the business logic of our application.

To get started, let's create a Service. Services typically live in the `app/Service` directory. You may use the `make:service` Artisan command to generate a new Service:

    php artisan make:service TestService

---

# 🌐 Generating Response Class

#### 🤖 Introduction

We use Response classes to avoid repetitive JSON Response code that we have to write in every function.

To get started, let's create a Response class. Response classes typically live in `app/Http/Response` directory. You may use `make:response` Artisan command to generate a new Response class:

    php artisan make:response TestResponse

---

# 📮Mail Config

To configure mail visit [mailtrap.io](https://mailtrap.io/inboxes) and sign-up using any google account. Then goto `My Inbox` and copy the following Credentials and paste it on [.env](.env) file.

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=tls

 <img src="./public/assets/img/readme/mailtrap.png" alt="mailtrap example img" />

---

# 🧑‍💻 Code overview

## 📢 Dependencies

-   [laravel/passport](https://laravel.com/docs/9.x/passport#main-content) - For authentication using `Bearer Token`

## 📁 Folder Structure

-   `app/Console/Commands` - Contains all the Commands files
-   `app/Console/Stubs` - Contains all Stubs files for the custom-made artisan commands
-   `app/Http/Action` - Contains all the Action functions
-   `app/Http/Controllers/Api` - Contains all the api controllers
-   `app/Http/Requests/Api` - Contains all the api form requests
-   `app/Models` - Contains all the Eloquent models
-   `app/Services` - Contains all the Custom made Service Classes
-   `config` - Contains all the application configuration files
-   `database/factories` - Contains the model factory for all the models
-   `database/migrations` - Contains all the database migrations
-   `database/seeds` - Contains the database seeder
-   `database/seeds/json` - Contains the database seeder json file
-   `routes` - Contains all the api routes defined in api.php file
-   `tests` - Contains all the application tests
-   `tests/Feature/Api` - Contains all the api tests

---

# 🔑 Authentication

This application uses `Laravel Passport` (Bearer Token) to handle authentication. The token is passed with each authentication request using the `Authorization` header with `Bearer Token` scheme.

---

# 🧪 Testing APIs

User Account Credentials

    username: jarvis
    password: 12345678

You can test the APIs using [Postman](https://www.postman.com/) or You can install the [RapidAPI Client](https://marketplace.visualstudio.com/items?itemName=RapidAPI.vscode-rapidapi-client) in your VS Code.

Run the laravel development server

    php artisan serve

The API can now be accessed at

    http://localhost:8000/api

Request headers

| **Required** | **Key**       | **Value**        |
| ------------ | ------------- | ---------------- |
| Yes          | Content-Type  | application/json |
| If Auth      | Authorization | Bearer Token     |

If you encounter an error like `personal access client not found. please create one` when trying to log in, then just run the following command

    php artisan passport:client --personal

---

After your contribution, don't forget to update the app version in the `config/app.php`.

<img src="./public/assets/img/readme/version.png" alt="version-update-image" />

---

<a href="#readme-top"><button class="button-86" role="button">Back to Top</button></a>

<style>
.button-86 {
  all: unset;
  width: 100px;
  height: 30px;
  font-size: 16px;
  background: transparent;
  border: none;
  position: relative;
  color: #f0f0f0;
  cursor: pointer;
  z-index: 1;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-86::after,
.button-86::before {
  content: '';
  position: absolute;
  bottom: 0;
  right: 0;
  z-index: -99999;
  transition: all .4s;
}

.button-86::before {
  transform: translate(0%, 0%);
  width: 100%;
  height: 100%;
  background: #ef3b2d;
  border-radius: 10px;
}

.button-86::after {
  transform: translate(10px, 10px);
  width: 35px;
  height: 35px;
  background: #ffffff15;
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
  border-radius: 50px;
}

.button-86:hover::before {
  transform: translate(5%, 20%);
  width: 110%;
  height: 110%;
}

.button-86:hover::after {
  border-radius: 10px;
  transform: translate(0, 0);
  width: 100%;
  height: 100%;
}

.button-86:active::after {
  transition: 0s;
  transform: translate(0, 5%);
}
</style>
