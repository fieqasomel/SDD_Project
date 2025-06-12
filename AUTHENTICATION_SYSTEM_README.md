# SDD System Authentication Implementation

## Overview
This document outlines the complete authentication system implemented for the SDD (Stakeholder Dialogue and Dispute Resolution) System with separate models and role-specific dashboards.

## System Architecture

### User Types
1. **Public User** - General users who submit inquiries
2. **Agency** - Government agencies that handle inquiries
3. **MCMC** - MCMC staff with administrative privileges

### Database Structure
- `publicuser` table - Public user information
- `agency` table - Agency information
- `mcmc` table - MCMC staff information
- `users` table - Default Laravel users (for Jetstream compatibility)

## Files Created/Modified

### Models
1. **app/Models/PublicUser.php** - Public user model with authentication
2. **app/Models/Agency.php** - Agency model with authentication
3. **app/Models/MCMC.php** - MCMC model with authentication
4. **app/Models/User.php** - Default user model

### Controllers
1. **app/Http/Controllers/Controller.php** - Base controller
2. **app/Http/Controllers/AuthController.php** - Authentication logic
3. **app/Http/Controllers/DashboardController.php** - Dashboard controllers

### Views

#### Authentication Views
1. **resources/views/auth/login.blade.php** - Unified login form
2. **resources/views/auth/register-selection.blade.php** - Registration type selection
3. **resources/views/auth/register-publicuser.blade.php** - Public user registration
4. **resources/views/auth/register-agency.blade.php** - Agency registration
5. **resources/views/auth/register-mcmc.blade.php** - MCMC registration

#### Dashboard Views
1. **resources/views/Dashboard/PublicUserDashboard.blade.php** - Public user dashboard
2. **resources/views/Dashboard/AgencyDashboard.blade.php** - Agency dashboard
3. **resources/views/Dashboard/MCMCDashboard.blade.php** - MCMC dashboard

#### Other Views
1. **resources/views/welcome.blade.php** - Landing page
2. **resources/views/Login/LoginView.blade.php** - Updated existing login view

### Configuration
1. **config/auth.php** - Updated with custom guards and providers
2. **routes/web.php** - Authentication and dashboard routes

## Authentication Guards

### Guards Configuration
```php
'guards' => [
    'web' => ['driver' => 'session', 'provider' => 'users'],
    'publicuser' => ['driver' => 'session', 'provider' => 'publicusers'],
    'agency' => ['driver' => 'session', 'provider' => 'agencies'],
    'mcmc' => ['driver' => 'session', 'provider' => 'mcmcs'],
],
```

### Providers Configuration
```php
'providers' => [
    'users' => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    'publicusers' => ['driver' => 'eloquent', 'model' => App\Models\PublicUser::class],
    'agencies' => ['driver' => 'eloquent', 'model' => App\Models\Agency::class],
    'mcmcs' => ['driver' => 'eloquent', 'model' => App\Models\MCMC::class],
],
```

## Routes

### Authentication Routes
- `GET /login` - Show login form
- `POST /login` - Process login
- `POST /logout` - Logout user

### Registration Routes
- `GET /register` - Show registration type selection
- `GET /register/publicuser` - Show public user registration form
- `GET /register/agency` - Show agency registration form
- `GET /register/mcmc` - Show MCMC registration form
- `POST /register/publicuser` - Process public user registration
- `POST /register/agency` - Process agency registration
- `POST /register/mcmc` - Process MCMC registration

### Dashboard Routes
- `GET /publicuser/dashboard` - Public user dashboard (protected by auth:publicuser)
- `GET /agency/dashboard` - Agency dashboard (protected by auth:agency)
- `GET /mcmc/dashboard` - MCMC dashboard (protected by auth:mcmc)

## Features

### Login System
- Unified login form with user type selection
- Visual user type cards (Public User, Agency, MCMC)
- Email and password authentication
- Automatic redirection to role-specific dashboards

### Registration System
- Registration type selection page
- Separate registration forms for each user type
- Extended forms with profile fields:
  - **Public User**: Name, IC, Age, Gender, Address, Email, Phone, Password
  - **Agency**: Name, Username, Category, Address, Email, Phone, Password
  - **MCMC**: Name, Username, Position, Address, Email, Phone, Password
- Form validation and error handling
- Automatic ID generation for each user type

### Dashboard Features

#### Public User Dashboard
- Profile information display
- Submit inquiry functionality
- Track progress
- View inquiry history
- Notifications
- Profile management
- Generate reports

#### Agency Dashboard
- Agency information display
- Statistics cards (Pending, In Progress, Completed, Total)
- View and manage inquiries
- Update progress
- Provide feedback
- Search inquiries
- Generate reports
- Profile management

#### MCMC Dashboard
- Staff information display
- System statistics (Users, Agencies, Inquiries, Activities)
- User management
- System monitoring
- Generate system reports
- Alert management
- Data management
- System settings
- Quick actions panel

## Security Features

### Password Security
- Passwords are automatically hashed using Laravel's Hash facade
- Password confirmation validation
- Minimum 8 character requirement

### Authentication Security
- Separate authentication guards for each user type
- Session-based authentication
- CSRF protection on all forms
- Input validation and sanitization

### Access Control
- Role-based access control using middleware
- Protected dashboard routes
- Automatic logout functionality

## Styling and UI

### Design Features
- Modern Bootstrap 5 design
- Gradient backgrounds and styling
- Font Awesome icons
- Responsive design
- Hover effects and animations
- Color-coded user types:
  - Public User: Blue theme
  - Agency: Green theme
  - MCMC: Red theme

### User Experience
- Intuitive navigation
- Clear visual feedback
- Error message display
- Success notifications
- Loading states
- Mobile-friendly interface

## Usage Instructions

### For Developers

1. **Database Setup**: Ensure all migrations are run
2. **Dependencies**: Bootstrap 5 and Font Awesome are loaded via CDN
3. **Testing**: Use the route list command to verify all routes are working

### For Users

1. **Access**: Visit the application root URL
2. **Registration**: Choose user type and fill out the appropriate form
3. **Login**: Select user type, enter email and password
4. **Dashboard**: Access role-specific features from the dashboard

## Next Steps

### Recommended Enhancements
1. Email verification system
2. Password reset functionality
3. Profile picture upload
4. Advanced user management for MCMC
5. Inquiry submission and tracking system
6. Notification system
7. Reporting and analytics
8. API endpoints for mobile app

### Integration Points
- Connect with existing inquiry management system
- Integrate with notification system
- Add reporting functionality
- Implement file upload capabilities

## Troubleshooting

### Common Issues
1. **Controller not found**: Ensure `app/Http/Controllers/Controller.php` exists
2. **Route errors**: Check that all routes are properly defined
3. **Authentication issues**: Verify guard configuration in `config/auth.php`
4. **Database errors**: Ensure migrations are run and tables exist

### Testing
- Test registration for each user type
- Test login with different user types
- Verify dashboard access and logout functionality
- Check form validation and error handling

This authentication system provides a solid foundation for the SDD System with proper separation of concerns, security, and user experience.