# SACLI-Q
## USER MANUAL

**SY: 2024-2025**

---

## System Setup

### Prerequisites
Before setting up the project, make sure the following tools are installed on your system:
- **XAMPP** – for Apache and MySQL server
- **Composer** – PHP dependency manager
- **Visual Studio Code** – recommended code editor

### Installation Guide

#### 1. Download the Project Source Code
- Visit the GitHub repository: [https://github.com/JudeAlmaden/SACLI-Q](https://github.com/JudeAlmaden/SACLI-Q)
- Click the green "Code" button and select "Download ZIP"
- Extract the ZIP file
- Move the extracted folder into your `xampp/htdocs` directory
- Open the project in **Visual Studio Code**

#### 2. Configure the Environment
- Update `.env` File
- Open the `.env` file in the root of the project and update the following values:

```env
APP_URL=http://192.168.1.12:8000   <-- Assign with device local IP

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Sacli_Queue
DB_USERNAME=root
DB_PASSWORD=

REVERB_HOST=192.168.1.12            <-- Assign with device local IP
```

- Update `vite.config.js`. Locate and update the file with your local IP address:

```javascript
server: {
    host: '0.0.0.0',
    port: 8880,
    strictPort: true,
    hmr: {
        host: '192.168.1.12'        <-- Assign with device local IP
    }
}
```

#### 3. Application & Database Setup
- Open a terminal in Visual Studio Code (inside the project directory)
- Run the following commands one by one:

```bash
composer install
php artisan migrate:fresh
php artisan db:seed
rm public/storage
php artisan storage:link

```

> **NOTE:** The **Admin** login credentials are defined in the seeder file `SACLIQueue/database/seeders/DatabaseSeeder.php`

#### 4. Running the Program
- Open three new terminals in Visual Studio Code
- Run the following commands in separate terminals:

Terminal 1:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Terminal 2:
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

Terminal 3:
```bash
npm run dev
```

___The application should now be up and running on your local network___

---

## User Guide

### Logging in
The login page is found at `http://192.168.x.x:8000/Sacli-Q.com/login` (where IP and port match your host server).

![Login Page](Documentation/images/image1.png)

### Creating and Managing Accounts
Accounts can be created by the admin by visiting the "Manage Accounts" section on the side panel.

![Manage Accounts](Documentation/images/image2.png)
![Create Account Form](Documentation/images/image3.png)

Clicking the "Create account" Button will provide a modal form for account creation.

![Account Creation Modal](Documentation/images/image4.png)

In the event of forgotten passwords and login credentials, Contact the system admin to change your password.

![Forgot Password](Documentation/images/image5.png)

### Managing Queues
Queues are made by the system admin; users are assigned to a window wherein multiple users can have access to the same window.

![Manage Queues](Documentation/images/image6.png)

_List of queues_
![List of Queues](Documentation/images/image7.png)

_Creating a new queue_
![Create Queue](Documentation/images/image8.png)

_Switching tabs_
![Switch Tabs](Documentation/images/image9.png)

### Analytics Section
Users with access to any window are added here and their privileges can be modified.

![Analytics](Documentation/images/image10.png)

### User Access Section
Copy Links have the url for live view and ticketing.

![User Access](Documentation/images/image11.png)

### Links Section

![Links 1](Documentation/images/image12.png)
![Links 2](Documentation/images/image13.png)

- URL for live queue
- URL for ticketing

#### Live Queue View
The live queue view displays the current queue status in real-time for public display.

![Live Queue View](Documentation/images/live.png)

### Windows Section
Contains the list of windows associated with your queue. You can make one or view a window to assign users to have access on that window.

![Windows Section List](Documentation/images/image14.png)

### Advertisement Section
This is where the user can insert files to be displayed for live viewing.
**NOTE:** This **OVERWRITES** the existing files so please be careful.
![Advertisement Section](Documentation/images/image15.png)

_Clicking Select Files_
![Select Files](Documentation/images/image16.png)

> **NOTE:** Uploads can take time for videos.
> **XAMPP Limits uploads to 20mb.** This can be changed by Opening the XAMPP Control Panel → clicking Config next to Apache → select `php.ini`. And changing the following values:
> - `upload_max_filesize = 20M` → change to 100M or more
> - `post_max_size = 20M` → change to 100M or more
> - `memory_limit = 128M` → increase to 256M or more

### Managing Windows
A New window can be created for a queue, and old windows can be removed. When clicked, the admin can view accounts that have access to the window.
Description will be displayed for the ticketing to provide more information regarding the queue.

![Managing Windows](Documentation/images/image17.png)

**Additional Management Views:**
| | |
|---|---|
| ![View 1](Documentation/images/image18.png) | ![View 2](Documentation/images/image19.png) |
| ![View 3](Documentation/images/image20.png) | ![View 4](Documentation/images/image21.png) |
| ![View 5](Documentation/images/image22.png) | ![View 6](Documentation/images/image23.png) |
| ![View 7](Documentation/images/image24.png) | ![View 8](Documentation/images/image25.png) |
| ![View 9](Documentation/images/image26.png) | ![View 10](Documentation/images/image27.png) |
| ![View 11](Documentation/images/image28.png) | ![View 12](Documentation/images/image29.png) |
| ![View 13](Documentation/images/image30.png) | |

### Ticketing
The ticketing system allows customers to get a queue number and wait for their turn.

![Ticketing Page 1](Documentation/images/ticketing1.png)
![Ticketing Page 2](Documentation/images/ticketing2.png)

Select the ticket regardless of position in queue.

![Select Ticket](Documentation/images/image31.png)

> **Note:** To prevent "Camping" and cutting behavior. Require ID to be presented and match the name associated with the ticket.

### Activity Diagrams
![Activity Diagram 1](Documentation/images/image32.png)
![Activity Diagram 2](Documentation/images/image33.png)
![Activity Diagram 3](Documentation/images/image34.png)
![Activity Diagram 4](Documentation/images/image35.png)
![Activity Diagram 5](Documentation/images/image36.png)