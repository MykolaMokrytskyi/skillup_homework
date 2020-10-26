CREATE USER 'queryman'@'%' IDENTIFIED BY 'queryman_pwd';
GRANT ALL PRIVILEGES ON mysql.* TO 'queryman'@'%';
FLUSH PRIVILEGES;