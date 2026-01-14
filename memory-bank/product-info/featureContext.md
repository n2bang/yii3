# Feature Context: Yii3 Demo Diary

## Implemented Features

### 1. Public Content Browsing

**Purpose**: Allow unauthenticated users to read published diary content.

**Routes**:
- `GET /` - Homepage with published posts listing
- `GET /category/{slug}` - Posts filtered by category
- `GET /post/{slug}` - Individual post view

**Components**:
- `IndexAction` - Lists all published posts with pagination
- `ViewAction` - Displays single post by slug
- `CategoryAction` - Filters posts by category slug
- `PostsList` widget - Renders paginated post list
- `CategoriesPanel` widget - Shows categories with post counts

**Business Logic**:
- Only posts with status = Published (1) are visible
- Posts ordered by publication_date descending
- Pagination: 10 posts per page
- Category panel shows only categories with published posts
- Invalid slugs return 404

**Files**:
- [src/UseCase/Diary/Front/IndexAction.php](src/UseCase/Diary/Front/IndexAction.php)
- [src/UseCase/Diary/Front/ViewAction.php](src/UseCase/Diary/Front/ViewAction.php)
- [src/UseCase/Diary/Front/CategoryAction.php](src/UseCase/Diary/Front/CategoryAction.php)
- [src/Presentation/Site/Widgets/PostsList.php](src/Presentation/Site/Widgets/PostsList.php)
- [src/Presentation/Site/Widgets/CategoriesPanel.php](src/Presentation/Site/Widgets/CategoriesPanel.php)

---

### 2. Authentication System

**Purpose**: Secure user login and session management.

**Routes**:
- `GET /login` - Login form
- `POST /login` - Process login credentials
- `POST /logout` - End user session

**Components**:
- `LoginRequest` action - Handles GET login form
- `LoginSubmit` action - Processes POST login
- `LogoutAction` - Destroys session and redirects

**Business Logic**:
- Validates username and password
- Checks user status is Active (1)
- Creates session on success
- Optional "remember me" cookie (cookie-based persistent auth)
- Failed attempts return to login with error message
- Inactive users cannot log in

**Files**:
- [src/UseCase/Login/LoginRequest.php](src/UseCase/Login/LoginRequest.php)
- [src/UseCase/Login/LoginSubmit.php](src/UseCase/Login/LoginSubmit.php)
- [src/UseCase/Logout/LogoutAction.php](src/UseCase/Logout/LogoutAction.php)

---

### 3. User Management (Admin Only)

**Purpose**: Full CRUD operations on user accounts.

**Routes**:
- `GET /admin/users` - List all users with filtering
- `GET /admin/users/create` - User creation form
- `POST /admin/users/create` - Process new user
- `GET /admin/users/{id}` - View user details
- `GET /admin/users/{id}/edit` - Edit user form
- `POST /admin/users/{id}/edit` - Update user
- `POST /admin/users/{id}/delete` - Delete user
- `POST /admin/users/change-password` - Batch password change

**Components**:
- `IndexAction` - Lists users with search by login/name
- `CreateAction` - Creates new user with role assignment
- `ViewAction` - Shows user details
- `EditAction` - Updates user data
- `DeleteAction` - Removes user
- `ChangePasswordAction` - Updates user password

**Business Logic**:
- Requires `user.manage` permission (Admin only)
- Users can be filtered by login or name
- Password hashed with bcrypt before storage
- Status set to Active by default
- Auth key regenerated on certain changes
- Pagination: 20 users per page

**Files**:
- [src/UseCase/Users/IndexAction.php](src/UseCase/Users/IndexAction.php)
- [src/UseCase/Users/CreateAction.php](src/UseCase/Users/CreateAction.php)
- [src/UseCase/Users/ViewAction.php](src/UseCase/Users/ViewAction.php)
- [src/UseCase/Users/EditAction.php](src/UseCase/Users/EditAction.php)
- [src/UseCase/Users/DeleteAction.php](src/UseCase/Users/DeleteAction.php)
- [src/UseCase/Users/ChangePasswordAction.php](src/UseCase/Users/ChangePasswordAction.php)

---

### 4. Diary Management (Editor & Admin)

**Purpose**: Complete CRUD for posts and categories.

#### Post Management

**Routes**:
- `GET /diary/post` - List posts with filtering
- `GET /diary/post/create` - Post creation form
- `POST /diary/post/create` - Save new post
- `GET /diary/post/{id}/edit` - Edit post form
- `POST /diary/post/{id}/edit` - Update post
- `POST /diary/post/{id}/delete` - Delete post

**Components**:
- `PostIndexAction` - Lists posts with title search
- `PostCreateAction` - Creates new post
- `PostEditAction` - Updates existing post
- `PostDeleteAction` - Removes post

**Business Logic**:
- Requires `diary.manage` permission (Editor + Admin)
- Auto-generate slug from title if empty
- Validate slug uniqueness
- Set created_by on creation
- Update updated_by on modifications
- Support multiple category assignments
- Default status: Draft (0)
- Pagination: 20 posts per page

**Files**:
- [src/UseCase/Diary/Manage/PostIndexAction.php](src/UseCase/Diary/Manage/PostIndexAction.php)
- [src/UseCase/Diary/Manage/PostCreateAction.php](src/UseCase/Diary/Manage/PostCreateAction.php)
- [src/UseCase/Diary/Manage/PostEditAction.php](src/UseCase/Diary/Manage/PostEditAction.php)
- [src/UseCase/Diary/Manage/PostDeleteAction.php](src/UseCase/Diary/Manage/PostDeleteAction.php)

#### Category Management

**Routes**:
- `GET /diary/category` - List categories with filtering
- `GET /diary/category/create` - Category creation form
- `POST /diary/category/create` - Save new category
- `GET /diary/category/{id}/edit` - Edit category form
- `POST /diary/category/{id}/edit` - Update category
- `POST /diary/category/{id}/delete` - Delete category

**Components**:
- `CategoryIndexAction` - Lists categories with name search
- `CategoryCreateAction` - Creates new category
- `CategoryEditAction` - Updates existing category
- `CategoryDeleteAction` - Removes category

**Business Logic**:
- Requires `diary.manage` permission (Editor + Admin)
- Auto-generate slug from name if empty
- Validate slug uniqueness
- Deleting category removes post associations
- Pagination: 20 categories per page

**Files**:
- [src/UseCase/Diary/Manage/CategoryIndexAction.php](src/UseCase/Diary/Manage/CategoryIndexAction.php)
- [src/UseCase/Diary/Manage/CategoryCreateAction.php](src/UseCase/Diary/Manage/CategoryCreateAction.php)
- [src/UseCase/Diary/Manage/CategoryEditAction.php](src/UseCase/Diary/Manage/CategoryEditAction.php)
- [src/UseCase/Diary/Manage/CategoryDeleteAction.php](src/UseCase/Diary/Manage/CategoryDeleteAction.php)

---

### 5. Profile Management

**Purpose**: Allow users to manage their own profile and password.

**Routes**:
- `GET /profile` - View own profile
- `GET /profile/edit` - Edit profile form
- `POST /profile/edit` - Update profile
- `GET /profile/change-password` - Password change form
- `POST /profile/change-password` - Update password

**Components**:
- `ViewAction` - Displays current user profile
- `EditAction` - Updates user name
- `ChangePasswordAction` - Changes password with validation

**Business Logic**:
- Requires authentication (any role)
- Users can only edit their own profile
- Password change requires current password verification
- New password must meet validation rules
- Cannot change login username

**Files**:
- [src/UseCase/Profile/ViewAction.php](src/UseCase/Profile/ViewAction.php)
- [src/UseCase/Profile/EditAction.php](src/UseCase/Profile/EditAction.php)
- [src/UseCase/Profile/ChangePasswordAction.php](src/UseCase/Profile/ChangePasswordAction.php)

---

### 6. REST API

**Purpose**: Provide programmatic access to diary data.

**Routes**:
- `GET /api/diary/post/list` - List posts with pagination
- `GET /api/diary/category/list` - List categories with pagination

**Components**:
- `PostListAction` - Returns paginated posts as JSON
- `CategoryListAction` - Returns paginated categories as JSON
- `PaginationPresenter` - Formats pagination metadata
- `PostPresenter` - Converts post to API response
- `CategoryPresenter` - Converts category to API response

**Response Format**:
```json
{
  "items": [...],
  "pagination": {
    "totalCount": 100,
    "pageSize": 20,
    "currentPage": 1,
    "totalPages": 5
  }
}
```

**Business Logic**:
- Public endpoints (no authentication required)
- Only published posts returned
- Pagination via query params: `?page=1&pageSize=20`
- Standard JSON responses
- Error responses with proper HTTP status codes

**Files**:
- [src/UseCase/Diary/Api/PostListAction.php](src/UseCase/Diary/Api/PostListAction.php)
- [src/UseCase/Diary/Api/CategoryListAction.php](src/UseCase/Diary/Api/CategoryListAction.php)
- [src/Presentation/Api/PostPresenter.php](src/Presentation/Api/PostPresenter.php)
- [src/Presentation/Api/CategoryPresenter.php](src/Presentation/Api/CategoryPresenter.php)
- [src/Presentation/Api/PaginationPresenter.php](src/Presentation/Api/PaginationPresenter.php)

---

### 7. Data Seeding

**Purpose**: Generate fake data for development and testing.

**Command**:
```bash
./yii fake-data/generate
```

**Components**:
- `GenerateCommand` - Console command using Faker library

**Generated Data**:
- 1 Admin user (login: admin, password: q1w2e3r4)
- 5 Editor users (random data)
- 10 Categories (random names and descriptions)
- 23 Posts (random titles, bodies, statuses, dates)
- Random post-category associations

**Business Logic**:
- Clears existing data first (truncates tables)
- Generates realistic fake content
- Auto-generates slugs
- Random status distribution
- Random publication dates (past 30 days)

**Files**:
- [src/UseCase/FakeData/GenerateCommand.php](src/UseCase/FakeData/GenerateCommand.php)

---

### 8. Role-Based Access Control (RBAC)

**Purpose**: Enforce permissions on protected actions.

**Roles**:
- **Admin**: Full access (user.manage + diary.manage)
- **Editor**: Content only (diary.manage)

**Permissions**:
- `user.manage`: Create/read/update/delete users
- `diary.manage`: Create/read/update/delete posts and categories

**Components**:
- `CheckAccess` middleware - Validates permissions before action
- `Role` enum - Defines available roles
- `Permission` enum - Defines available permissions

**Business Logic**:
- Middleware checks permission on each request
- Returns 403 Forbidden if unauthorized
- Admin inherits all permissions
- Editor limited to diary management
- Public routes bypass permission checks

**Files**:
- [src/Presentation/Middleware/CheckAccess.php](src/Presentation/Middleware/CheckAccess.php)
- [config/common/rbac-items.php](config/common/rbac-items.php)

---

## Feature Dependencies

```
Authentication (Login/Logout)
    └── Profile Management (requires auth)
    └── User Management (requires Admin role)
    └── Diary Management (requires Editor/Admin role)

Public Browsing (no dependencies)

REST API (no dependencies)

Data Seeding (standalone command)
```

## Cross-Cutting Concerns

### Pagination
- All list views use pagination (10-20 items per page)
- Consistent across web UI and API
- Offset-based pagination

### Validation
- All forms validated before persistence
- Server-side validation using Yii validators
- Unique constraint checks for slugs and logins

### Flash Messages
- Success/error messages after actions
- Rendered via `ContentNoticesWidget`
- Stored in session, displayed once

### URL Generation
- Centralized via `UrlGenerator` service
- All links generated programmatically
- Never hardcoded URLs in templates

### Form Models
- Separate form models from domain entities
- Validation rules defined on forms
- Forms hydrated from/to entities
