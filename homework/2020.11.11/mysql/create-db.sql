CREATE DATABASE skillup_shop;
USE skillup_shop;

CREATE TABLE users (
    id                      SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name                    VARCHAR(100) NOT NULL,
    login                   VARCHAR(100) NOT NULL UNIQUE,
    email                   VARCHAR(100) NOT NULL UNIQUE,
    password                VARCHAR(60) NOT NULL,
    role                    ENUM('admin', 'user') DEFAULT 'user',
    is_deleted              CHAR(1) NOT NULL DEFAULT 'F',
    date_created            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE products_categories (
    id                      SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    description             VARCHAR(100) NOT NULL,
    parent_category_id      SMALLINT UNSIGNED NULL,
    is_deleted              CHAR(1) NOT NULL DEFAULT 'F',
    date_created            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk-products_categories-parent_category_id-products_categories-id`
                            FOREIGN KEY (parent_category_id) REFERENCES products_categories(id) ON DELETE SET NULL
);

CREATE TABLE products (
    id                      SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    description             VARCHAR(100) NOT NULL UNIQUE,
    price                   DECIMAL(8, 2) UNSIGNED NOT NULL,
    category_id             SMALLINT UNSIGNED NULL,
    stored_quantity         SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_deleted              CHAR(1) NOT NULL DEFAULT 'F',
    date_created            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk-products-category_id-products_categories-id` FOREIGN KEY(category_id)
                            REFERENCES products_categories(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id                      SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id                 SMALLINT UNSIGNED NOT NULL,
    is_deleted              CHAR(1) NOT NULL DEFAULT 'F',
    date_created            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk-orders-user_id-users-id` FOREIGN KEY(user_id) REFERENCES users(id)
                            ON DELETE RESTRICT
);

CREATE TABLE orders_items (
    order_id                SMALLINT UNSIGNED NOT NULL,
    product_id              SMALLINT UNSIGNED NOT NULL,
    ordered_quantity        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_deleted              CHAR(1) NOT NULL DEFAULT 'F',
    CONSTRAINT `fk-orders_items-order_id-orders-id` FOREIGN KEY(order_id) REFERENCES orders(id)
                            ON DELETE RESTRICT,
    CONSTRAINT `fk-orders_items-product_id-products-id` FOREIGN KEY(product_id) REFERENCES products(id)
                            ON DELETE RESTRICT
);

CREATE TABLE user_basket (
    user_id                 SMALLINT UNSIGNED NOT NULL UNIQUE,
    product_id              SMALLINT UNSIGNED NOT NULL UNIQUE,
    ordered_quantity        SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT `fk-user_basket-user_id-users-id` FOREIGN KEY(user_id) REFERENCES users(id)
                            ON DELETE RESTRICT,
    CONSTRAINT `fk-user_basket-product_id-products-id` FOREIGN KEY(product_id) REFERENCES products(id)
                            ON DELETE RESTRICT
);

CREATE USER 'shop_query_man'@'%';

GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.users TO 'shop_query_man'@'%';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.products_categories TO 'shop_query_man'@'%';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.products TO 'shop_query_man'@'%';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.orders TO 'shop_query_man'@'%';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.orders_items TO 'shop_query_man'@'%';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON skillup_shop.user_basket TO 'shop_query_man'@'%';

FLUSH PRIVILEGES;

#DROP DATABASE skillup_shop;
#REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'shop_query_man'@'%';
#DROP USER 'shop_query_man'@'%';