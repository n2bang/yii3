# Active Context: Yii3 Demo Diary

## Current State (as of 2026-01-29)

### Project Status
- **Phase**: Complete and Functional
- **Environment**: Development ready with Docker support
- **Database**: MySQL 8.0+ with all migrations applied
- **Application**: Running on PHP 8.5+ with Yii3 framework

### Recent Work

**Memory Bank Initialization**:
- Initialized `.LunaSpecs/memory-bank` structure
- Created all core memory bank files
- Documented project architecture and patterns
- Captured technology stack and dependencies

### What's Working

1. **Public Diary Features**
   - Browse published posts with pagination
   - View posts by category
   - Individual post detail pages
   - Clean URLs with slugs

2. **Authentication System**
   - User login/logout
   - Remember-me functionality
   - Session management
   - Secure password handling

3. **Diary Management (Editors)**
   - Create, edit, delete posts
   - Draft/Published/Archived workflow
   - Category assignment
   - Publication date scheduling

4. **Category Management**
   - CRUD operations for categories
   - Post count display
   - Slug generation

5. **User Management (Admins)**
   - User CRUD operations
   - Role assignment
   - Status management
   - Password reset

6. **REST API**
   - Post listing endpoint
   - Category listing endpoint
   - JSON responses

7. **Development Tools**
   - Docker development environment
   - Makefile automation
   - Static analysis (PHPStan, Psalm)
   - Code style enforcement (PHP-CS-Fixer)
   - Testing framework (Codeception)

### Current Focus

- Memory bank is now initialized for AI-assisted development
- Project is feature-complete and stable
- Ready for any future enhancements or maintenance

### Active Patterns & Preferences

1. **Code Style**
   - PSR-12 with strict types
   - Constructor property promotion
   - Named arguments where clarity improves
   - PHP 8.1+ enums for type safety

2. **Architecture**
   - Single Action Controllers (one action per class)
   - Constructor dependency injection
   - Domain layer with ActiveRecord
   - Middleware for cross-cutting concerns

3. **Testing**
   - Codeception for all test types
   - Functional tests for HTTP flows
   - Unit tests for domain logic

### Known Configuration

**Database Connection**:
```
Host: localhost (or host.docker.internal in Docker)
Port: 3306
Database: demo_diary
User: root (development)
```

**Development Server**:
```
URL: http://127.0.0.1:8080
Command: make up (or ./yii serve)
```

### Development Workflow

1. Start server: `make up`
2. Make changes following architecture patterns
3. Run static analysis: `make psalm && make phpstan`
4. Fix code style: `make cs-fix`
5. Run tests: `make test`
6. Commit changes

### Project Insights & Learnings

1. **Yii3 Differences from Yii2**
   - No service locator - pure DI everywhere
   - PSR-15 middleware instead of filters
   - Separate packages for each component
   - More explicit configuration

2. **Clean Architecture Benefits**
   - Easy to locate code by feature
   - Single responsibility makes testing easier
   - Dependencies are explicit and visible

3. **Docker Development**
   - Use `host.docker.internal` for database connection from container
   - Volume mounts for live code changes
   - PHP built-in server sufficient for development

### Important Patterns to Follow

When adding new features:

1. **New Use Case**: Create action in `src/UseCase/[Feature]/`
2. **New Entity**: Add to `src/Domain/[Entity]/`
3. **New Endpoint**: Configure route in `config/[app]/routes.php`
4. **New DI Service**: Add to `config/common/di/`
5. **New Migration**: Run `./yii migrate:create [name]`

### Next Steps (If Development Continues)

1. **Potential Enhancements**:
   - Rich text editor for post content
   - Image upload support
   - Post search functionality
   - Comments system
   - Email notifications

2. **Technical Improvements**:
   - Increase test coverage
   - Add API authentication
   - Implement caching layer
   - Add OpenAPI documentation

### Current Blockers

None - project is fully functional.

### Recent Challenges Resolved

1. **Database Connectivity in Docker**: Resolved by using `host.docker.internal`
2. **Yii3 Package Stability**: Managing `@dev` packages with composer

### Documentation Status

- ✅ projectbrief.md - Complete
- ✅ productContext.md - Complete
- ✅ systemPatterns.md - Complete
- ✅ techContext.md - Complete
- ✅ activeContext.md - Complete
- ✅ progress.md - Complete

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

### Session Context

This memory bank was initialized to enable AI-assisted development. When starting new work:

1. Read all memory bank files to understand context
2. Follow established patterns in systemPatterns.md
3. Use technology stack described in techContext.md
4. Update activeContext.md with current work focus
5. Update progress.md when completing features
