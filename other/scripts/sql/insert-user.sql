INSERT INTO MYSQL.USERS (
    MYSQL.USERS.USERNAME,
    MYSQL.USERS.DATE_CREATED,
    MYSQL.USERS.LM_DATE,
    MYSQL.USERS.PASSWORD
) VALUES
('TEST USER 1', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'TEST PASSWORD 1'),
('TEST USER 2', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'TEST PASSWORD 2'),
('TEST USER 3', CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 'TEST PASSWORD 3');