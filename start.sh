# Start Apache in the foreground
apache2-foreground &

# Wait for Apache to start
sleep 5

# Log a message to indicate the start of Apache
echo "start.sh : Apache started in the foreground"

# Wait for MySQL to start
until nc -z -v -w30 database 3306
do
  echo "start.sh : Waiting for MySQL to start to continue"
  sleep 5
done

# Log a message to indicate the start of MySQL
echo "start.sh : MySQL started, the script resumes"

# Change directory to the project root
cd /var/www/html

# Install Composer dependencies
composer install

# Build database
php bin/console doctrine:database:create --env=dev
php bin/console doctrine:schema:create --env=dev
echo "yes" | php bin/console doctrine:fixtures:load --env=dev

# Log a message to indicate the end of the script
echo "start.sh : End of script execution, container ready"

# Keep container running
tail -f /dev/null