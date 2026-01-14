# Product Context: Yii3 Demo Diary

## Why This Project Exists

### Primary Purpose
Demo Diary serves a dual purpose:
1. **Educational Reference**: Demonstrates modern Yii3 framework usage with clean architecture patterns
2. **Functional Application**: Provides a working multi-user diary/journal management system

### Problems It Solves

1. **Learning Curve**: Helps developers understand how to structure complex Yii3 applications using clean architecture principles
2. **Content Management**: Provides a simple way for multiple users to create, organize, and publish diary entries
3. **Access Control Demonstration**: Shows proper implementation of role-based permissions in Yii3
4. **Best Practices Reference**: Demonstrates proper separation of concerns, dependency injection, and domain-driven design

## Target Users

### Primary Users
1. **PHP Developers**: Learning Yii3 framework and modern PHP patterns
2. **Content Creators**: Users who want to write and publish diary entries (Editors)
3. **Administrators**: Managing users and overall system content (Admins)
4. **Public Readers**: Browsing published diary content without authentication

### User Roles

**Public (Unauthenticated)**
- Browse published posts
- View posts by category
- Read individual post details

**Editor (Authenticated)**
- All public features
- Create, edit, delete own diary posts
- Manage categories
- Draft and publish posts
- Update own profile

**Admin (Authenticated)**
- All editor features
- Manage all users (CRUD operations)
- Reset user passwords
- Assign roles
- Full system access

## How It Should Work

### User Experience Goals

1. **Simple Public Browsing**
   - Anyone can read published content without barriers
   - Clear navigation by categories
   - Easy-to-read post listings with pagination

2. **Intuitive Content Creation**
   - Straightforward post creation workflow
   - Auto-generate slugs from titles
   - Draft-first approach allows refinement before publishing
   - Easy categorization with multiple category support

3. **Secure Access Control**
   - Clear separation between public and protected areas
   - Proper authentication with remember-me functionality
   - Role-based permissions prevent unauthorized actions
   - Users only see actions they're permitted to perform

4. **Professional Admin Experience**
   - Dedicated admin panel for user management
   - Batch operations for efficiency
   - Clear user status indicators
   - Comprehensive user CRUD operations

5. **Developer-Friendly API**
   - RESTful endpoints with standard JSON responses
   - Paginated responses for large datasets
   - Clear error messages

### Key User Workflows

**1. Public Reader Journey**
```
Visit site → Browse published posts → Select category (optional) → Read post → Navigate to related posts
```

**2. Editor Content Creation**
```
Login → Navigate to diary management → Create new post → Add title/body → Select categories →
Save as draft → Review → Change status to published → Set publication date
```

**3. Admin User Management**
```
Login → Navigate to user management → View user list → Create/edit user →
Assign role → Set status → Reset password (if needed)
```

**4. Category Organization**
```
Login → Navigate to categories → Create category → Define name/description → Generate slug →
Assign to posts
```

## Product Principles

### 1. Clean Code First
- Strict type declarations in all PHP files
- Single Responsibility Principle for all classes
- Dependency Injection over service locators
- Domain logic separated from presentation

### 2. Security by Design
- Role-based access control at every protected endpoint
- Password hashing with secure algorithms
- CSRF protection on forms
- SQL injection prevention through parameterized queries

### 3. Separation of Concerns
- Domain models contain only business logic
- Use cases handle application workflows
- Presentation layer handles only display logic
- Infrastructure manages external dependencies

### 4. Developer Experience
- Clear directory structure
- Consistent naming conventions
- Makefile for common operations
- Docker support for easy setup
- Comprehensive seeding for quick testing

### 5. Scalability Patterns
- Pagination on all list endpoints
- Database indexing on frequently queried fields
- Read models separated from write models
- Action classes allow easy feature addition

## Success Metrics

### For Developers (Learning Goal)
- Can understand the codebase structure within 30 minutes
- Can add a new feature following existing patterns
- Can extend RBAC with new permissions
- Can create new entities with full CRUD

### For Users (Functional Goal)
- Can create and publish a post in under 2 minutes
- Can find published posts by category in 1 click
- Can manage personal profile without confusion
- Admins can create users quickly and assign roles

### For Code Quality
- All static analysis passes (PHPStan, Psalm)
- Code follows PSR standards
- Tests cover critical paths
- Migrations are reversible

## Feature Philosophy

**Keep It Simple**: Each feature does one thing well. No feature creep.

**Convention Over Configuration**: Follow Yii3 conventions. Reduce custom configuration.

**Explicit Over Implicit**: Clear, readable code over clever abstractions.

**Security First**: Every feature considers authorization and validation from the start.

**Real-World Patterns**: Use production-ready patterns, not tutorial shortcuts.
