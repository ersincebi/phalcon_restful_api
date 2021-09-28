create table users
(
    id            int auto_increment,
    login         varchar(255)                          not null,
    password      varchar(255)                          not null,
    created_at    timestamp default current_timestamp() not null,
    constraint users_email_uindex
        unique (login),
    constraint users_id_uindex
        unique (id)
);

alter table users
    add primary key (id);



create table products
(
    id           int auto_increment,
    user_id      int          null,
    quantity     int          not null,
    address      varchar(255) not null,
    shippingDate timestamp    null,
    orderCode    varchar(50)  not null,
    constraint products_id_uindex
        unique (id),
    constraint products_users_id_fk
        foreign key (user_id) references users (id)
            on update cascade on delete cascade
);

alter table products
    add primary key (id);

INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (1, 1, 1, 'Turkey', null, '1a2b3c');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (2, 1, 2, 'Turkey', '2021-09-27 16:30:33', '2d3e4r');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (3, 2, 3, 'Turkey', null, '3f4f5g');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (4, 2, 4, 'Turkey', '2021-09-27 16:31:12', '4r3e21');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (5, 3, 5, 'Turkey', null, '34hk5j');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (6, 3, 6, 'Turkey', '2021-09-27 16:31:15', '2j349d');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (7, 4, 7, 'Turkey', '2021-09-27 16:31:17', '34n2ij');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (8, 4, 8, 'Turkey', null, 'j23ı45');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (9, 5, 9, 'Turkey', '2021-09-27 16:31:23', 'b53m4n');
INSERT INTO phalcon.products (id, user_id, quantity, address, shippingDate, orderCode) VALUES (10, 5, 10, 'Turkey', null, '5uy34u');

INSERT INTO phalcon.users (id, login, password, created_at) VALUES (1, 'customer1@mail.com', '$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy', '2021-09-27 13:29:47');
INSERT INTO phalcon.users (id, login, password, created_at) VALUES (2, 'customer2@mail.com', '$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy', '2021-09-27 13:29:47');
INSERT INTO phalcon.users (id, login, password, created_at) VALUES (3, 'customer3@mail.com', '$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy', '2021-09-27 13:29:47');
INSERT INTO phalcon.users (id, login, password, created_at) VALUES (4, 'customer4@mail.com', '$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy', '2021-09-27 13:29:47');
INSERT INTO phalcon.users (id, login, password, created_at) VALUES (5, 'customer5@mail.com', '$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy', '2021-09-27 13:29:47');
