# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### PHP & Laravel
- `php artisan serve` - Start development server
- `php artisan migrate` - Run database migrations
- `php artisan tinker` - Open interactive shell
- `composer install` - Install PHP dependencies
- `vendor/bin/phpunit` - Run tests
- `vendor/bin/sail up` - Start Docker containers

### Frontend Assets
- `npm run dev` - Start Vite development server with hot reload
- `npm run build` - Build production assets
- `npm install` - Install Node.js dependencies

### Docker
- `docker compose build` - Build containers
- `docker compose up` - Start application stack
- Access at http://localhost:8001 (default user: webtool, password: test)

## Architecture Overview

### Core Framework
This is a Laravel 11 application with a custom ORM layer called "Orkester" that extends Laravel's capabilities for linguistic data management.

### Key Directories
- `app/` - Laravel application code
  - `Http/Controllers/` - Route controllers using PHP attributes for routing
  - `Services/` - Business logic layer for annotation, reports, and data processing
  - `Data/` - Data transfer objects and form validation
  - `Repositories/` - Data access layer abstractions
- `orkester/` - Custom ORM and persistence framework
  - `Persistence/` - Database abstraction, criteria, and model mapping
  - `Security/` - Custom authentication system (MAuth)
- `resources/` - Frontend assets and Blade templates
- `public/scripts/` - Third-party JavaScript libraries (jQuery EasyUI, JointJS, etc.)
- `config/webtool.php` - Application-specific configuration and menu structure

### Authentication & Authorization
Uses a custom MAuth system with role-based access control (ADMIN, MANAGER, MASTER levels). Can integrate with Auth0 for external authentication.

### Frontend Architecture
- Uses Laravel Blade templates with custom UI components
- Vite for asset compilation with LESS preprocessing
- Heavy use of jQuery EasyUI for data grids and forms
- JointJS for graph visualizations (frame relations, semantic networks)
- HTMX for dynamic content updates

### Database Layer
The Orkester framework provides:
- Custom Criteria API for complex queries
- Repository pattern implementation
- Support for multilingual data structures
- Specialized handling of linguistic relationships (frames, constructions, semantic types)

**Database Schema (`webtool40_db`)**:
The schema is designed around linguistic annotation and FrameNet concepts:

**Core Linguistic Entities:**
- `frame` - Semantic frames with multilingual descriptions
- `frameelement` - Frame elements (FEs) with coreness types and color coding
- `construction` - Grammatical constructions with abstract patterns
- `constructionelement` - Construction elements with constraints
- `lu` (Lexical Units) - Words that evoke frames
- `lexicon` - Lexical entries with morphological information

**Annotation System:**
- `annotationset` - Groups annotations for sentences/documents
- `annotation` - Individual annotations linking text spans to semantic elements
- `annotationobject` - Objects being annotated (text, static, dynamic)
- `staticannotationmm` - Static multimodal annotations (images)
- `annotationmm` - Dynamic multimodal annotations (video)

**Content Management:**
- `corpus` - Text corpora organization
- `document` - Individual documents within corpora
- `sentence` - Sentence-level segmentation
- `image`/`video` - Multimodal content for annotation

**User & Task Management:**
- `user` - User accounts with authentication
- `usertask` - Task assignments for annotation projects
- `user_group` - Role-based access control

**Semantic Relations:**
- `entityrelation` - Generic relation framework
- `relationtype` - Types of semantic relations (inheritance, subframe, etc.)
- Views like `view_frame_relation` provide structured access to semantic networks

**Key Views:**
- `view_*` tables provide optimized queries for complex linguistic data relationships
- Include multilingual support and efficient access patterns for annotation interfaces

### Key Features
- **Annotation Tools**: Multiple annotation modes (static/dynamic, full-text, deixis, bounding boxes)
- **Linguistic Data Management**: Frames, constructions, lexical units, semantic types
- **Visualization**: Interactive graphs for semantic networks and frame relations  
- **Multimodal Support**: Video and image annotation capabilities
- **Export Systems**: XML export with XSD validation for linguistic data interchange

### Testing
Uses PHPUnit for testing. Run tests with `vendor/bin/phpunit`.

### Configuration
- Main app configuration in `config/webtool.php`
- Environment variables in `.env` file
- Database configuration supports multiple connections defined in `config/database.php`