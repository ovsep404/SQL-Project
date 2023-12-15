# Gestion de bibliothèque

This project is a library management system built with PHP, JavaScript, and SQL. It provides a simple and intuitive interface for managing books and subscribers.

## Features

- **Book Management**: Search for books by title, author, publisher, and availability status.
- **Subscriber Management**: Search for subscribers by name, city, and subscription status. View detailed subscriber profiles, including borrowed books and suggested books based on borrowing history.
- **User Authentication**:
    - Login and registration system for users.
    - A table for storing user identifiers. It is necessary to be able to differentiate between "subscriber" and "manager" users.
    - A "subscriber" type user is associated with a subscriber.
- **Rights Management**:
    - "Subscriber" users will be able to view their file and modify their basic information: Date of birth, Address, Postal code, City and the "Subscriber" users will not able to view the search abonne page.

## Setup and Installation

1. Clone the repository to your local machine.
2. Set up a MySQL database and execute the `sudo mysql < init.sql` in the `requests` directory to add the table `utilisateurs` to your existent database.
3. Update the `db_connect.php` file in the `requests` directory with your database credentials.
4. Run the project on a PHP server.

## File Structure

- `index.php`: The main entry point of the application.
- `requests`: This directory contains all the PHP files for handling database requests.
- `view`: This directory contains all the PHP files for the application's views and `UpdateAbonneDetails.js & UpdateUserDetails.js`that allow to update modify the users informations.
- `css`: This directory contains all the CSS files for styling the application.
- `debug`: This directory contains the `debug.php` file for error handling and debugging.

## Technologies Used

- PHP
- JavaScript
- SQL
- CSS

