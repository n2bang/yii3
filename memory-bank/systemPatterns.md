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
namespace App\Shared\Read;

class PostReadRepository
{
    public function findPublished(int $limit, int $offset): array
    {
        return Post::find()
            ->where(['status' => PostStatus::Published])
            ->orderBy(['publication_date' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }
}
```

**Location**: [src/Shared/Read/](src/Shared/Read/)

**Benefits**:
- Query optimization without affecting domain
- Separation of read/write concerns
- Easy to add projections and DTOs

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

Cross-cutting concerns implemented as PSR-15 middleware.

**Structure**:
```php
namespace App\Presentation\Middleware;

class CheckAccess implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Permission check before action
        if (!$this->canAccess($request)) {
            return new ForbiddenResponse();
        }

        return $handler->handle($request);
    }
}
```

**Common Middleware**:
- `CheckAccess` - RBAC permission validation
- `ErrorCatcher` - Exception handling
- `NotFound` - 404 handling

**Configuration**: [config/site/params.php](config/site/params.php) (middleware stack)

### 7. Presenter Pattern (API Responses)

Convert domain entities to API-friendly formats.

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
            'slug' => $post->slug,
            'excerpt' => substr($post->body, 0, 200),
            'publication_date' => $post->publication_date->format('Y-m-d'),
            'author' => $post->creator->name,
        ];
    }
}
```

**Location**: [src/Presentation/Api/](src/Presentation/Api/)

**Benefits**:
- Consistent API responses
- Hide internal implementation
- Easy to version APIs
- Transform data as needed

### 8. Widget Pattern (Reusable UI Components)

Encapsulate complex UI logic in reusable widgets.

**Structure**:
```php
namespace App\Presentation\Site\Widgets;

class PostsList extends Widget
{
    public function __construct(
        private readonly ViewRenderer $view,
        private array $posts,
        private ?Pagination $pagination = null,
    ) {}

    public function render(): string
    {
        return $this->view->render('_posts-list', [
            'posts' => $this->posts,
            'pagination' => $this->pagination,
        ]);
    }
}
```

**Examples**:
- `PostsList` - Displays paginated post list
- `CategoriesPanel` - Sidebar category navigation
- `ShortUserInfo` - Current user display
- `ContentNoticesWidget` - Flash messages

**Location**: [src/Presentation/Site/Widgets/](src/Presentation/Site/Widgets/)

### 9. Form Model Pattern

Separate validation and input handling from domain entities.

**Structure**:
```php
namespace App\UseCase\Diary\Forms;

class PostForm extends FormModel
{
    public string $title = '';
    public string $body = '';
    public string $slug = '';
    public int $status = PostStatus::Draft->value;

    public function getRules(): array
    {
        return [
            'title' => [new Required(), new Length(max: 255)],
            'body' => [new Required()],
            'slug' => [new Length(max: 255)],
            'status' => [new Integer()],
        ];
    }

    public function toEntity(): Post
    {
        $post = new Post();
        $post->title = $this->title;
        $post->body = $this->body;
        $post->slug = $this->slug ?: Str::slug($this->title);
        $post->status = PostStatus::from($this->status);
        return $post;
    }
}
```

**Benefits**:
- Validation separate from domain
- Easy to test validation rules
- User input isolated from domain
- Can have different forms for same entity

### 10. Service Provider Pattern

Configure dependencies and register services.

**Structure**:
```php
// config/common/di/database.php
return [
    ConnectionInterface::class => [
        'class' => Connection::class,
        '__construct()' => [
            'dsn' => DynamicReference::to(fn () =>
                'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME']
            ),
            'username' => DynamicReference::to(fn () => $_ENV['DB_USER']),
            'password' => DynamicReference::to(fn () => $_ENV['DB_PASSWORD']),
        ],
    ],
];
```

**Configuration Files**:
- [config/common/di/database.php](config/common/di/database.php) - Database connection
- [config/common/di/router.php](config/common/di/router.php) - Routing
- [config/site/di/](config/site/di/) - Site-specific services
- [config/api/di/](config/api/di/) - API-specific services

## Architectural Decisions

### 1. Why ActiveRecord Instead of Doctrine/Eloquent?

**Decision**: Use Yii's ActiveRecord for persistence.

**Rationale**:
- Native Yii integration
- Simpler for demo/learning purposes
- Good query builder
- Adequate for application size

**Trade-offs**:
- Domain entities know about persistence (not pure DDD)
- Tightly coupled to database structure
- Acceptable for demo, might reconsider for large production app

### 2. Why Action Classes Instead of Controllers?

**Decision**: One action class per use case instead of fat controllers.

**Rationale**:
- Single Responsibility Principle
- Easier to test
- Clear dependencies
- Better organization

**Benefits**:
- No controller method clutter
- Each action is independently testable
- Easy to locate feature logic

### 3. Why Separate UseCase Layer?

**Decision**: Dedicated layer for application workflows between presentation and domain.

**Rationale**:
- Domain stays pure business logic
- Presentation focuses on UI/API
- UseCase orchestrates workflows
- Clear separation of concerns

**Structure**:
```
UseCase/
├── Diary/
│   ├── Manage/     # Protected diary operations
│   ├── Front/      # Public diary browsing
│   └── Api/        # API endpoints
├── Users/          # User management
├── Profile/        # User profile
├── Login/          # Authentication
└── Logout/
```

### 4. Why Enum for Status Values?

**Decision**: Use PHP 8.1+ enums instead of constants or magic numbers.

**Rationale**:
- Type safety
- No magic numbers in code
- IDE autocomplete
- Explicit value declarations

**Before** (bad):
```php
if ($post->status === 1) { ... }
```

**After** (good):
```php
if ($post->status === PostStatus::Published) { ... }
```

### 5. Why Middleware for Authorization?

**Decision**: Use PSR-15 middleware for RBAC checks instead of action-level checks.

**Rationale**:
- DRY - don't repeat permission checks
- Declarative authorization
- Fails fast before action execution
- Separation of concerns

**Configuration**:
```php
// Route with permission requirement
Group::create('/diary')
    ->middleware(CheckAccess::withPermission(Permission::DiaryManage))
    ->routes(...);
```

### 6. Why Separate Read Models?

**Decision**: Create dedicated read repositories separate from ActiveRecord.

**Rationale**:
- Query optimization without cluttering domain
- CQRS-lite pattern
- Easy to add projections
- Keep domain entities focused on writes

**Pattern**:
- Domain entities handle commands (writes)
- Read repositories handle queries (reads)

## Code Organization Principles

### 1. Directory Structure by Feature

**Pattern**: Organize by feature/use case, not by technical layer.

**Structure**:
```
UseCase/
├── Diary/          # Feature
│   ├── Manage/     # Subfeat
│   ├── Front/
│   └── Api/
└── Users/          # Feature
    ├── CreateAction.php
    ├── EditAction.php
    └── ...
```

**Benefits**:
- Feature cohesion
- Easy to locate related code
- Scales better than layer-first organization

### 2. Naming Conventions

**Actions**: `{Verb}Action` (e.g., `CreateAction`, `EditAction`, `IndexAction`)

**Forms**: `{Entity}Form` (e.g., `PostForm`, `UserForm`)

**Presenters**: `{Entity}Presenter` (e.g., `PostPresenter`)

**Widgets**: `{Purpose}Widget` or descriptive name (e.g., `PostsList`, `CategoriesPanel`)

**Repositories**: `{Entity}Repository` or `{Entity}ReadRepository`

**Enums**: `{Entity}{Property}` (e.g., `PostStatus`, `UserStatus`)

### 3. Constructor Property Promotion

**Pattern**: Use PHP 8.0+ constructor property promotion with readonly where applicable.

```php
public function __construct(
    private readonly Service $service,
    private readonly Repository $repository,
) {}
```

**Benefits**:
- Less boilerplate
- Immutable dependencies
- Clear at a glance

### 4. Strict Types

**Pattern**: All PHP files start with `declare(strict_types=1);`

**Benefits**:
- Type safety
- Catch errors early
- Better IDE support

### 5. PSR Standards

**Followed Standards**:
- PSR-4: Autoloading
- PSR-7: HTTP messages
- PSR-11: Container interface
- PSR-15: HTTP handlers/middleware

## Testing Strategy

### Test Structure

```
tests/
├── Unit/           # Unit tests for domain logic
├── Functional/     # Feature tests using Codeception
└── Console/        # Console command tests
```

### Patterns

**Unit Tests**: Test domain logic in isolation

**Functional Tests**: Test complete user workflows

**Test Database**: Separate test database, reset between tests

## Migration Strategy

**Principle**: All schema changes via migrations, never manual SQL.

**Location**: [src/Migration/](src/Migration/)

**Pattern**:
- One migration per change
- Reversible (up/down methods)
- Idempotent where possible
- Include sample data in development

**Commands**:
```bash
make migrate-up      # Apply migrations
make migrate-down    # Rollback last migration
```

## Future Considerations

### Potential Improvements

1. **Event Sourcing**: Capture domain events explicitly
2. **CQRS**: Separate read/write models more strictly
3. **DTOs**: Add data transfer objects for API requests
4. **Specifications**: Add specification pattern for complex queries
5. **Value Objects**: Extract more value objects from entities
6. **Integration Events**: Add event bus for cross-feature communication
