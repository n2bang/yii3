# Project Brief: Yii3 Demo Diary

## Project Overview

Demo Diary is a multi-user diary/journal management system built with Yii3 framework. It serves as both a functional application and a demonstration of modern PHP development practices using Yii3's clean architecture approach.

## Core Goals

1. **Demonstrate Yii3 Best Practices**: Showcase clean architecture, dependency injection, and domain-driven design patterns
2. **Multi-User Diary Platform**: Provide a platform where users can create, manage, and publish diary entries
3. **Role-Based Access Control**: Implement secure access control with Admin and Editor roles
4. **Public Content Browsing**: Allow public users to read published diary entries organized by categories
5. **Modern PHP Development**: Utilize PHP 8.5+ features with strict typing and modern development tools

## Key Requirements

### Functional Requirements

1. **Public Features**
   - Browse published diary posts
   - View posts by category
   - Read individual post details
   - Paginated post listings

2. **Authentication & Authorization**
   - User login with remember-me functionality
   - Session-based authentication with persistent cookies
   - Role-based permissions (Admin, Editor)
   - Secure password management

3. **Diary Management** (Editor & Admin)
   - Create, edit, and delete diary posts
   - Organize posts with multiple categories
   - Post status workflow (Draft → Published → Archived)
   - Auto-generate slugs from titles
   - Track creation/modification timestamps and authors
   - Schedule publication dates

4. **Category Management** (Editor & Admin)
   - Create, edit, and delete categories
   - Assign multiple categories to posts
   - View post counts per category

5. **User Management** (Admin only)
   - Create, read, update, and delete users
   - Manage user status (Active/Inactive)
   - Reset user passwords
   - Assign roles to users

6. **Profile Management** (All authenticated users)
   - Update personal profile information
   - Change own password

7. **REST API**
   - List posts with pagination
   - List categories
   - Standard JSON responses

### Technical Requirements

1. **Framework**: Yii3 with full dependency injection
2. **PHP Version**: 8.5+ with strict types
3. **Database**: MySQL 8.0+ with proper migrations
4. **Architecture**: Clean Architecture with separated layers
5. **Testing**: Codeception for functional and unit tests
6. **Code Quality**: PHPStan, Psalm, and PHP-CS-Fixer integration
7. **Environment Management**: Docker support for development
8. **Data Seeding**: Faker integration for test data generation

## Success Criteria

1. Clean separation of concerns across Domain, Presentation, and UseCase layers
2. All CRUD operations working for Posts, Categories, and Users
3. Proper RBAC implementation with permission checks
4. Public browsing works without authentication
5. API endpoints return proper JSON responses
6. Database migrations are reversible
7. Code passes static analysis checks
8. All tests pass successfully

## Non-Goals

- Real-time collaboration features
- Mobile application
- Advanced rich text editor (WYSIWYG)
- Social sharing features
- Comments or reactions system
- Email notifications
- File/image uploads
- Advanced search functionality

## Project Scope

This is a **demonstration project** focused on showcasing Yii3 framework capabilities and best practices. It implements core diary management features with proper architecture, authentication, and authorization patterns that can serve as a reference for building production applications.
