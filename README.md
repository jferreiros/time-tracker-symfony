# Time Tracker

Time Tracker is a web-based application built using the Symfony framework. It allows users to efficiently track time spent on various tasks throughout the day. With a simple and clean user interface, users can start and stop tasks, view the list of completed tasks, and see the total time spent on tasks for the day.

## Features

- Start and stop task timers with one click.
- View a list of completed tasks.
- See the total time spent on tasks for the current day.
- Data persistence using a database.
- Command-line interface for creating and listing tasks.

## Getting Started

These instructions will get your copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- PHP 7.4 or higher
- Composer
- Symfony CLI
- Docker (optional for containerized setup)
- MySQL or another compatible database

### Installing

A step-by-step series of examples that tell you how to get a development environment running.

1. Clone the repository to your local machine:
    ```sh
    git clone https://github.com/jferreiros/time-tracker-symfony.git
    ```

2. Navigate to the project directory:
    ```sh
    cd time-tracker-symfony
    ```

3. Install dependencies using Composer:
    ```sh
    composer install
    ```

4. Start the local web server:
    ```sh
    symfony server:start
    ```

5. Visit `http://localhost:8000` in your web browser to view the application.


## Built With
- Symfony - The web framework used
- Doctrine - Object Relational Mapper (ORM)
- Twig - Templating engine
