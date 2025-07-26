# core-php
Interview Task 


Hi,
Kindly acknowledge the mail.

Create Table using below query.

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password  VARCHAR(255) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description  VARCHAR(255) NOT NULL,
        price  int(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );


    2. Task : create a PHP script that handles user authentication for a simple web application .
    
        The script should include the following features:
            1.User registration with fields like username, email, and password.
            2.Password storage in the database.
            3.User login functionality with appropriate session management.
            4.A "Forgot Password" feature that allows users to reset their passwords via email (Consider email OTP as static 1111).
            5.Access control to certain pages or features for authenticated users only.
            6.  Show user list with add , edit , delete
            7.  Show products list with add , edit , delete


    3. Task : Building a PHP script that serves as a simple API endpoint for managing a collection of   products.

    The API should support:
        1.Retrieving a list of items.
        2.Retrieving details of a specific item.
        3.Adding a new item.
        4.Updating an existing item.
        5.Deleting an item.
