# Active Context: Yii3 Demo Diary

## Current State (as of 2026-01-14)

### Project Status

**Phase**: Stable & Functional

The application is fully functional with all core features implemented and working. Recent activity focused on fixing MySQL database connectivity issues.

### Recent Work

#### Latest Commits

**Commit fbc464a**: "feat: fix to connect with mysql" (Most Recent)
- Fixed MySQL connection configuration
- Updated database parameters in config files
- Modified `.env` for proper MySQL host configuration
- Adjusted Dockerfile to include required PHP extensions (intl, pdo_mysql)
- Fixed user table migration issues

**Commit 91f9460**: "feat: update db connection"
- Updated database configuration in DI container
- Modified `/config/common/di/database.php`
- Updated `/config/common/params.php`
- Refined Docker configuration
- Simplified service provider setup

**Commit 33df832**: "First commit"
- Initial complete project setup
- All core features implemented
- Full architecture in place

### What's Working

All implemented features are functional:

1. **Public Content Browsing**
   - Homepage with published posts
   - Category filtering
   - Individual post viewing
   - Pagination working correctly

2. **Authentication**
   - Login/logout functional
   - Session management working
   - Remember-me cookies operational
   - User status checks enforced

3. **User Management (Admin)**
   - Full CRUD operations on users
   - Password changes
   - Role assignments
   - User filtering and search

4. **Diary Management (Editor & Admin)**
   - Post CRUD with status workflow
   - Category CRUD
   - Multiple category assignments
   - Slug auto-generation
   - Author tracking

5. **Profile Management**
   - Personal profile updates
   - Password changes

6. **REST API**
   - Post listing endpoint
   - Category listing endpoint
   - JSON responses with pagination

7. **Data Seeding**
   - Fake data generation command working
   - Creates admin + editors + posts + categories

8. **RBAC**
   - Permission checks enforced
   - Admin and Editor roles working
   - Middleware authorization functional

### Current Focus

**Database Connectivity**: Recently resolved MySQL connection issues. The application now properly connects to MySQL database using `host.docker.internal` for Docker environments.

### Active Patterns & Preferences

#### Code Style Preferences

1. **Strict Types**: All PHP files use `declare(strict_types=1);`
2. **Constructor Property Promotion**: Preferred for cleaner code
3. **Readonly Properties**: Used for dependencies to ensure immutability
4. **Enums Over Constants**: Type-safe enums for status values
5. **Action Classes**: One class per use case, no fat controllers

#### Architecture Preferences

1. **Clean Architecture**: Strict layer separation maintained
2. **Dependency Injection**: Always constructor injection, never service locator
3. **Single Responsibility**: Each action class has one purpose
4. **Domain-First**: Business logic stays in domain layer
5. **Read/Write Separation**: Read models separate from ActiveRecord entities

#### Database Preferences

1. **Migrations for All Schema Changes**: Never manual SQL
2. **Reversible Migrations**: Always provide down() method
3. **Indexed Queries**: Index all frequently queried columns
4. **UTF-8MB4**: Full Unicode support everywhere

#### Testing Preferences

1. **Test Before Push**: All tests must pass
2. **Static Analysis**: PHPStan level 8 must pass
3. **Functional Tests**: Cover main user workflows
4. **Isolated Unit Tests**: No database dependencies in unit tests

### Known Configuration

#### Environment Setup

**Database Connection**:
- Host: `host.docker.internal` (for Docker)
- Database: `yii3_db`
- User: `root`
- Password: `root123qwe`
- Charset: `utf8mb4`

**Application Settings**:
- Environment: `dev`
- Debug: `true`
- Port: `8080`

**Default Admin Credentials** (after `make create-admin` or `make fake-data`):
- Login: `admin`
- Password: `q1w2e3r4`

### Development Workflow

**Current Setup Commands**:
```bash
make init              # Initial setup
make migrate-up        # Apply schema
make fake-data         # Seed data
make serve             # Start server (localhost:8080)
make test              # Run tests
make psalm             # Static analysis
```

**Daily Workflow**:
1. Start Docker: `make up`
2. Start server: `make serve`
3. Make changes
4. Test: `make test`
5. Check quality: `make psalm`

### Project Insights & Learnings

#### Key Insights

1. **Docker Database Connectivity**: When using Docker, connecting to host MySQL requires `host.docker.internal` instead of `localhost`. This was the source of recent connection issues.

2. **Yii3 Dev Packages**: Many Yii3 packages are still in development (`@dev` versions). Need to monitor for breaking changes when updating dependencies.

3. **ActiveRecord Trade-offs**: Using ActiveRecord means domain entities know about persistence. This is acceptable for a demo but might need reconsideration for larger applications.

4. **Action Pattern Benefits**: Single action classes are much easier to test and maintain than traditional controllers. Clear win for this pattern.

5. **RBAC Middleware Pattern**: Declaring permissions at the route level (middleware) is cleaner than checking permissions in every action. Less boilerplate, more declarative.

6. **Slug Auto-generation**: Auto-generating slugs from titles is convenient but needs uniqueness validation. Current implementation handles this well.

#### Technical Decisions Made

1. **Why ActiveRecord**: Chose ActiveRecord for rapid development and Yii integration, despite mixing domain logic with persistence concerns.

2. **Why Action Classes**: Chose one-action-per-class pattern for better testability and single responsibility adherence.

3. **Why Enums**: PHP 8.1+ enums provide type safety that constants and magic numbers can't match.

4. **Why Separate Read Models**: Created read repositories to keep query logic separate from domain entities, following CQRS principles lightly.

5. **Why Middleware for Auth**: Chose middleware over action-level checks to avoid repeating authorization logic everywhere.

### Important Patterns to Follow

When adding new features, maintain these patterns:

1. **New Entity**: Add to `src/Domain/` with business logic
2. **New Use Case**: Create action class in `src/UseCase/` organized by feature
3. **New Route**: Add to appropriate routes file (`site/routes.php` or `api/routes.php`)
4. **New View**: Add to views directory following existing structure
5. **Schema Change**: Create migration, never modify database manually
6. **Permission Check**: Use middleware, not inline checks
7. **Form Handling**: Create form model separate from domain entity
8. **API Response**: Use presenter to format output

### Next Steps (If Development Continues)

Potential improvements (not currently planned):

1. **API Authentication**: Add token-based auth for API endpoints
2. **Post Search**: Full-text search across post titles and bodies
3. **Image Uploads**: Allow image attachments to posts
4. **Comments System**: User comments on posts
5. **Draft Auto-save**: Periodic auto-saving of draft posts
6. **Email Notifications**: Notify users of relevant events
7. **Activity Log**: Track all user actions for audit
8. **Enhanced WYSIWYG**: Rich text editor for post body

### Current Blockers

**None**: All features working, no blockers at this time.

### Recent Challenges Resolved

1. **MySQL Connection Issues**: Fixed by configuring proper Docker host reference
2. **PHP Extensions**: Ensured `intl` and `pdo_mysql` installed in Dockerfile
3. **Migration Issues**: Resolved user table migration compatibility

### Collaboration Notes

**For New Developers**:
- Read the Memory Bank files (you're doing it!)
- Run `make init` to set up environment
- Check `make fake-data` to populate test data
- Start with `make serve` to launch the application
- Tests must pass: `make test`
- Code quality matters: `make psalm`

**For Code Reviews**:
- Verify strict types declaration
- Check layer separation (domain, usecase, presentation)
- Ensure tests included
- Validate migration has down() method
- Confirm permission checks on protected routes

### Documentation Status

All Memory Bank files have been initialized and are current:

- ✅ [projectBrief.md](projectBrief.md) - Core requirements and goals
- ✅ [productContext.md](productContext.md) - Project purpose and user experience
- ✅ [domainContext.md](domainContext.md) - Domain concepts and business rules
- ✅ [featureContext.md](featureContext.md) - Detailed feature documentation
- ✅ [systemPatterns.md](systemPatterns.md) - Architecture and patterns
- ✅ [techContext.md](techContext.md) - Technology stack and tooling
- ✅ [activeContext.md](activeContext.md) - Current state (this file)
- ✅ [progress.md](progress.md) - Project status and evolution

### Important Files to Reference

**Configuration**:
- [.env](.env) - Environment variables
- [config/common/params.php](config/common/params.php) - Application parameters
- [config/common/di/database.php](config/common/di/database.php) - Database config

**Core Domain**:
- [src/Domain/User/User.php](src/Domain/User/User.php) - User entity
- [src/Domain/Post/Post.php](src/Domain/Post/Post.php) - Post entity
- [src/Domain/Category/Category.php](src/Domain/Category/Category.php) - Category entity

**Key Use Cases**:
- [src/UseCase/Login/LoginSubmit.php](src/UseCase/Login/LoginSubmit.php) - Authentication
- [src/UseCase/Diary/Manage/PostCreateAction.php](src/UseCase/Diary/Manage/PostCreateAction.php) - Post creation
- [src/UseCase/Users/CreateAction.php](src/UseCase/Users/CreateAction.php) - User management

**Middleware**:
- [src/Presentation/Middleware/CheckAccess.php](src/Presentation/Middleware/CheckAccess.php) - RBAC enforcement

**Migrations**:
- [src/Migration/M20230101CreateUserTable.php](src/Migration/M20230101CreateUserTable.php) - User schema
- [src/Migration/M20230102CreatePostTable.php](src/Migration/M20230102CreatePostTable.php) - Post schema

### Session Context

**Current Session Goal**: Memory Bank initialization completed successfully. All documentation files created and populated with comprehensive project information.

**What Changed This Session**: Initialized complete Memory Bank system with all core documentation files.
