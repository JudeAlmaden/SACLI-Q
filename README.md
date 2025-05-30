# Setup Database
Database>seeders>databaseSeeder

# Resets the database
php artisan db:fresh or
php artisan migrate:fresh

# Fills database with initial data
php artisan db:seed 


# To start server
# 1. Start server and make sure it is available through the local network
php artisan serve --host=0.0.0.0 --port=8000                    
# 2. Open a new terminal and run the following command to start the server for reverb, it is used to synchronous operations
php artisan reverb:start --host=0.0.0.0 --port=8080 --debug
# 3. Open a new terminal and run the following command to run NPM to start up vite and other dependencies
npm run dev
# 4. For advertisement and media files to work, you also need to run
php artisan storage:link



# NOTES
-- Check .env for mysql configuration
-- Run " php artisan migrate:fresh"  to initialize the database
-- Run " php artisan db:seed" to create the admin account, 
-- Initial Account credentials are 
    ID: 0000-0001 and Password: Password
-- New users can only reset their account through the admin
-- Ticket Info is at ip:port/Sacli-Q.com/info
-- User Login is at ip:port/Sacli-Q.com/login

# Ensure the ip is correct for .env and vite.config.js files 
# Change connection to not use DHCP to prevent changing IP addresses
APP_URL=http://192.168.1.6:8000   
REVERB_HOST='192.168.0.140'

server:{
    host: '0.0.0.0',
    port: 8880,
    strictPort: true,
    hmr:{
        host:'192.168.0.140', 
    }
},