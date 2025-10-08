http://localhost/FUSSwebsitev2/ → loads index.php automatically.

http://localhost/FUSSwebsitev2/db.php → directly test DB connection.

http://localhost/FUSSwebsitev2/login.php → login page.

http://localhost/FUSSwebsitev2/register.php → registration.

http://localhost/FUSSwebsitev2/booking.php → bookings.

etc.


To get it running you gotta use
C:\xampp\php\php.exe -S localhost:8000 -t C:\xampp\htdocs\FUSSwebsitev2

cause it php -S only uses the php running on your system and not the XAMP one... so yea