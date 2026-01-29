# System Patterns: Yii3 Demo Diary

## Architecture Overview

This application follows **Clean Architecture** principles with a strong emphasis on **Domain-Driven Design (DDD)** and **SOLID principles**. The architecture separates concerns into distinct layers, each with specific responsibilities.

## Layer Structure

```
┌─────────────────────────────────────────────────┐
│         Presentation Layer                      │
│  (Site UI, API Responses, Middleware, Widgets)  │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│           UseCase Layer                         │
│      (Application Logic, Actions)               │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│            Domain Layer                         │
│   (Entities, Value Objects, Enums)              │
└─────────────────┬───────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────┐
│        Infrastructure Layer                     │
│  (Database, External Services, Yii Framework)   │
└─────────────────────────────────────────────────┘
```

### Layer Responsibilities

**Domain Layer** ([src/Domain/](src/Domain/))
- Core business entities and rules
- No dependencies on other layers
- Pure PHP objects with business logic
- ActiveRecord entities for persistence
- Enums for type-safe constants

**UseCase Layer** ([src/UseCase/](src/UseCase/))
- Application workflows and orchestration
- One action class per use case
- Depends on Domain layer only
- Receives dependencies via constructor injection
- Returns responses or renders views

**Presentation Layer** ([src/Presentation/](src/Presentation/))
- User interface components
- API response formatting
- View rendering logic
- Widgets for reusable UI components
- Middleware for cross-cutting concerns

**Infrastructure Layer** (Implicit - Yii Framework)
- Database connections
- External service integrations
- Framework-specific implementations

## Key Patterns

### 1. Action Pattern (Single Action Controllers)

Each use case is represented by a single action class implementing `RequestHandlerInterface`.

**Structure**:
```php
namespace App\UseCase\Feature;

class FeatureAction implements RequestHandlerInterface
{
    public function __construct(
        private readonly DependencyInterface $dependency,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // Use case logic here
        return $response;
    }
}
```

**Benefits**:
- Single Responsibility Principle
- Easy to test in isolation
- Clear dependency requirements
- No controller bloat

**Example**: [src/UseCase/Diary/Manage/PostCreateAction.php](src/UseCase/Diary/Manage/PostCreateAction.php)

### 2. Dependency Injection (Constructor Injection)

All dependencies injected through constructors, never using service locators.

**Pattern**:
```php
public function __construct(
    private readonly EntityRepository $repository,
    private readonly ViewRenderer $view,
    private readonly WebFlashMessageService $flash,
    private readonly UrlGeneratorInterface $url,
) {}
```

**Configuration**: [config/common/di/](config/common/di/)

**Benefits**:
- Explicit dependencies
- Easy to mock for testing
- No hidden dependencies
- Type-safe

### 3. ActiveRecord Pattern

Domain entities extend `ActiveRecord` for persistence.

**Structure**:
```php
namespace App\Domain\Post;

class Post extends ActiveRecord
{
    // Business methods
    public function publish(): void
    {
        $this->status = PostStatus::Published;
        $this->publication_date = new DateTime();
    }

    // Relations
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('post_category', ['post_id' => 'id']);
    }
}
```

**Trade-offs**:
- ✅ Rapid development
- ✅ Built-in query builder
- ✅ Relationship management
- ⚠️ Domain logic mixed with persistence (acceptable for demo)

**Example**: [src/Domain/Post/Post.php](src/Domain/Post/Post.php)

### 4. Repository Pattern (Read Models)

Separate read models for queries, keeping domain entities clean.

**Structure**:
```php
namespace App\Domain\Post;

class PostReadRepository
{
    public function getPublishedPosts(int $page, int $limit): OffsetPaginator
    {
        // Optimized query for listing
    }
}
```

**Benefits**:
- Query optimization without affecting domain models
- CQRS-like separation
- Easier caching strategies

### 5. Enum Pattern (Type-Safe Constants)

PHP 8.1+ enums for type safety instead of magic numbers.

**Structure**:
```php
namespace App\Domain\Post;

enum PostStatus: int
{
    case Draft = 0;
    case Published = 1;
    case Archived = 2;
}
```

**Usage**:
```php
$post->status = PostStatus::Published;

if ($post->status === PostStatus::Published) {
    // Type-safe comparison
}
```

**Examples**:
- [src/Domain/Post/PostStatus.php](src/Domain/Post/PostStatus.php)
- [src/Domain/User/UserStatus.php](src/Domain/User/UserStatus.php)

### 6. Middleware Pattern

Cross-cutting concerns handled via PSR-15 middleware.

**Examples**:
- `AccessChecker` - RBAC permission validation
- `CsrfMiddleware` - CSRF protection for forms
- `AuthenticationMiddleware` - Session-based auth

**Configuration**: [config/site/params.php](config/site/params.php) - Middlewares array

### 7. Presenter Pattern (API Responses)

Dedicated presenter classes format API responses.

**Structure**:
```php
namespace App\Presentation\Api;

class PostPresenter
{
    public function present(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
            'published_at' => $post->publication_date?->format('c'),
        ];
    }
}
```

### 8. Widget Pattern (Reusable UI Components)

Yii3 widgets for reusable UI elements.

**Location**: [src/Presentation/Site/Widget/](src/Presentation/Site/Widget/)

**Examples**:
- Pagination widgets
- Navigation components
- Flash message displays

### 9. Form Model Pattern

Form handling separated from domain entities.

**Structure**:
```php
namespace App\UseCase\Login;

final class LoginForm extends FormModel
{
    private string $login = '';
    private string $password = '';
    private bool $rememberMe = false;

    // Validation rules via attributes
}
```

**Benefits**:
- Validation separate from domain
- Multiple forms can write to same entity
- Easy to customize validation

### 10. Service Provider Pattern

DI container configured via provider classes.

**Location**: [config/common/di/](config/common/di/)

**Pattern**:
```php
// config/common/di/database.php
return [
    ConnectionInterface::class => [
        'class' => Connection::class,
        '__construct()' => [
            'driver' => new Driver('mysql', $params['db']['dsn']),
        ],
    ],
];
```

## Architectural Decisions

### Why Clean Architecture?

1. **Testability**: Domain logic testable without framework dependencies
2. **Flexibility**: Easy to swap framework components
3. **Maintainability**: Clear boundaries between concerns
4. **Scalability**: Add features without affecting existing code

### Why Single Action Pattern Over Controllers?

1. **SRP**: Each action has one responsibility
2. **Discoverability**: Easy to find code for specific features
3. **Testing**: Simpler test setup with fewer dependencies
4. **Routing**: Direct mapping from routes to handlers

### Why ActiveRecord Over Pure Domain Models?

1. **Speed**: Faster development for demo project
2. **Integration**: Better Yii framework integration
3. **Trade-off**: Acceptable coupling for project scope
4. **Pragmatism**: Pure domain would add complexity without proportional benefit

## Code Organization Principles

1. **Feature-Based Structure**: Code organized by domain feature (Post, Category, User)
2. **Explicit Dependencies**: All dependencies visible in constructors
3. **Thin Actions**: Business logic in domain, actions orchestrate only
4. **No Service Locators**: Never use container directly in application code
5. **Type Safety**: Strict types, enums, and type hints everywhere

## Testing Strategy

### Test Types

1. **Unit Tests**: Domain logic in isolation
2. **Functional Tests**: HTTP request/response cycles
3. **Console Tests**: CLI command testing

### Test Location

```
tests/
├── Unit/           # Domain and service unit tests
├── Functional/     # HTTP functional tests
├── Console/        # Console command tests
├── Site/           # Site-specific tests
└── Support/        # Test helpers and fixtures
```

### Running Tests

```bash
make test           # Run all tests
make test-unit      # Run unit tests only
make test-coverage  # Generate coverage report
```

## Migration Strategy

Migrations follow Yii3's migration system:

**Location**: [src/Migration/](src/Migration/)

**Pattern**:
```php
class M250101_000001_create_post_table extends Migration
{
    public function up(): void
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            // columns...
        ]);
    }

    public function down(): void
    {
        $this->dropTable('post');
    }
}
```

**Commands**:
```bash
make migrate-up      # Apply migrations
make migrate-down    # Revert migrations
make migrate-reset   # Reset database
```

## Component Relationships

```
User ─────────── writes ──────────→ Post
                                      │
                                      ├── has ──→ PostStatus (enum)
                                      │
                                      └── belongs to many ──→ Category
                                                               │
                                                               └── has ──→ CategoryStatus (enum)
```

## Future Considerations

1. **Event Sourcing**: For audit trail requirements
2. **CQRS Full**: Complete separation of read/write models
3. **Hexagonal Architecture**: Ports and adapters for better testability
4. **Microservices**: If scaling requirements emerge
