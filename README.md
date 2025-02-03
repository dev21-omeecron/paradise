
## Table of Contents

1. [Introduction](#introduction)
2. [System Requirements](#system-requirements)
3. [Technology Stack](#technology-stack)
4. [Features & Functionality](#features--functionality)
5. [User Roles & Access Control](#user-roles--access-control)
6. [Database Structure](#database-structure)
7. [Installation Guide](#installation-guide)
8. [User Guide](#user-guide)
9. [Admin Guide](#admin-guide)
10. [Security Features](#security-features)
11. [Proposed Enhancements](#proposed-enhancements)
12. [Conclusion](#conclusion)

## Introduction

Palm Paradise Hotel Management System is a comprehensive web-based application designed to streamline hotel operations, booking management, and customer service. The system provides an intuitive interface for both administrators and users to manage room bookings, event hall reservations, and other hotel services efficiently.

## System Requirements

### Hardware Requirements

- **Processor**: Intel Core i3 or equivalent (minimum)
- **RAM**: 4GB (minimum), 8GB (recommended)
- **Storage**: 10GB free space
- **Internet Connection**: Broadband connection (minimum 1 Mbps)

### Software Requirements

- **Operating System**: Windows/Linux/MacOS
- **Web Server**: Apache 2.4+
- **Database**: MySQL 5.7+ or MariaDB 10+
- **PHP Version**: PHP 7.4+
- **Web Browser**: Chrome 80+, Firefox 75+, Safari 13+, Edge 80+

## Technology Stack

- **Frontend**:

  - HTML5
  - CSS3
  - JavaScript
  - Bootstrap 4.6
  - jQuery 3.6.4
  - AdminLTE 3 Dashboard Template
  - ApexCharts for data visualization
  - DataTables for dynamic tables
  - Font Awesome for icons

- **Backend**:

  - PHP 7.4+
  - MySQL
  - PDO for database operations

- **Security**:
  - Session Management
  - Password Hashing (bcrypt)
  - Prepared Statements
  - Input Sanitization
  - CSRF Protection

## Features & Functionality

### Core Features

1. **User Management**

   - User registration and authentication
   - Profile management
   - Password recovery
   - Session management (Role-based)

2. **Room Management**

   - Multiple room categories
   - Room availability tracking
   - Dynamic pricing
   - Room details and images
   - Advance Search filter functionality and DataTable
   - Edit and delete room booking
   - View and Download Invoice PDF

3. **Event Hall Management**

   - Multiple hall types
   - Capacity management
   - Pricing structure
   - Hall details and images
   - Advance Search filter functionality and DataTable
   - Edit and delete event hall booking
   - View and Download Invoice PDF

4. **Booking System**

   - Room booking
   - Event hall booking
   - Date range selection
   - Real-time availability checking
   - Invoice Generation

5. **Admin Dashboard**

   - Total revenue tracking
   - Booking statistics
   - User management
   - Monthly trends visualization
   - Recent bookings overview

6. **User Dashboard**
   - Fully Dynamic multiple feature-based Dashboard
   - Personal booking history
   - Upcoming reservations
   - Booking status tracking
   - Quick access to services

### Additional Features

- Responsive design for mobile devices
- Real-time data updates
- Interactive booking calendar
- Advanced search and filtering
- Detailed booking reports
- Chat inquiry system 

## User Roles & Access Control

### Admin Role

- **Credentials**:
  - Email: admin@gmail.com
  - Password: admin@123
- **Permissions**:
  - Full system access
  - User management
  - Room/Hall management
  - Booking management
  - Revenue tracking
  - System configuration

### User Role

- **Sample Credentials**:
  - Email: jaygoyani939@gmail.com
  - Password: Jay@12345
- **Permissions**:
  - Personal profile management
  - Room/Hall booking
  - Booking history view
  - Personal dashboard access

## Database Structure

### Key Tables

1. **users**

   - User authentication and profile data
   - Personal information storage
   - Role management

2. **rooms**

   - Room inventory
   - Room types and categories
   - Pricing information
   - Availability status

3. **event_halls**

   - Hall inventory
   - Hall types and capacities
   - Pricing structure
   - Availability tracking

4. **room_bookings**

   - Room reservation records
   - Check-in/out dates
   - Payment status
   - Booking status
   - Edit and delete room booking
   - View and Download Invoice PDF

5. **hall_bookings**
   - Hall reservation records
   - Event dates
   - Payment tracking
   - Booking status
   - Edit and delete hall booking
   - View and Download Invoice PDF

## Installation Guide

1. **System Setup** (for ubuntu only if you have windows or mac operation system then ignore it.)

   ```bash
   # Clone repository
   git clone [repository-url]

   # Configure Apache
   sudo a2enmod rewrite
   sudo service apache2 restart

   # Set permissions
   chmod -R 755 /var/www/html/palmparadise
   chown -R www-data:www-data /var/www/html/palmparadise
   ```

2. **Database Setup**

   ```sql
   CREATE DATABASE paradise;
   USE paradise;
   SOURCE paradise.sql;
   ```

3. **Configuration**
   - Update database credentials in `dbcon.php`
   - Configure email settings if required
   - Set up virtual host if needed

## Security Features

1. **Authentication Security**

   - Secure password hashing using bcrypt
   - Session-based authentication
   - Automatic session timeout
   - Protection against brute force attacks

2. **Database Security**

   - Prepared statements for all queries
   - Input validation and sanitization
   - Protection against SQL injection

3. **Application Security**
   - CSRF token protection
   - XSS prevention
   - Secure file upload handling
   - Error logging and handling

## Proposed Enhancements

1. **Technical Enhancements**

   - Implementation of REST API
   - Mobile application development
   - Real-time notifications using WebSockets
   - Integration with payment gateways

2. **Feature Enhancements**

   - Room service management
   - Staff management system
   - Inventory management
   - Customer Message/Feedback system
   - Analytics and reporting
   - Multi-language support

3. **User Experience**
   - Enhanced booking interface
   - Virtual room tours
   - AI-powered chatbot
   - Personalized recommendations

## Conclusion

The Palm Paradise Hotel Management System successfully implements a comprehensive solution for hotel operations management. The system provides:

- Efficient booking management for rooms and event halls
- Secure user authentication and authorization
- Real-time availability tracking and booking
- Comprehensive administrative controls
- User-friendly interfaces for both staff and customers

The system is built with scalability in mind and can be enhanced with additional features as per future requirements. Regular maintenance and updates will ensure optimal performance and security.

---

_Documentation generated on: January 22, 2025_
