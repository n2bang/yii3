# Domain Context: Yii3 Demo Diary

## Domain Overview

Demo Diary operates in the **content management** domain, specifically focused on personal journaling and diary management with multi-user support. The system manages the lifecycle of diary posts, their categorization, user management, and access control.

## Core Domain Concepts

### 1. Post (Diary Entry)

The central entity representing a diary entry.

**Properties:**
- `id`: Unique identifier
- `title`: Entry title (max 255 characters)
- `body`: Full text content (no length limit)
- `slug`: URL-friendly identifier (unique, max 255 characters)
- `status`: Current publication state (Draft/Published/Archived)
- `publication_date`: When the post should be/was published
- `created_at`, `updated_at`: Timestamp tracking
- `created_by`, `updated_by`: User references for audit trail

**States:**
- **Draft (0)**: Work in progress, not visible to public
- **Published (1)**: Visible to all users, appears in listings
- **Archived (2)**: Removed from active listings but preserved

**Business Rules:**
- Slug must be unique across all posts
- If slug is empty, auto-generate from title
- Only published posts appear in public listings
- Publication date can be set for future (scheduled publishing)
- Author (created_by) is immutable once set
- Editor (updated_by) changes with each modification

**Relationships:**
- Belongs to one User (creator)
- Edited by one User (updater)
- Belongs to many Categories (many-to-many)

### 2. Category

Organizational structure for grouping related posts.

**Properties:**
- `id`: Unique identifier
- `name`: Display name (max 100 characters)
- `desc`: Description of category purpose
- `slug`: URL-friendly identifier (unique, max 100 characters)

**Business Rules:**
- Slug must be unique across all categories
- A category can have zero or many posts
- Categories are independent of post status
- Deleting a category removes associations but not posts

**Relationships:**
- Has many Posts (many-to-many)

### 3. User

System users with authentication and authorization.

**Properties:**
- `id`: Unique identifier
- `login`: Username (unique, max 50 characters)
- `name`: Display name (max 100 characters)
- `status`: Account status (Active/Inactive)
- `password_hash`: Bcrypt hashed password
- `auth_key`: For cookie-based authentication

**States:**
- **Active (1)**: Can log in and perform actions
- **Inactive (0)**: Cannot log in, account disabled

**Business Rules:**
- Login must be unique across all users
- Password stored as hash only (never plain text)
- Auth key regenerated on certain events
- Inactive users cannot authenticate
- Users can be assigned one primary role

**Relationships:**
- Has many Posts (as creator)
- Has many Posts (as editor/updater)
- Has one or more Roles (via RBAC)

### 4. Role (RBAC)

Authorization layer defining what users can do.

**Available Roles:**
- **Admin**: Full system access, user management
- **Editor**: Content management only

**Permissions:**
- `user.manage`: CRUD operations on users (Admin only)
- `diary.manage`: CRUD operations on posts and categories (Admin + Editor)

**Business Rules:**
- Admin role includes all permissions
- Editor role includes only diary.manage
- Permissions checked before every protected action
- Unauthorized actions return 403 Forbidden

## Domain Workflows

### Post Publication Workflow

```
Create Draft → Edit/Revise → Set Publication Date → Publish → (Optional) Archive
     ↓              ↓              ↓                   ↓              ↓
  Status: 0      Status: 0      Status: 0         Status: 1      Status: 2
  Not visible   Not visible    Not visible      Visible       Hidden from lists
```

### User Lifecycle

```
Create User → Assign Role → Activate → User Works → (Optional) Deactivate
     ↓            ↓           ↓            ↓              ↓
  Login set   Permission   Can login   Performs      Cannot login
              granted                  actions
```

### Category Association

```
Create Category → Assign to Posts → Posts Display with Category Badge
       ↓                 ↓                    ↓
   Slug generated   Many-to-many link   Filterable by category
```

## Domain Language (Ubiquitous Language)

### Key Terms

**Post**: A diary entry created by a user. Never called "article" or "entry" in code.

**Category**: A classification label for posts. Never called "tag" or "label".

**Slug**: URL-friendly identifier derived from title/name. Auto-generated if not provided.

**Status**: The publication state of a post (Draft/Published/Archived).

**Role**: A set of permissions assigned to users (Admin/Editor).

**Permission**: A specific action a user can perform (user.manage, diary.manage).

**Active/Inactive**: User account status controlling login ability.

**Created By/Updated By**: User references for audit trail. "Created by" is the author.

**Publication Date**: When a post is/was made publicly visible.

### Action Verbs

**Publish**: Change post status from Draft to Published
**Archive**: Change post status to Archived (remove from active listings)
**Draft**: Create or edit a post in unpublished state
**Assign**: Link a category to a post or role to a user
**Deactivate**: Set user status to Inactive
**Authenticate**: Verify user credentials and establish session

## Domain Constraints

### Business Constraints

1. **Uniqueness Requirements**
   - User login must be unique
   - Post slug must be unique
   - Category slug must be unique

2. **Referential Integrity**
   - Cannot delete a user who created posts (author reference preserved)
   - Can delete categories (post associations removed)
   - Post-category relationship is optional (post can have zero categories)

3. **Status Transitions**
   - Posts can move between any status states freely
   - Users can only be Active or Inactive (no other states)

4. **Authorization Rules**
   - Public users can only read published posts
   - Editors can manage their own content
   - Admins can manage all content and users
   - No operation bypasses permission checks

### Technical Constraints

1. **Data Types**
   - All IDs are auto-incrementing integers
   - Timestamps stored in MySQL datetime format
   - Status values stored as tinyint (0, 1, 2)
   - Slugs limited to URL-safe characters

2. **Required Fields**
   - Post: title, status, created_by
   - Category: name, slug
   - User: login, password_hash, status

3. **Performance Considerations**
   - Pagination required for post listings (performance)
   - Indexes on slug fields for fast lookups
   - Indexes on status and publication_date for filtering

## Domain Invariants

These rules MUST always be true:

1. Published posts must have a publication_date
2. User password is never stored in plain text
3. Deleted users are referenced as creators but cannot log in
4. Slugs are always unique within their entity type
5. Only Active users can authenticate
6. Permission checks precede all protected actions
7. Created_by field never changes after initial set
8. Categories can exist without posts but posts don't require categories

## Domain Events (Implicit)

While not explicitly implemented as events, these domain changes occur:

1. **PostPublished**: When status changes to Published
2. **UserCreated**: When new user is added to system
3. **CategoryAssigned**: When post-category link is created
4. **UserDeactivated**: When user status changes to Inactive
5. **PostArchived**: When status changes to Archived

These could be extracted as explicit domain events in future enhancements.
