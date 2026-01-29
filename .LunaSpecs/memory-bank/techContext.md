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
APP_SECRET=your-secret-key

DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=demo_diary
DB_USER=root
DB_PASSWORD=
```

**Configuration Files**:
- [config/common/params.php](config/common/params.php) - Shared parameters
- [config/site/params.php](config/site/params.php) - Site-specific parameters
- [config/api/params.php](config/api/params.php) - API-specific parameters
- [config/console/params.php](config/console/params.php) - Console-specific parameters

## Database

### Schema Overview

**Tables**:
1. `user` - User accounts and authentication
2. `post` - Diary entries
3. `category` - Post categories
4. `post_category` - Many-to-many junction table
5. RBAC tables (managed by yiisoft/rbac-db)

**Migrations**: [src/Migration/](src/Migration/)

### Key Relationships

```
user 1 ─────── * post
post * ─────── * category (via post_category)
```

## Build & Task Automation

### Makefile Commands

**Development**:
```bash
make init           # Initialize project (composer install)
make up             # Start development server
make down           # Stop development server
make shell          # Enter Docker container shell
```

**Database**:
```bash
make migrate-up     # Apply all pending migrations
make migrate-down   # Revert last migration
make migrate-reset  # Drop all tables and re-migrate
make seed           # Seed database with fake data
```

**Testing**:
```bash
make test           # Run all tests
make test-unit      # Run unit tests only
make test-coverage  # Generate test coverage report
```

**Code Quality**:
```bash
make psalm          # Run Psalm static analysis
make phpstan        # Run PHPStan static analysis
make cs-fix         # Fix code style issues
make rector         # Run Rector refactoring
```

**Composer**:
```bash
make composer-update  # Update dependencies
make composer-install # Install dependencies
```

## Testing

### Test Configuration

**Codeception** ([codeception.yml](codeception.yml)):
```yaml
paths:
  tests: tests
  output: runtime/tests/_output
  support: tests/Support
  
suites:
  Unit: {}
  Functional: {}
  Console: {}
  Site: {}
```

### Test Structure

```
tests/
├── Unit/           # Domain logic tests
├── Functional/     # HTTP tests
├── Console/        # CLI command tests
├── Site/           # Site-specific tests
├── Support/        # Helpers and fixtures
│   ├── Helper/     # Test helpers
│   └── Data/       # Test data
├── bootstrap.php   # Test bootstrap
└── *.suite.yml     # Suite configurations
```

## Static Analysis

### PHPStan

**Configuration**: [phpstan.neon](phpstan.neon)
- Level: 8 (strictest)
- Analyzes all `src/` directory
- Excludes vendor and runtime

### Psalm

**Configuration**: [psalm.xml](psalm.xml)
- Error level: 1 (strictest)
- Type inference enabled
- Plugin support for Yii3

### PHP-CS-Fixer

**Configuration**: [.php-cs-fixer.php](.php-cs-fixer.php)
- PSR-12 style
- Strict types declaration
- Ordered imports

## Frontend Assets

### Asset Management

**Location**: [assets/](assets/), [public/assets/](public/assets/)

**Manager**: yiisoft/assets
- Asset bundles for CSS/JS grouping
- Automatic versioning
- Minification in production

### CSS Framework

Basic custom CSS with no external frameworks for simplicity.

## Development Workflow

### Local Development

1. Clone repository
2. Copy `.env.example` to `.env`
3. Configure database connection
4. Run `make init` (or `composer install`)
5. Run `make migrate-up`
6. Run `make up` to start server
7. Access at http://127.0.0.1:8080

### Code Changes

1. Write code following patterns in [systemPatterns.md](systemPatterns.md)
2. Run `make cs-fix` to fix code style
3. Run `make psalm` and `make phpstan` for static analysis
4. Run `make test` to ensure tests pass
5. Commit changes

### Database Changes

1. Create new migration: `./yii migrate:create migration_name`
2. Edit migration file in [src/Migration/](src/Migration/)
3. Run `make migrate-up` to apply
4. Test both `up()` and `down()` methods

## Security

### Authentication

- Session-based authentication via yiisoft/user
- Remember-me with persistent cookies
- Password hashing with bcrypt

### Authorization

- RBAC with database storage (yiisoft/rbac-db)
- Permission checks via middleware
- Route-level access control

### CSRF Protection

- CSRF tokens for all forms
- Automatic validation via middleware

### XSS Prevention

- Automatic output escaping in views
- Content Security Policy headers (can be added)

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

### Log Configuration

**Location**: [runtime/logs/](runtime/logs/)

**Levels**: DEBUG, INFO, WARNING, ERROR

### Error Handling

- Custom error handler in production
- Detailed errors in development
- Stack traces logged to file

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
