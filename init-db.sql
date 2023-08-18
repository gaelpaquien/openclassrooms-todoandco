CREATE DATABASE IF NOT EXISTS todo_and_co;
CREATE DATABASE IF NOT EXISTS todo_and_co_test;
GRANT ALL PRIVILEGES ON todo_and_co.* TO 'todo'@'%';
GRANT ALL PRIVILEGES ON todo_and_co_test.* TO 'todo'@'%';
FLUSH PRIVILEGES;
