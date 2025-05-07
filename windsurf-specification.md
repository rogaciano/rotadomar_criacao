# Wind Surf Tool Management System Specification

## Overview
This document outlines the specifications for a modern Laravel-based application for wind surf tool management with MySQL, Tailwind CSS, Alpine.js, and other modern tools.

## Technology Stack
- **Backend Framework**: Laravel (Latest version)
- **Database**: MySQL
- **Frontend**: 
  - Tailwind CSS for styling
  - Alpine.js for interactive components
  - Laravel Blade for templating
- **Authentication**: Laravel Breeze/Jetstream
- **Additional Tools**:
  - Laravel Livewire (for reactive components)
  - Laravel Sanctum (for API authentication)
  - Laravel Spatie Permission (for role management)

## Database Models

### 1. Tecidos (Fabrics)
- `id` - Primary Key
- `descricao` - Description of the fabric
- `data_cadastro` - Registration date
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 2. Estilistas (Designers)
- `id` - Primary Key
- `estilista` - Designer name
- `ativo` - Active status (boolean)
- `data_cadastro` - Registration date
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 3. Marcas (Brands)
- `id` - Primary Key
- `marca` - Brand name
- `ativo` - Active status (boolean)
- `data_cadastro` - Registration date
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 4. Grupo de Produtos (Product Groups)
- `id` - Primary Key
- `grupo_produto` - Product group name
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 5. Localizacao (Locations)
- `id` - Primary Key
- `nome_localizacao` - Location name
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 6. Tipo (Types)
- `id` - Primary Key
- `descricao` - Type description (e.g., Criação, Peça Piloto, Monstruário)
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 7. Situacao (Situations)
- `id` - Primary Key
- `descricao` - Situation description (e.g., Ativo, Produção, Compras, Cancelado)
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 8. Status
- `id` - Primary Key
- `descricao` - Status description (e.g., Em processo, Cancelado, Ativo)
- `ativo` - Active status (boolean)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 9. Produtos (Products)
- `id` - Primary Key
- `referencia` - Reference code
- `descricao` - Product description
- `data_cadastro` - Registration date
- `marca_id` - Foreign key to Marcas
- `quantidade` - Quantity
- `tecido_id` - Foreign key to Tecidos
- `estilista_id` - Foreign key to Estilistas
- `grupo_produto_id` - Foreign key to Grupo de Produtos
- `preco_atacado` - Wholesale price
- `preco_varejo` - Retail price
- `status_id` - Foreign key to Status
- `ficha_producao` - Production sheet attachment (file path)
- `catalogo_vendas` - Sales catalog attachment (file path)
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

### 10. Movimentacao (Movement)
- `id` - Primary Key
- `produto_id` - Foreign key to Produtos
- `comprometido` - Committed quantity
- `localizacao_id` - Foreign key to Localizacao
- `data_entrada` - Entry date
- `data_saida` - Exit date
- `tipo_id` - Foreign key to Tipo
- `situacao_id` - Foreign key to Situacao
- `observacao` - Observations
- `created_at`, `updated_at`, `deleted_at` (for soft delete)

## Features

### 1. Authentication System
- User registration and login
- Role-based access control
- Password reset functionality

### 2. Dashboard
- Overview of key metrics
- Charts and graphs showing:
  - Quantities of Products by Type
  - Quantities of Products by Situation
  - Quantities of Products by Status
  - Quantities of references by Type per Designer
  - Quantities of references by Brand
  - Quantities of references by Product Group
  - Quantities of references by Location
  - Quantities of references by Type
  - Quantities of references by Situation
  - Quantities of references by Status

### 3. CRUD Operations for All Models
- Create, Read, Update, Delete functionality for all models
- Soft delete implementation (records are marked as deleted but not physically removed from the database)
- Form validation
- File upload for product attachments

### 4. Search and Filtering
- Advanced search functionality
- Filtering by all fields for each model
- Sorting options
- Pagination

### 5. Reports
- Exportable reports (PDF, Excel)
- Custom report generation based on filters

### 6. Responsive Design
- Mobile-friendly interface
- Optimized for different screen sizes

## User Interface

### 1. Layout
- Modern, clean design using Tailwind CSS
- Responsive sidebar navigation
- Dark/Light mode toggle
- Breadcrumb navigation

### 2. Components
- Data tables with sorting, filtering, and pagination
- Modal dialogs for quick actions
- Form components with validation
- Alert and notification system
- File upload with preview
- Date pickers and selectors

## Security
- CSRF protection
- XSS prevention
- Input validation
- Role-based access control
- Secure password hashing
- Rate limiting for API endpoints

## Performance Optimization
- Database query optimization
- Eager loading of relationships
- Caching strategies
- Asset minification and bundling

## Deployment Considerations
- Environment configuration
- Database migrations and seeders
- Scheduled tasks for maintenance
- Backup strategies

## Future Enhancements
- API integration with external systems
- Multi-language support
- Advanced analytics
- Email notifications
- Activity logging

## Installation Guide
Detailed steps for setting up the development environment and deploying the application will be provided in a separate document.
