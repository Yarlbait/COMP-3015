# Terminal 1 (storage)
cd part2/file_storage_service
composer install
php -S localhost:4000 -t public

# Terminal 2 (user)
cd part2/user_service
composer install
php -S localhost:9000 -t public

# Test (PowerShell)
curl.exe -F "image=@C:\Users\earlb\Downloads\lebron.png" http://localhost:9000/upload