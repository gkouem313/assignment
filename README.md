# Assignment

This section provides an overview of the functionality of the backend API. The API is designed to manage a list of shops and send email notifications to users. Below, we describe the primary functionalities and how they work:

### Prerequisites:

- PHP
- Composer
- Docker

### Installation Steps:

Start by cloning the repository to your desired location and navigate to the project folder:

    git clone https://github.com/gkouem313/assignment.git
    cd assignment

Open a terminal window in the project directory and run the following command to install the necessary packages:

    composer install

This command will download and install all required dependencies and create the vendor directory.

Create a **.env** file based on the **env.example template**. You can define environment variables related to the containers we will create in this file. It is essential to generate the **.env** file before running the next command, as it will prevent errors during the MySQL container setup.

Run the following command to generate a key for your project:

    sail artisan key:generate

To create the required containers, execute the following command:
    
    sail up --build

If this command runs successfully, it means that the necessary containers have been created. In case you encounter errors about occupied ports, you may need to free up the port or stop a conflicting service, such as apache2, before running the command again. Make sure that you have created the **.env** file before proceeding.

To populate the database with initial data, you will need the **/database/see_sql/assignment.sql**:
1) Access the MySQL container by running sail mysql in a new terminal window and manually import the SQL file that contains the required tables and data.
2) Use phpMyAdmin, which was installed using the docker-compose.yml file. Access phpMyAdmin through your browser at **localhost:8888** or any other port you specified in the **docker-compose.yml**. Log in with the credentials you defined in the **docker-compose.yml** file.

Import the SQL file to create your tables and insert data.

Alternatively, if you prefer an automated approach, you can run the following command to create the tables and execute seeders:

    sail artisan migrate --seed

This command will create the tables and populate them with initial data.

To enable email notifications, you need to run a queue worker. Open a new terminal and navigate to your project's root directory. Run the following command to start the queue worker:

    php artisan queue:work

The command allows the background processing of tasks, such as sending email notifications, which are queued within the application.

Please note that you should make sure all the prerequisites are installed on your system, and **Docker** is up and running.

### Usage Instructions:

Within the project repository, you will find **/postman** folder that contains a postman collection and a postman environment. By importing these into Postman, you will have access to a collection containing all the required endpoints and example responses. The folder and endpoint names closely follow the API route URLs. All endpoints require authorization via a bearer token, with the exception of the two endpoints in the **/users** folder. To get started, select the **Owner** environment.

Begin by hitting the [POST] register endpoint to create a new user with the same credentials you intend to use for login. Next, access the [POST] login endpoint, and under the Authorization tab, select **Basic Auth**. Fill in the fields with the same credentials as before. This endpoint will generate a device token and store it in the environment. With the bearer token set under Authorization, you can now access all the other endpoints.

In the **/shops** folder, you will find endpoints for creating and editing shops. Additionally, there are four endpoints that provide essential information about the shops owned by a user. These are intended to assist with the development of the front-end environment. These endpoints are exclusively for the shops owned by the specific user and are not accessible by guests, as guests do not have accounts in the application. There are no endpoints to display shops from all owners.

Inside the **/offers** folder, you will find the endpoint for creating new offers. When an owner creates a new offer, emails are sent to all other platform owners to inform them. Given the potential for a large number of owners, a queue is used for generating and sending emails. To successfully send emails, you will need to define your SMTP server and queue connection in the **.env** file. In case of email delivery failure, two additional attempts are made. If the delivery still fails, an entry is created as a record in the failed jobs table in the database, where you can view the error message.

### Models

Overview of the model properties, their data types, and any additional information.

**User**

    id (integer): Unique identifier for the user.
    name (string, required): User's name.
    email (email, required): User's email address.
    password (password, required): User's password.
    created_at (timestamp): Timestamp of when the user was created.
    updated_at (timestamp): Timestamp of when the user was last updated.

**ShopCategory**

    id (integer): Unique identifier for the shop category.
    name (string, required): Name of the shop category.
    created_at (timestamp): Timestamp of when the shop category was created.
    updated_at (timestamp): Timestamp of when the shop category was last updated.

**Shop**

    id (integer): Unique identifier for the shop.
    user_id (foreign key): The ID of the user who owns the shop.
    shop_category_id (foreign key): The ID of the shop category to which the shop belongs.
    name (string, required): Name of the shop.
    description (text, required): Description of the shop.
    open_hours (string, required): Opening hours of the shop.
    city (string, required, indexed): City where the shop is located.
    address (string, nullable): Address of the shop (optional).
    created_at (timestamp): Timestamp of when the shop was created.
    updated_at (timestamp): Timestamp of when the shop was last updated.

**Offer**

    id (integer): Unique identifier for the offer.
    shop_id (foreign key): The ID of the shop for which the offer is created.
    name (string, required): Name of the offer.
    description (text, required): Description of the offer.
    created_at (timestamp): Timestamp of when the offer was created.
    updated_at (timestamp): Timestamp of when the offer was last updated.