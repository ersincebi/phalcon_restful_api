# phalcon-api
Implementation of an API application using the Phalcon Framework [https://docs.phalcon.io/3.4/en/tutorial-rest](https://docs.phalcon.io/3.4/en/tutorial-rest)

### Installation
- Clone the project
- In the project folder run `make all`
- Hit the IP address with postman
- if database not loads to the mysql, file is under ./docker/mysql/phalcon.sql

### Features
##### JWT Tokens
As part of the security of the API, [JWT](https://jwt.io) are used.

### Requerinments
- docker
- docker-compose
- make

### Usage

- The postman collaction file is inside main directory of the project
- The base will be `http://localhost:8081`

#### Requests
The routes available are:

| Method | Route              | Parameters                                      | Action                                                   | 
|--------|--------------------|-------------------------------------------------|----------------------------------------------------------|
| `POST` | `/api/login`       | `email`, `password`                             | Login - get Token                                        |
| `GET`  | `/api/products`      |                                                 | Retrieves all products                                     |
| `GET`  | `/api/products/2`    | Numeric Id                                      | Retrieves products based on primary key                    |
| `POST` | `/api/products`      | `quantity`,`address`,`shippingDate`,`orderCode` | Adds a new product                                         |
| `PUT`  | `/api/products/2`    | Numeric Id                                      | Updates products based on primary key                      |
|`DELETE`| `/api/products/2`    | Numeric Id                                      | Deletes products based on primary key                      |
                                             

