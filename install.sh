cp ./src/setup.sh ./src/laraapp/
docker-compose up -d && echo "Please wait while service is up..." && sleep 5 && docker exec myapp-web /var/www/laraapp/setup.sh && echo "All done"
