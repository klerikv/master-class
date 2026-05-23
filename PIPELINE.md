# About CI/CD Pipeline

Пайплайн запускается при каждом push или pull request в ветки `develop`, `uat` и `main`

## Шаги пайплайна

### 1. Tests (Тестирование)
- **Команда:** `php artisan test --coverage --min=50`

### 2. Static Analysis (Статический анализ)
- **Инструмент:** Larastan (PHPStan)
- **Команда:** `vendor/bin/phpstan analyse app --level=5`

### 3. Linting (Проверка стиля кода)
- **Инструмент:** Laravel Pint
- **Команда:** `vendor/bin/pint --test`

### 4. Manual Approval (Ручной аппрув) - ТОЛЬКО для main

### 5. Simulate Deployment (Симуляция деплоя)
Запускается для долгоживущих веток:
- develop → use .env.dev
- uat → use .env.uat
- main → use .env.prod


### 6. Notification (Уведомление)
- **Что делает:** Создает сообщение с результатами пайплайна