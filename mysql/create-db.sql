CREATE DATABASE FILE_MANAGER;

USE FILE_MANAGER;

CREATE TABLE FILE_MANAGER.USERS_GROUPS (
ID                    INT UNSIGNED NOT NULL AUTO_INCREMENT,
DESCRIPTION           VARCHAR(100),
DATE_CREATED          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
LM_DATE               TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CONSTRAINT PK_USERS_GROUP PRIMARY KEY (ID)
);

CREATE TABLE FILE_MANAGER.USERS (
ID                    INT UNSIGNED AUTO_INCREMENT,
USERNAME              VARCHAR(100) NOT NULL,
PASSWORD              VARCHAR(60) NOT NULL,
EMAIL                 VARCHAR(100) NOT NULL UNIQUE,
GENDER                CHAR(1) NOT NULL DEFAULT 'U',
AGE                   INT UNSIGNED NOT NULL,
GROUP_ID              INT UNSIGNED NOT NULL,
STATUS                CHAR(2) NOT NULL DEFAULT 'CL',
DATE_CREATED          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
LM_DATE               TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
CONSTRAINT PK_USER PRIMARY KEY (ID),
FOREIGN KEY (GROUP_ID) REFERENCES USERS_GROUPS (ID) ON DELETE CASCADE
);

SET AUTOCOMMIT = 0;

INSERT INTO FILE_MANAGER.USERS_GROUPS (
FILE_MANAGER.USERS_GROUPS.DESCRIPTION
)
VALUES
('Application administrator'),
('Content author'),
('Regular user');
COMMIT;

CREATE USER 'file_manager'@'%' IDENTIFIED BY 'file_manager';
GRANT ALTER, SELECT, INSERT, UPDATE, DELETE ON FILE_MANAGER.USERS TO 'file_manager'@'%';
FLUSH PRIVILEGES;