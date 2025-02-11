# RPU
# University Admission Management System 🎓📱

A comprehensive system for managing university admission requests, payments, and grants. This project is powered by **Laravel** for the backend and **Flutter** for the frontend mobile applications. The system supports web-based admin control and mobile apps for employees and end users.

---

## Features ✨
### 🌐 Web (Admin Panel)
- **User Management:** Manage users with different roles (Admin, Employee, User).
- **Admission Requests:** Monitor and manage admission requests for multiple universities.
- **Grant Allocation:** Automatically allocate grant seats based on student scores.
- **Payment Processing:** Manage and track payments.
- **Support Questions Management:**  

### 📱 Mobile Apps (Flutter)
#### Support Staff App:
- **Answer User Questions:** Review and answer support questions submitted by users.  
- **Mark FAQs:** Identify and mark questions as frequently asked.  
- **Manage Question Status:** Approve, reject, or update question statuses.

#### User App:
- **Show all information about universities and specializations in each university**
- **Submit Requests:** Users can apply for university admissions.
- **Track Status:** Check the status of their requests.
- **Payment History:** View and manage payments.
- **Submit Questions:** Users can submit support questions through the app.  
- **Track Question Status:** View the status of submitted questions (`Pending`, `Answered`, or `Rejected`).
- **View FAQ**
---

## Technologies Used 🛠
- **Backend:** Laravel 10.x
- **Database:** MySQL/MariaDB
- **Mobile Apps:** Flutter (Dart)
- **Frontend (Web):** Blade templates, Bootstrap 5
---

## Installation 🖥️
### Prerequisites:
- PHP >= 8.1
- Composer
- MySQL or MariaDB
- Node.js & npm (for frontend assets)
- Flutter >= 3.x

### Backend Installation:
1. Clone the repository:
   ```bash
   git clone https://github.com/Ob3y1/RPU.git
   cd RPU
2. Install dependencies:
   ```bash
    composer install
    npm install && npm run dev
3. Configure the .env file:
   ```bash
  
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
4. Run migrations and seeders:
     ```bash
      php artisan migrate
      php artisan db:seed
      php artisan db:seed --class=UserRequestSeeder
      php artisan db:seed --class=SupportQuestionSeeder
      
5. Start the development server:
   ```bash
    php artisan serve
### Flutter Apps Installation 📱
- **Run the following command in each directory:**
   ```bash
     flutter pub get
     flutter run
---

## License 📄
- **This project is licensed under the MIT License. See the LICENSE file for details.**
---
## Contact 📧
- **Author:** Obay Mosa
- **Email:** abymwsy1@gmail.com
- **GitHub:** Ob3y1
