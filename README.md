# ICMS – Innovation Challenge Management System

## Project Overview

The **Innovation Challenge Management System (ICMS)** is a web-based database management system designed to manage innovation challenges, participating teams, participants, project submissions, judges, evaluations, and awards.

The system provides an organized platform for an administrator/organizer to manage the complete lifecycle of an innovation challenge from challenge creation to project evaluation and award management.

The project follows the **MVC (Model–View–Controller)** architecture and uses **Oracle Database** as the backend database.

---

## Main Features

### 1. Dashboard
- Centralized admin dashboard
- Navigation to all major modules
- Overview of the system

### 2. Challenge Management
- Create new challenges
- View existing challenges
- Update challenge information
- Delete challenges
- Manage challenge status

### 3. Team Management
- View participating teams
- Manage team information
- View team details

### 4. Participant Management
- View participants
- Manage participant information
- Associate participants with teams

### 5. Participation Management
- Manage team participation in challenges
- View participation records

### 6. Project Submission Management
- View submitted projects
- Manage project submission information
- Track submission status
- View project and team details

### 7. Judge Management
- Add and manage judges
- Store judge information
- Manage judge specialization and contact information

### 8. Evaluation Management
- View project evaluations
- Store evaluation scores
- Store comments and recommendations
- Associate evaluations with judges and project submissions

### 9. Award Management
- Create and manage awards
- Assign awards based on evaluations
- Store prize amount and certificate information
- Update and delete award records

### 10. Authentication
- Admin login system
- Username and password validation
- Session-based authentication
- Role and account status checking
- Logout functionality

---

## Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP 8.0.30
- MVC Architecture

### Database
- Oracle Database 10g XE
- Oracle OCI8 extension for PHP

### Development Environment
- XAMPP
- Visual Studio Code
- Git & GitHub

---

## Project Architecture

The project follows the MVC structure:

```text
ICMS/
│
├── app/
│   ├── controllers/
│   │   ├── AwardController.php
│   │   ├── ChallengeController.php
│   │   ├── EvaluationController.php
│   │   ├── JudgeController.php
│   │   ├── LoginController.php
│   │   ├── ParticipantController.php
│   │   ├── ParticipationController.php
│   │   ├── ProjectSubmissionController.php
│   │   └── TeamController.php
│   │
│   ├── models/
│   │   ├── AwardModel.php
│   │   ├── ChallengeModel.php
│   │   ├── EvaluationModel.php
│   │   ├── JudgeModel.php
│   │   ├── ParticipantModel.php
│   │   ├── ParticipationModel.php
│   │   ├── ProjectSubmissionModel.php
│   │   └── TeamModel.php
│   │
│   ├── views/
│   │   ├── dashboard.php
│   │   ├── awards.php
│   │   ├── challenges.php
│   │   ├── evaluations.php
│   │   ├── judges.php
│   │   ├── participants.php
│   │   ├── participation.php
│   │   ├── submissions.php
│   │   ├── teams.php
│   │   └── partials/
│   │
│   └── core/
│       └── Auth.php
│
├── config/
│   └── database.php
│
├── public/
│   └── css/
│       └── style.css
│
└── index.php
MVC Workflow

The application follows this general flow:

User
  ↓
Controller
  ↓
Model
  ↓
Oracle Database
  ↓
Model
  ↓
Controller
  ↓
View
  ↓
User
Controller

Controllers receive user requests and decide what operation should be performed.

Model

Models communicate with the Oracle database and perform database operations such as:

SELECT
INSERT
UPDATE
DELETE
View

Views display the user interface and database results to the user.

Database Connection

The project uses the OCI8 PHP extension to connect PHP with Oracle Database.

The database connection is configured in:

config/database.php

The application uses an Oracle connection similar to:

oci_connect(
    $username,
    $password,
    $connection_string
);

The database connection is then used by the Models to execute SQL queries.

Local Environment Configuration
Requirements

Before running the project, install/configure:

Windows
XAMPP
PHP 8.0.30 x86 Thread Safe
Oracle Database 10g XE
OCI8 extension compatible with PHP 8.0
Git
PHP Configuration

The working PHP installation is:

C:\php80x86

PHP version:

PHP 8.0.30

The PHP configuration file is:

C:\php80x86\php.ini

The PHP extension directory is configured as:

extension_dir = "C:\php80x86\ext"

The OCI8 extension is enabled in the PHP configuration so that PHP can communicate with Oracle Database.

Running the Project

The project currently uses PHP's built-in development server with the configured PHP 8.0.30 environment.

Open Command Prompt or PowerShell and run:

cd C:\xampp\htdocs\ICMS

Then start the PHP server:

C:\php80x86\php.exe -S localhost:8000 -t C:\xampp\htdocs

If the server starts successfully, the terminal will show:

PHP 8.0.30 Development Server
(http://localhost:8000) started

Then open the application in the browser:

http://localhost:8000/ICMS/
Important Note About XAMPP

The project uses the PHP 8.0.30 installation located at:

C:\php80x86

for the application server.

Therefore, the application should be accessed through:

http://localhost:8000/ICMS/

rather than relying on XAMPP Apache's default PHP configuration.

Oracle Database must be running and accessible for database operations to work correctly.

Authentication

The login system uses session-based authentication.

The login process is:

Login Form
    ↓
LoginController
    ↓
UserModel
    ↓
Oracle SYSTEM_USER table
    ↓
Username & Password Validation
    ↓
Session Creation
    ↓
Dashboard

The authentication system checks:

Username
Password
Account status
User role

After successful login, session information is stored and the user is redirected to the dashboard.

Database Modules

The system contains database modules for:

Organizer
Challenge
Team
Participant
Participation
Project Submission
Judge
Evaluation
Award
User Authentication

The database follows relational database principles and uses primary keys, foreign keys, relationships, and normalized tables.

CRUD Operations

Most management modules support CRUD operations:

Create
  ↓
Read
  ↓
Update
  ↓
Delete

For example, the Challenge module allows the administrator to:

Create a challenge
View challenges
Update challenge information
Delete a challenge

Similar operations are implemented for other major modules.

Security Considerations

The project includes several basic security practices:

Session-based authentication
Password verification
Account status checking
Prepared/bound Oracle query parameters using oci_bind_by_name()
Session ID regeneration after successful login
Authentication checks before accessing protected pages
Project Purpose

The main purpose of ICMS is to provide a centralized system for managing innovation competitions and their related database information.

The system reduces manual management and provides structured management of:

Challenges
Teams
Participants
Project Submissions
Judges
Evaluations
Awards
Future Improvements

Possible future improvements include:

Role-based dashboards for different users
File upload for project documents
Advanced search and filtering
Statistical reports and charts
Email notifications
Online judge evaluation interface
Improved API-based architecture
Deployment to a production server
Repository

This repository contains the complete source code of the Innovation Challenge Management System (ICMS).

The source code is organized using MVC architecture to keep database logic, application logic, and user interface components separated.

ICMS
├── Controllers
├── Models
├── Views
├── Configuration
├── Authentication
└── Public Assets
License

This project was developed as an academic project for database management coursework.