# Laravel Project README

## Project Description

This Laravel project is designed to create a robust RESTful API for a web application focused on generating, storing, and sending invoices to clients. Key project details include:

* **Technology Stack:** The application is built using Laravel 8, a popular PHP framework known for its versatility and ease of use, and it utilizes a MySQL database to store and manage data efficiently.

* **Authentication:** User authentication for admin access is implemented using Laravel Passport, providing a secure and token-based authentication system.

* **Background Job Processing:** To enhance performance and responsiveness, the project leverages Laravel Queues for background job processing. This enables the system to handle tasks such as sending emails asynchronously, ensuring a smooth user experience.

* **Unit Testing:** The project includes a suite of unit tests to verify the functionality and reliability of the admin flow. These tests help maintain code quality and ensure that the application operates as intended.

With a focus on efficiency, security, and test-driven development, this Laravel application simplifies the process of managing invoices while offering a seamless experience for both administrators and end users.





----------

# Getting started

## Installation

Clone the repository

    git clone git@github.com:gothinkster/laravel-realworld-example-app.git

Switch to the repo folder

    cd invoicing-app

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate
Install a passport
    
    php artisan passport:install

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**command list**

    git clone git@github.com:gothinkster/laravel-realworld-example-app.git
    cd invoicing-app
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan passport:install
    php artisan serve
    

***Note*** : i used online host database so no need to migrate and use your local database

    

   
## Table of Contents

- [API Endpoints](#api-endpoints)
  - [User Registration](#user-registration)
  - [User Login](#user-login)
  - [Invoices](#invoices)
    - [Create Invoice](#create-invoice)
    - [Get Invoices by Phone or Email](#get-invoices-by-phone-or-email)
    - [Update Invoice](#update-invoice)
    - [Delete Invoice](#delete-invoice)
  - [Clients](#clients)
    - [Get All Clients with Pagination](#get-all-clients-with-pagination)
  - [Sending Emails Using Mailtrap and Queue](#sending-emails-using-mailtrap-and-queue)
    - [Configuration](#configuration)
- [Unit Tests](#unit-tests)



## API Endpoints

### User Registration

- **Description:** Register a new user.
- **HTTP Method:** POST
- **URL:** `http://127.0.0.1:8000/api/register`
- **Request Body:** JSON object with user registration data:
  
  ```json
  {
    "name": "abdo",
    "email": "abdo_a@yahoo.com",
    "password": "12345678",
    "isAdmin": true
  }


### User Login

- **Description:** Authenticate a user.
- **HTTP Method:** POST
- **URL:** `http://127.0.0.1:8000/api/login`
- **Request Body:** JSON object with user registration data:
  
  ```json
  {

    "email": "abdo_a@yahoo.com",
    "password": "12345678"
  }
- **Response:** Access token on successful login or error details.
-------------------
### Invoices

### Create Invoice

- **Description:** Create a new invoice associated with a client. Admins have the flexibility to choose an existing client by providing the `client_id`, or they can create a new client with the provided data. If a user with the same email address and phone number exists, the invoice will be added to the current client.

- **HTTP Method:** POST
- **URL:** `http://127.0.0.1:8000/api/invoices`
- **Request Body:** JSON object with invoice creation data.

  - `client_id` (optional): The ID of the existing client associated with the invoice.
  - `amount` (required): The amount of the invoice.
  - `due_date` (required): The due date of the invoice in the format 'Y-m-d'.
  - `full_name` (optional): The full name of the client if `client_id` is not provided.
  - `mobile_number` (optional): The mobile number of the client if `client_id` is not provided.
  - `email_address` (optional): The email address of the client if `client_id` is not provided.

- **Response:** If the invoice is created successfully, the response includes the created invoice data with a status code of 200. In case of validation failures or errors, appropriate error messages are returned.

  Example response for a successful invoice creation:

  ```json
  {
    "invoice": {
      "client_id": 4,
        "amount": 20.5,
        "due_date": "2023-09-30",
        "updated_at": "2023-09-17T16:46:51.000000Z",
        "created_at": "2023-09-17T16:46:51.000000Z",
        "id": 23
     
    }
  }
------------------------------------------------

#### Get Invoices by Phone or Email

### Get Invoices by Phone or Email

- **Description:** Retrieve invoices associated with a client by searching for their phone number or email address.
- **HTTP Method:** GET
- **URL:** `/api/invoices?search=abdo_201333@hotmail.com`
- **Query Parameter:**
  - `search` (required): The phone number or email address to search for.

- **Response:** If a client matching the provided phone number or email address is found, the response includes the list of invoices associated with that client with a status code of 200. If no matching client is found, a "Client not found" message is returned with a status code of 404.

  Example response for successful retrieval of invoices:

  ```json
  {
    "invoices": [
      {
        "id": 1,
        "client_id": 123,
        "amount": 1000,
        "due_date": "2023-12-31",

      },
      {
        "id": 2,
        "client_id": 123,
        "amount": 1500,
        "due_date": "2023-11-15",

      }
     
    ]
  }

------------------------------------------------------------------------
#### Update Invoice

### Update Invoice and Client Data

- **Description:** Update invoice and client data associated with an invoice.
- **HTTP Method:** PUT
- **URL:** `/api/invoices/{id}`
- **URL Parameters:**
  - `id` (required): The ID of the invoice to be updated.

- **Request Body:** JSON object with fields to update. You can include any combination of the following fields:

  For Invoice Update:
  - `amount` (optional): The updated amount of the invoice.
  - `due_date` (optional): The updated due date of the invoice in the format 'Y-m-d'.

  For Client Update:
  - `full_name` (optional): The updated full name of the client.
  - `mobile_number` (optional): The updated mobile number of the client.
  - `email_address` (optional): The updated email address of the client.

- **Response:** If the updates are successful, the response includes a message indicating that the data has been updated successfully with a status code of 200. If the specified invoice is not found, a "Invoice not found" message is returned with a status code of 404.

  Example request body for updating the invoice amount:

  ```json
  {
    "amount": 1500
  }

------------------------------------------------------------------
#### Delete Invoice

### Delete Invoice

- **Description:** Delete an existing invoice.
- **HTTP Method:** DELETE
- **URL:** `/api/invoices/{id}`
- **URL Parameters:**
  - `id` (required): The ID of the invoice to be deleted.

- **Response:** If the specified invoice is found and successfully deleted, the response includes a message indicating that the invoice has been deleted successfully with a status code of 200. If the specified invoice is not found, a "Invoice not found" message is returned with a status code of 404.

  Example response for successful invoice deletion:

  ```json
  {
    "message": "Invoice deleted successfully"
  }


### Clients

#### Get All Clients with Pagination

### Get All Clients with Pagination and Filtering

- **Description:** Retrieve a paginated list of clients with optional filtering by full name, mobile number, or email address.
- **HTTP Method:** GET
- **URL:** `/api/clients`
- **Query Parameters:**
  - `page` (optional): The page number for pagination (e.g., `/api/clients?page=2`).
  - `per_page` (optional): The number of results per page (default is 10).
  - `full_name` (optional): Filter clients by full name (e.g., `/api/clients?full_name=John`).
  - `mobile_number` (optional): Filter clients by mobile number (e.g., `/api/clients?mobile_number=1234567890`).
  - `email_address` (optional): Filter clients by email address (e.g., `/api/clients?email_address=johndoe@example.com`).

- **Response:** The response includes a paginated list of clients based on the applied filters and pagination settings.

  Example response with pagination:

  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "full_name": "John Doe",
        "mobile_number": "1234567890",
        "email_address": "johndoe@example.com",
        // Other client fields
      },
      {
        "id": 2,
        "full_name": "Mary Jane",
        "mobile_number": "9876543210",
        "email_address": "maryjane@example.com",
        // Other client fields
      }
      // More clients based on filters and pagination
    ],
    "from": 1,
    "last_page": 5,
    "links": {
      "first": "http://127.0.0.1:8000/api/clients?page=1",
      "last": "http://127.0.0.1:8000/api/clients?page=5",
      "next": "http://127.0.0.1:8000/api/clients?page=2",
      "prev": null
    },
    "path": "http://127.0.0.1:8000/api/clients",
    "per_page": 10,
    "to": 10,
    "total": 50
  }

## Sending Emails Using Mailtrap and Queue
In this project, I utilize [Mailtrap.io](https://mailtrap.io/) for email testing and debugging. Mailtrap.io allows us to send and preview emails without sending them to real email addresses. This is particularly useful during development and testing phases.

### Configuration

Before you can use Mailtrap.io for email testing, make sure you have set up your Mailtrap.io account and obtained your SMTP settings. Then, update your Laravel project's `.env` file with the following SMTP configuration:

    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=465
    MAIL_USERNAME=99c221b6a3db9c
    MAIL_PASSWORD=7fc212004e7cb9
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=abdulrahemanmanman@gmail.com
    MAIL_FROM_NAME="abdo"
Here's an example of how emails are sent in the "Create Invoice" endpoint:
![alt text](http://url/to/img.png)



## Unit Tests
I have included unit tests to ensure the functionality and reliability of this project. You can run the tests using PHPUnit. To execute the tests, run  the following command:
  ```bash
    php artisan test



