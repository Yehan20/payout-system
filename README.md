# Payout System

## Project Description
Automate payment completion

You can use the following test user to log in:

- **Email:** `admin@test.com`
- **Password:** `password`

## Features
- User Authentication
- Invoice Automation
- Currency Conversion
- Pagination


## Technology Stack
- Backend: Laravel (12)
- Frontend: Vue JS with TypeScript
- State management: Context Api
- Storage: AWS 
- UI Framework: Vuetify

## Testing
- PHP Unit Tests (Feature tests)

## Setup Instructions

### Prerequisites

Make sure you have the following installed:

- [PHP](https://www.php.net/) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) & [npm](https://www.npmjs.com/) — **Latest version required**
- [MySQL](https://www.mysql.com/) or compatible database; other options include [XAMPP](https://www.apachefriends.org/)


### First Steps

Open a terminal and clone this repo:

```bash
# Clone this repository
git clone 

# Go into the repository
cd payout-system

# Remove current origin repository
git remote remove origin

# If you want, you can add a new remote repository
git remote add origin https://github.com/<your-github-username>/<your-repo-name>.git
```

### Backend Setup

1. Navigate to backend folder:

   ```bash
   cd backend
   ```

2. Copy environment config:

   ```bash
   cp .env.example .env
   ```

3. Update `.env` with your database credentials:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_db_name
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. Install dependencies:

   ```bash
   composer install
   ```

5. Generate app key and create the storage link. Run the following commands one after another:

   ```bash
   php artisan key:generate
   php artisan storage:link
 
   ```

6. Run migrations and seeders  to get the application ready:

   ```bash
   php artisan migrate 
   ```


7. Start Laravel development server:

   ```bash
   php artisan serve --host localhost --port 8000
   ```

   The API will be available at `http://localhost:8000`.


### Exchange Rate API Setup

To get currency conversion rates for transactions, this project uses the [ExchangeRate.host](https://api.exchangerate.host/latest) API.

#### Steps to Configure

####  Api intergration to recive contry status code and payment reats
1. **Get an API Key**  
   - ExchangeRate.host is free and doesn’t strictly require an API key.  

###  Aws Configuration

In order to connect your file storage to the s3 bucket .firs create
and s3 bucket instance

#### 1. Create an S3 Bucket
- Go to your AWS Management Console → S3 → Create bucket.
- Give your bucket a unique name and choose the region.
- Configure options like versioning, encryption, and public access as needed.
- Click **Create bucket**.

#### 2. Create an IAM User
- Go to AWS Management Console → IAM → Users → Add user.
- Assign **Programmatic access**.
- Attach **AmazonS3FullAccess** policy (or a custom policy with required permissions).
- Save the **Access Key ID** and **Secret Access Key** securely.

#### 3. Configure Laravel `.env`
```env
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=your-bucket-region
AWS_BUCKET=your-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false
```
#### 4. Using Local Public Storage Instead of AWS
- If you are **not using AWS S3** and want to store files locally:
  1. Open `config/filesystems.php`.
  2. Change the default disk from `s3` to `public`:

  ```php
  'default' => env('FILESYSTEM_DRIVER', 'public'),

  ```

### Queue Worker Setup

Laravel uses queue workers to process jobs asynchronously, such as sending emails or handling payments. For this project, you should run **3 separate workers**:

| Worker Name | Queue Name  |
|------------|------------|
| Default    | default    |
| Payments   | payments   |
| Email      | email      |

#### Starting the Workers

Run each worker in a separate terminal:

```bash
php artisan queue:work --queue=default
php artisan queue:work --queue=payments
php artisan queue:work --queue=email
```

### Email Server Configuration 

1.In order to send emails in test server register for an account in mailtrap:
- [MailTrap](https://mailtrap.io/)


2. Once you register get the secret keys and paste then in the env file `.env` 

   ```env
   MAIL_MAILER= REPLACE WITH YOUR KEY
   MAIL_HOST= REPLACE WITH YOUR KEY
   MAIL_PORT= REPLACE WITH YOUR KEY
   MAIL_USERNAME= REPLACE WITH YOUR KEY
   MAIL_PASSWORD= REPLACE WITH YOUR KEY

   ```
3. Once All is configured you can run your queue worker to begin the waiting for any dispatched jobs 
   ```bash
   php artisan queue:work 

   ```
4. ⚠️ Warning:
If your application deals with a large volume of emails, test email providers like Mailtrap may reach limits. For heavy testing, it is recommended to use the log mail driver instead to prevent filling up the provider:

### Scheduler

To automatically send invoices every night, you need to run Laravel’s scheduler in a separate terminal or window.

#### Steps:

1. Open a new terminal/window in your project directory.  
2. Run the scheduler using the Artisan command:

```bash
php artisan schedule:work
```
#### Terminals Summary

You will need **6 terminals** in total:  

- Frontend  
- Backend  
- Queue Worker (default)  
- Queue Worker (payments)  
- Queue Worker (email)  
- Scheduler (for nightly invoice jobs)


### Generating Admin user

1. If you want to create an admin user you can use command and enter the options
   ```bash
   php artisan admin:generate {name} {email} {password} 
   ```
2. Then you can login with that user

### Frontend Setup

1. Navigate to frontend folder:

   ```bash
   cd frontend
   ```

2. Copy environment config:

   ```bash
   cp .env.example .env
   ```

3. Update `.env` with the backend API URL:

   ```env
   VITE_API_BASE_URL=http://localhost:8000/
   ```

4. Install dependencies:

   ```bash
   npm install
   ```

5. Start the development server:

   ```bash
   npm run dev
   ```

   The frontend will be available at `http://localhost:5173`.
   If you change the development url make sure to update the cors.php file in laravel as other wise it wont allow

6. While it's possible to manually set the API URL directly in the code, it's **highly recommended** to    use environment variables instead for flexibility and cleaner configuration.

   **File:** `frontend/src/axios/axios.ts`

      ```js
      const axiosInstance = axios.create({
      baseURL: 'http://yourendpoint.com', 
      });
      ```

      ```bash
      npm run dev
      ```

   The frontend will be available at `http://localhost:5173` by default.

### Testing

#### Backend: PHP Unit

To run feature tests for the Laravel backend:

>  Make sure you're in the project root before navigating to `backend`

```bash
cd backend
php artisan test
```

>  **Note:**  
> If you change the test runner's origin (e.g., running the frontend on a different port), be sure to update the `allowed_origins` setting in your Laravel `config/cors.php` file:


## Main API Endpoints


| Method | Endpoint                 | Description                          |
|--------|--------------------------|--------------------------------------|
| POST   | `/login`                 | User login                           |
| GET    | `/sanctum/csrf-cookie`   | Get CSRF token for frontend requests |
| POST   | `/logout`                | User logout                          |
| GET    | `/api/user`              | Get authenticated user info          |
| POST   | `/api/upload`            | Upload a file                        |
| GET    | `/api/payments`          | List all payments (paginated)        |





