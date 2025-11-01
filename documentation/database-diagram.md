# 🗃️ Диаграмма базы данных портфолио

## Структура таблиц и связей

```mermaid
erDiagram
    CATEGORY {
        int id PK "Уникальный идентификатор категории"
        string name UK "Название категории (Веб-разработка, UI/UX)"
        string slug UK "URL-название (web-development)"
        text description "Описание категории"
        string color "Цвет в HEX (#3B82F6)"
        string icon "Иконка PrimeIcons"
        string image "URL изображения"
        string meta_title "SEO title"
        text meta_description "SEO description"
        int sort_order "Порядок сортировки"
        boolean is_visible "Видимость на сайте"
        int item_count "Количество статей"
        datetime created_at "Дата создания"
        datetime updated_at "Дата обновления"
    }

    ARTICLE {
        int id PK "Уникальный идентификатор статьи"
        string title UK "Заголовок статьи"
        string slug UK "URL-название"
        text excerpt "Краткое описание"
        text content "Полное содержание"
        string cover_image "URL обложки"
        int reading_time "Время чтения (мин)"
        int view_count "Счетчик просмотров"
        boolean is_published "Опубликована"
        boolean is_featured "Рекомендуемая"
        string meta_title "SEO title"
        text meta_description "SEO description"
        datetime published_at "Дата публикации"
        datetime created_at "Дата создания"
        datetime updated_at "Дата обновления"
        int category_id FK "Категория"
    }

    TAG {
        int id PK "Уникальный идентификатор тега"
        string name UK "Название тега (PHP, Vue.js)"
        text description "Описание тега"
        datetime created_at "Дата создания"
    }

    PROJECT {
        int id PK "Уникальный идентификатор проекта"
        string title UK "Название проекта"
        string slug UK "URL-название"
        text description "Краткое описание"
        text content "Подробное описание"
        string cover_image "URL обложки"
        string project_url "URL проекта"
        string github_url "URL GitHub"
        string demo_url "URL демо"
        string status "Статус: planning, in_progress, completed"
        datetime created_at "Дата создания"
        datetime updated_at "Дата обновления"
    }

    ARTICLE_TAGS {
        int article_id PK,FK "ID статьи"
        int tag_id PK,FK "ID тега"
    }

    PROJECT_TAGS {
        int project_id PK,FK "ID проекта"
        int tag_id PK,FK "ID тега"
    }

    CATEGORY ||--o{ ARTICLE : "имеет"
    ARTICLE }o--o{ TAG : "помечена" via ARTICLE_TAGS
    PROJECT }o--o{ TAG : "помечен" via PROJECT_TAGS
```

## 🔗 Описание связей

- **CATEGORY → ARTICLE**: Одна категория имеет много статей
- **ARTICLE ↔ TAG**: Многие-ко-многим через таблицу ARTICLE_TAGS  
- **PROJECT ↔ TAG**: Многие-ко-многим через таблицу PROJECT_TAGS

## 📊 Статистика
- **4 основные таблицы**
- **2 таблицы связей** 
- **15+ полей в основных таблицах**