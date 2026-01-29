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

1. **Simplicity First**: Features should be intuitive and straightforward
2. **Security by Default**: All protected actions require proper authentication and authorization
3. **Clean Code**: Maintainable codebase that serves as a learning resource
4. **Progressive Disclosure**: Show advanced features only to authorized users
5. **Convention Over Configuration**: Follow Yii3 conventions for predictable behavior

## Success Metrics

1. **Code Quality**: Passes static analysis (PHPStan level 8, Psalm)
2. **Test Coverage**: All critical paths covered by tests
3. **Documentation**: Clear memory bank documentation for AI assistance
4. **Usability**: Core workflows achievable with minimal clicks
5. **Performance**: Responsive page loads with proper caching
