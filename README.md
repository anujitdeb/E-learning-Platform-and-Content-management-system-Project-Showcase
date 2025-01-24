# E-learning Platform & Content management system

## Introduction

E-learning Platform & Content management system is a web-based application that allows educational institutions,
businesses, and individuals to create, manage, and deliver online courses and training programs. It provides a
comprehensive set of features for course management, user management, progress tracking, assessment, and content
delivery. The platform is designed to be user-friendly, scalable, and customizable to meet the unique needs of different
organizations and industries.

## Features

- **Course Management**: Add, edit, and manage courses, modules, and lessons.
- **User Management**: Manage different roles such as students, instructors, and admins within the platform.
- **Progress Tracking**: Track student progress through courses and modules.
- **Assessment and Quizzes**: Create, manage, and evaluate quizzes and assessments.
- **Discussion Forums**: Facilitate discussions and engagement within course-specific forums.
- **Content Delivery**: Streamline the delivery of multimedia content including video, audio, and documents.
- **Notifications**: Manage notifications for course updates, new content, and other relevant information.
- **Reporting**: Generate reports on user activity, course performance, and completion statistics.
- **Integration**: Easy integration with third-party tools such as payment gateways and communication platforms.

## Requirements

- PHP 8.2 or higher
- Composer 2.5.8
- MySQL
- Laravel 11.23.4

## Installation

### 1. Clone the repository

```bash
git clone git@github.com:anujitdeb/E-learning-Platform-and-Content-management-system-Project-Showcase.git
cd E-learning-Platform-and-Content-management-system-Project-Showcase
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment Variables

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations and Seed the Database

```bash
php artisan migrate --seed
```

### 5. Run Migrations and Seed the Database

```bash
php artisan passport:client --personal
```

### 6. Start the Development Server

```bash
php artisan serve
```
