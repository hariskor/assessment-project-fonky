# assessments/assessment-project


I generated the project using (Initializer for Laravel). 
To quickly setup the project, use the following script provided:

```shell
./initialize
```

## Local Development

This project uses
[Laravel Sail]
It provides a dockerized setup with a laravel installation, MySQL server.
(https://laravel.com/docs/sail) to manage
its local development stack. For more detailed usage instructions take a look at
the [official documentation](https://laravel.com/docs/sail).


### Links

- **The application will be launched at** http://localhost

### To Start the development server

```shell
./vendor/bin/sail up
```
### copy env file
.cp .env.example .env


### to interact with the command line inside the installation

eg.: Performing MIgrations
```shell
./vendor/bin/sail artisan migrate
```
You can also use the `-d` option, to start the server in
the background if you do not care about the logs or still want to use your
terminal for other things.

### To import the data
Add the csv to storage/app/ directory
run the command

```shell
./vendor/bin/sail artisan csv:readAndSave
```

### Compile frontend assets

```shell
./vendor/bin/sail npm run dev
```
alternatively build it so it doesn't occupy a terminal

### Run Tests

```shell
./vendor/bin/sail test
```
### Login
After the csv is imported, there are users auto-generated, according to the number of customers and sellers.
The emails are being generated with prefix 'buyer' or 'seller' accordingly, followed by an incrementing number, suffixed with '@email.com'
The password for all users is 'password' for convenience.

