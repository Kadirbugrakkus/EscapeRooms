<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Escape Room Reservation System

Welcome to the Escape Room Reservation System! This project provides a platform for users to book and manage reservations for escape rooms.

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Getting Started](#getting-started)
- [Installation](#installation)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Escape Room Reservation System is a web application that allows users to browse available escape rooms, make reservations, and manage their bookings. The system also provides a convenient API for integrating with other applications.

## Features

- User registration and authentication
- View available escape rooms and their details
- Make and manage reservations
- Apply discounts for special occasions (e.g., birthday discounts)
- Room status management (availability)
- API endpoints for integration

## Getting Started

To get started with the Escape Room Reservation System, follow these steps:

1. Clone the repository: `git clone https://github.com/your-username/escape-room-reservation.git`
2. Install dependencies: `composer install`
3. Create a `.env` file by copying `.env.example`: `cp .env.example .env`
4. Generate an application key: `php artisan key:generate`
5. Configure your database settings in the `.env` file
6. Migrate the database: `php artisan migrate`
7. Run the development server: `php artisan serve`

## Installation

1. Clone the repository:
   ```shell
   git clone https://github.com/your-username/escape-room-reservation.git

## Setup

1. Change to the project directory:
   ```shell
   cd escape-room-reservation
   
2. Install dependencies using Composer:
    ```shell
   composer install
   
3. Create a .env file by copying .env.example:
    ```shell
   cp .env.example .env


4. Generate an application key:
   ```shell
   php artisan key:genarate
   
5. Configure your database settings in the .env file.

6. Migrate the database:
    ```shell
   php artisan migrate
   
7. Run the development server:
    ```shell
   php artisan serve

Usage
Register or log in to your account.
Browse available escape rooms and their details.
Make reservations for your desired time slots.
Manage your reservations and view their details.
Cancel reservations if needed.
Enjoy your escape room experience!
API Documentation
The Escape Room Reservation System provides a RESTful API for integration with other applications. API documentation can be found here.

Contributing
Contributions to the Escape Room Reservation System are welcome! If you find any issues or have suggestions for improvements, please feel free to create a pull request or submit an issue.

License
This project is licensed under the MIT License.
