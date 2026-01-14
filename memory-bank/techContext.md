# Technology Context: Yii3 Demo Diary

## Technology Stack

### Core Technologies

**Language**: PHP 8.5+
- Strict types enabled across all files
- Modern PHP features (constructor property promotion, enums, named arguments)
- Type declarations on all methods
- Return type hints

**Framework**: Yii3 (yiisoft/*)
- Modern rewrite of Yii2
- Full dependency injection container
- PSR-7/PSR-15 compliant
- Modular architecture

**Database**: MySQL 8.0+
- InnoDB storage engine
- UTF-8MB4 character set
- Foreign key constraints
- Indexed queries for performance

**Web Server**: PHP Built-in Development Server
- Port 8080 for local development
- Production: Any PSR-7 compatible server

## Dependencies

### Framework Components (Yii3)

```json
"yiisoft/active-record": "^1.0@dev"
"yiisoft/db": "^1.2"
"yiisoft/db-mysql": "^1.2"
"yiisoft/router-fastroute": "^3.0"
"yiisoft/di": "^1.2"
"yiisoft/form-model": "^1.0@dev"
"yiisoft/rbac-db": "^2.0@dev"
"yiisoft/user": "^3.0@dev"
"yiisoft/validator": "^1.1"
"yiisoft/view": "^10.0"
"yiisoft/csrf": "^2.0"
"yiisoft/session": "^2.0"
"yiisoft/cache": "^3.0"
"yiisoft/aliases": "^3.0"
"yiisoft/assets": "^4.0"
```

**Purpose**:
- `active-record`: ORM for database entities
- `db-mysql`: MySQL driver
- `router-fastroute`: Fast routing with FastRoute library
- `di`: Dependency injection container
- `form-model`: Form handling and validation
- `rbac-db`: Role-based access control with DB storage
- `user`: Authentication and identity management
- `validator`: Data validation rules
- `view`: Template rendering
- `csrf`: CSRF protection for forms
- `session`: Session management
- `cache`: Caching layer
- `aliases`: Path aliases (@app, @root, etc.)
- `assets`: Frontend asset management

### PSR Standards

```json
"psr/http-message": "^2.0"
"psr/http-server-handler": "^1.0"
"psr/http-server-middleware": "^1.0"
"psr/http-factory": "^1.0"
"psr/container": "^2.0"
"psr/log": "^3.0"
```

**Purpose**:
- PSR-7: HTTP messages (Request/Response)
- PSR-11: Container interface
- PSR-15: HTTP handlers and middleware
- PSR-17: HTTP factories
- PSR-3: Logger interface

### Utilities

```json
"symfony/console": "^7.0"
"vlucas/phpdotenv": "^5.4"
"fakerphp/faker": "^1.21"
```

**Purpose**:
- `symfony/console`: Console command framework
- `phpdotenv`: Environment variable loading from .env
- `faker`: Generate fake data for testing/seeding

### Development Tools

```json
"codeception/codeception": "^5.0"
"codeception/module-asserts": "^3.0"
"phpstan/phpstan": "^1.10"
"vimeo/psalm": "^5.13"
"friendsofphp/php-cs-fixer": "^3.21"
"rector/rector": "^0.17"
"roave/security-advisories": "dev-latest"
```

**Purpose**:
- `codeception`: Testing framework (functional, unit, console tests)
- `phpstan`: Static analysis (level 8)
- `psalm`: Additional static analysis
- `php-cs-fixer`: Code style enforcement
- `rector`: Automated code refactoring
- `roave/security-advisories`: Security vulnerability prevention

## Development Environment

### Docker Setup

**Dockerfile** ([Dockerfile](Dockerfile)):
```dockerfile
FROM php:8.5-cli

# Install extensions
RUN docker-php-ext-install intl pdo_mysql

# Configure working directory
WORKDIR /app

# Expose port for web server
EXPOSE 8080
```

**Docker Compose** ([docker-compose.yml](docker-compose.yml)):
- PHP container with application code
- MySQL container (can be enabled if needed)
- Volume mounting for development
- Port mapping: 8080:8080

**Host Configuration**:
- Database connection via `host.docker.internal` (Docker Desktop)
- Allows connecting to host machine's MySQL

### Environment Configuration

**Environment Variables** ([.env](.env)):
```env
APP_ENV=dev
APP_DEBUG=true

DB_DRIVER=mysql
DB_HOST=host.docker.internal
DB_NAME=yii3_db
DB_USER=root
DB_PASSWORD=root123qwe
DB_CHARSET=utf8mb4
```

**Environments Supported**:
- `dev`: Development mode with debug enabled
- `test`: Testing mode with separate database
- `prod`: Production mode with optimizations

### Configuration Structure

**Directory**: [config/](config/)

```
config/
├── configuration.php       # Main config plugin loader
├── common/                 # Shared configuration
│   ├── params.php          # Application parameters
│   ├── rbac-items.php      # RBAC roles and permissions
│   └── di/                 # Dependency injection configs
│       ├── database.php    # Database connection
│       ├── router.php      # Routing configuration
│       ├── yiisoft-*.php   # Framework component configs
│       └── ...
├── site/                   # Web application
│   ├── params.php
│   ├── routes.php          # Web routes
│   └── di/                 # Site-specific DI
├── api/                    # REST API
│   ├── params.php
│   ├── routes.php          # API routes
│   └── di/                 # API-specific DI
└── console/                # Console commands
    ├── params.php
    ├── routes.php
    └── di/
```

**Configuration Loading**:
1. Load environment from `.env`
2. Load `config/configuration.php`
3. Merge environment-specific configs
4. Build DI container with all configurations

## Database

### Connection Configuration

**File**: [config/common/di/database.php](config/common/di/database.php)

```php
ConnectionInterface::class => [
    'class' => Connection::class,
    '__construct()' => [
        'dsn' => 'mysql:host={DB_HOST};dbname={DB_NAME}',
        'username' => '{DB_USER}',
        'password' => '{DB_PASSWORD}',
        'charset' => 'utf8mb4',
    ],
]
```

**Configuration**:
- DSN built from environment variables
- UTF-8MB4 for full Unicode support (including emojis)
- Connection pooling handled by MySQL
- Prepared statements for all queries

### Schema Management

**Migrations**: [src/Migration/](src/Migration/)

**Migration Classes**:
- `M20230101CreateUserTable` - User table and indexes
- `M20230102CreatePostTable` - Post table with foreign keys
- `M20230103CreateCategoryTable` - Category table
- `M20230104CreatePostCategoryTable` - Junction table

**Migration Commands**:
```bash
./yii migrate/up      # Apply pending migrations
./yii migrate/down    # Rollback last migration
./yii migrate/redo    # Rollback and re-apply last migration
```

**Best Practices**:
- One migration per schema change
- Always provide down() method for rollback
- Use table prefixes if needed
- Include indexes in creation migrations

### Database Schema

**Tables**:
1. `user` (6 columns, 1 unique index)
2. `post` (10 columns, 1 unique index, 2 foreign keys)
3. `category` (4 columns, 1 unique index)
4. `post_category` (2 columns, composite primary key, 2 foreign keys)
5. `yii_rbac_*` (RBAC system tables - managed by yiisoft/rbac-db)

**Indexes**:
- Primary keys on all tables
- Unique indexes on slug fields (post.slug, category.slug)
- Unique index on user.login
- Foreign key indexes for relationships

**Character Set**: utf8mb4 (all tables)

**Collation**: utf8mb4_unicode_ci

## Build & Task Automation

### Makefile

**File**: [Makefile](Makefile)

**Common Commands**:

```bash
make init               # Full setup (build + composer + codecept)
make build              # Build Docker containers
make up                 # Start containers
make down               # Stop containers
make composer-install   # Install PHP dependencies
make composer-update    # Update dependencies

# Database
make migrate-up         # Run migrations
make migrate-down       # Rollback migration
make fake-data          # Seed with fake data
make create-admin       # Create admin user

# Development
make serve              # Start development server (port 8080)
make shell              # Open shell in container

# Testing
make test               # Run all tests
make test-unit          # Run unit tests only
make test-functional    # Run functional tests only
make codecept-build     # Build codeception

# Code Quality
make psalm              # Run Psalm static analysis
make phpstan            # Run PHPStan static analysis
make cs-fix             # Fix code style issues
make rector             # Run automated refactoring
```

**Typical Workflow**:
```bash
make init              # First time setup
make migrate-up        # Apply database schema
make fake-data         # Add sample data
make serve             # Start server
make test              # Run tests
make psalm             # Check code quality
```

## Testing

### Codeception Setup

**Configuration**: [codeception.yml](codeception.yml)

**Test Suites**:

1. **Unit Tests** ([tests/Unit/](tests/Unit/))
   - Test domain logic in isolation
   - No database dependencies
   - Fast execution

2. **Functional Tests** ([tests/Functional/](tests/Functional/))
   - Test complete workflows
   - Database interactions
   - HTTP requests/responses
   - Test database reset between tests

3. **Console Tests** ([tests/Console/](tests/Console/))
   - Test console commands
   - Command output validation

**Test Database**:
- Separate `yii3_db_test` database
- Reset before each test
- Migrations applied automatically

**Running Tests**:
```bash
make test                    # All tests
make test-unit              # Unit tests only
make test-functional        # Functional tests only
vendor/bin/codecept run     # Direct codeception
```

## Static Analysis

### PHPStan

**Configuration**: [phpstan.neon](phpstan.neon)

**Settings**:
- Level: 8 (strictest)
- Paths: src/, config/
- Bootstrap: vendor/autoload.php

**Run**: `make phpstan`

### Psalm

**Configuration**: [psalm.xml](psalm.xml)

**Settings**:
- Error level: 4
- Strict types check enabled
- Report mixed types

**Run**: `make psalm`

### PHP CS Fixer

**Configuration**: [.php-cs-fixer.php](.php-cs-fixer.php)

**Rules**:
- PSR-12 standard
- Strict types declaration
- Return type hints required
- No unused imports

**Run**: `make cs-fix`

## Frontend Assets

### Asset Management

**Location**: [public/assets/](public/assets/)

**Structure**:
```
public/
├── index.php           # Application entry point
└── assets/             # Static assets
    ├── css/
    ├── js/
    └── images/
```

**Asset Bundles**: Defined in application config

**Note**: This is a backend-focused demo. Minimal frontend assets are used. No JavaScript framework (React, Vue, etc.).

## Development Workflow

### Initial Setup

1. **Clone Repository**
2. **Copy Environment File**: `cp .env.example .env`
3. **Configure Database**: Edit `.env` with database credentials
4. **Install Dependencies**: `make init`
5. **Run Migrations**: `make migrate-up`
6. **Seed Data**: `make fake-data`
7. **Start Server**: `make serve`
8. **Visit**: http://localhost:8080

### Daily Development

1. **Start Containers**: `make up`
2. **Start Server**: `make serve`
3. **Make Changes**: Edit code
4. **Run Tests**: `make test`
5. **Check Quality**: `make psalm`
6. **Fix Styles**: `make cs-fix`

### Adding a New Feature

1. Create domain entities in `src/Domain/`
2. Create migration for database schema
3. Run migration: `make migrate-up`
4. Create use case action in `src/UseCase/`
5. Add route in `config/site/routes.php` or `config/api/routes.php`
6. Create view templates if needed
7. Write tests in `tests/`
8. Run tests: `make test`
9. Check static analysis: `make psalm`

## Performance Considerations

### Database Query Optimization

- Eager loading for relationships to avoid N+1 queries
- Indexes on frequently queried columns (slug, status)
- Pagination on all list queries
- Connection pooling via MySQL

### Caching Strategy

- Yii cache component available but not heavily used in demo
- Can add caching for:
  - Published post listings
  - Category counts
  - User session data

### Code Optimization

- Strict types for better PHP opcache optimization
- Constructor property promotion reduces memory
- Readonly properties prevent accidental mutations
- Enums compiled at runtime (faster than constants)

## Security

### Security Measures

1. **Password Hashing**: Bcrypt with cost factor 13
2. **CSRF Protection**: Token-based on all forms
3. **SQL Injection Prevention**: Parameterized queries via ActiveRecord
4. **XSS Prevention**: Output escaping in views
5. **Authentication**: Session-based with secure cookies
6. **Authorization**: RBAC middleware on all protected routes
7. **Dependency Security**: `roave/security-advisories` prevents vulnerable packages

### Security Configuration

**Headers** (should be configured in web server):
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block

**Session Configuration**:
- HttpOnly cookies
- Secure flag in production
- SameSite: Lax

## Deployment Considerations

### Production Requirements

1. **PHP 8.5+** with extensions:
   - intl
   - pdo_mysql
   - opcache (recommended)
   - mbstring

2. **MySQL 8.0+**

3. **Web Server**: Nginx or Apache with PHP-FPM

4. **Environment**:
   - Set `APP_ENV=prod`
   - Set `APP_DEBUG=false`
   - Use strong `APP_SECRET`
   - Configure secure database credentials

### Production Optimizations

- Enable PHP opcache
- Use APCu for caching
- Minimize autoloader with `composer dump-autoload --optimize`
- Use CDN for static assets
- Enable Gzip compression
- Configure proper logging

## Monitoring & Logging

### Logging

**Location**: [runtime/logs/](runtime/logs/)

**Logs**:
- Application logs: `runtime/logs/app.log`
- Error logs: `runtime/logs/error.log`

**Log Levels**: Error, Warning, Info, Debug

### Debugging

**Development Mode** (`APP_DEBUG=true`):
- Detailed error messages
- Stack traces shown
- Query logging enabled

**Production Mode** (`APP_DEBUG=false`):
- Generic error pages
- Errors logged only
- No stack traces exposed

## Dependencies Update Strategy

### Update Process

1. Check for updates: `composer outdated`
2. Review changelogs for breaking changes
3. Update dependencies: `make composer-update`
4. Run tests: `make test`
5. Run static analysis: `make psalm`
6. Test manually in development

### Yii3 Version Tracking

**Note**: Yii3 is still in development (many packages at `@dev` or `^1.0@dev`). Monitor releases for:
- Breaking changes in APIs
- New features
- Security updates
- Performance improvements

## Technology Decisions

### Why Yii3 Over Yii2?

- Modern PHP 8.0+ features
- Full dependency injection (no service locator)
- PSR-compliant (7, 11, 15, 17)
- Better separation of concerns
- Improved testability

### Why MySQL Over PostgreSQL?

- Wide compatibility
- Good performance for application size
- Familiar to most developers
- Adequate feature set for requirements

### Why PHP Built-in Server Over Nginx?

- Simplified development setup
- No web server configuration needed
- Fast iterations
- Production would use proper web server
