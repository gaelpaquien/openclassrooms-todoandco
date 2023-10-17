CREATE DATABASE IF NOT EXISTS training_todoandco;
CREATE DATABASE IF NOT EXISTS training_todoandco_test;
GRANT ALL PRIVILEGES ON training_todoandco.* TO 'admin_gls'@'%';
GRANT ALL PRIVILEGES ON training_todoandco_test.* TO 'admin_gls'@'%';
FLUSH PRIVILEGES;
