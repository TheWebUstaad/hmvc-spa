# Sobeat.com.pk — CodeIgniter 3 HMVC + SPA Starter

Open-source **CodeIgniter 3** starter built with **Modular HMVC** and a lightweight **Single Page Application (SPA)** layer. Use it to ship modular PHP apps that feel fast in the browser while remaining crawlable for search engines.

**Live project path (local):** `http://localhost/sobeat.com.pk/`

---

## Why this stack?

| Need | Solution in this project |
|------|--------------------------|
| Reusable features (auth, blog, shop) | **HMVC modules** — each feature is its own MVC folder |
| Fast page switches without full reload | **SPA shell** — AJAX loads HTML/JSON partials |
| Google / SEO indexing | Full HTML on first load + real URLs + History API |
| Easy for beginners | Clear folder layout, sample modules, copy-paste recipes |

---

## Features

- CodeIgniter **3.1.13**
- **Modular Extensions (MX / HMVC)** — `application/third_party/MX`
- **SPA mode** — shell layout + client router (`assets/js/spa.js`)
- Clean URLs via root `.htaccess` (`index.php` removed)
- Sample modules: `home`, `welcome`, `spa`
- Base controller: `MY_Controller` with `spa_render()`
- Central SPA config: `application/config/spa.php`

---

## Requirements

- PHP **7.4+** (tested on 7.4)
- Apache with `mod_rewrite` (XAMPP / WAMP / LAMP)
- MySQL / MariaDB (optional until you enable the database)

---

## Quick start (XAMPP)

1. Place the project in your web root, e.g.  
   `D:\xampcode\htdocs\sobeat.com.pk`
2. Open `application/config/config.php` and set your base URL:

```php
$config['base_url'] = 'http://localhost/sobeat.com.pk/';
$config['index_page'] = '';
```

3. Confirm `.htaccess` `RewriteBase` matches your folder:

```apache
RewriteBase /sobeat.com.pk/
```

4. Start Apache (and MySQL if needed), then open:

```
http://localhost/sobeat.com.pk/
```

5. Click **Home** / **Welcome** — content should change **without** a full page reload.

---

## Project structure

```
sobeat.com.pk/
├── application/
│   ├── config/
│   │   ├── config.php      # base_url, modules_locations, spa_enabled
│   │   ├── routes.php
│   │   └── spa.php         # SPA nav + shell settings
│   ├── core/
│   │   ├── MY_Controller.php
│   │   ├── MY_Loader.php
│   │   └── MY_Router.php
│   ├── modules/            # ← HMVC modules live here
│   │   ├── home/
│   │   ├── welcome/
│   │   └── spa/            # SPA shell view
│   └── third_party/MX/     # Modular Extensions (HMVC)
├── assets/
│   ├── css/spa.css
│   └── js/spa.js
├── system/                 # CodeIgniter core (do not edit)
├── .htaccess
├── index.php
└── README.md
```

### Typical module layout

```
application/modules/blog/
├── controllers/
│   └── Blog.php
├── models/
│   └── Blog_model.php
├── views/
│   └── index.php
├── config/
│   └── routes.php          # optional
└── libraries/              # optional
```

URL pattern:

```
/module
/module/method
/module/controller/method
```

Example: `http://localhost/sobeat.com.pk/home`

---

## How SPA mode works

1. **First visit / refresh**  
   Server returns the full page: SPA shell + current module HTML.  
   Search engines and social crawlers see real content.

2. **In-app navigation**  
   `assets/js/spa.js` intercepts internal links, requests the same URL with SPA headers, and injects JSON `{ title, html }` into `#spa-content`.

3. **Deep links & back button**  
   History API keeps real URLs (`/home`, `/welcome`). Refresh always works.

### SPA config (`application/config/spa.php`)

```php
$config['spa_enabled']   = TRUE;
$config['spa_shell']     = 'spa/shell';
$config['spa_container'] = 'spa-content';
$config['spa_nav'] = array(
    'home'    => 'Home',
    'welcome' => 'Welcome',
);
```

Add every public page you want in the top nav to `spa_nav`.

---

## Create a new module (step by step)

### 1. Create folders

```
application/modules/blog/controllers/
application/modules/blog/views/
```

### 2. Controller — `application/modules/blog/controllers/Blog.php`

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller
{
    public function index()
    {
        $this->spa_render('index', array(
            'heading' => 'Blog',
            'message' => 'Posts will appear here.',
        ), 'Blog');
    }
}
```

### 3. View — `application/modules/blog/views/index.php`

```php
<section class="page">
    <h1><?php echo html_escape($heading); ?></h1>
    <p><?php echo html_escape($message); ?></p>
</section>
```

### 4. Register in SPA nav

In `application/config/spa.php`:

```php
$config['spa_nav'] = array(
    'home'    => 'Home',
    'welcome' => 'Welcome',
    'blog'    => 'Blog',
);
```

### 5. Open it

```
http://localhost/sobeat.com.pk/blog
```

### Call another module from a view (true HMVC)

```php
<?php echo Modules::run('welcome'); ?>
```

---

## Database (optional)

Edit `application/config/database.php`, then autoload or load the DB:

```php
// application/config/autoload.php
$autoload['libraries'] = array('database');
```

---

## SEO & Google indexing (important)

This SPA is **server-rendered first**, so Google can index pages without relying on JavaScript.

### Do this for better ranking

1. **Unique titles** — pass a clear title into `spa_render(..., 'Page Title')`.
2. **Real URLs** — keep pretty paths (`/blog`, `/about`); avoid hash routes (`#/blog`).
3. **Meta description** — extend the SPA shell (`application/modules/spa/views/shell.php`) with:

```html
<meta name="description" content="<?php echo html_escape($meta_description); ?>">
```

4. **Canonical URL** in the shell:

```html
<link rel="canonical" href="<?php echo current_url(); ?>">
```

5. **`robots.txt`** (project root):

```
User-agent: *
Allow: /
Sitemap: https://sobeat.com.pk/sitemap.xml
```

6. **XML sitemap** — list every public module URL and submit it in [Google Search Console](https://search.google.com/search-console).
7. **Production `base_url`** — set HTTPS domain in `config.php` (never leave `localhost` live).
8. **Performance** — compress assets, use caching, keep HTML lean.
9. **Mobile** — shell CSS is responsive; test with Mobile-Friendly Test.
10. **Avoid blocking crawlers** — do not require JS-only content for primary text.

### How crawlers see this app

| Request type | Response |
|--------------|----------|
| Normal browser / Googlebot | Full HTML document (shell + content) |
| SPA AJAX (`X-SPA-Request: 1`) | JSON partial for fast client updates |

That dual response is intentional: users get SPA speed; search engines get indexable HTML.

---

## Key configuration checklist

| File | What to set |
|------|-------------|
| `application/config/config.php` | `base_url`, `index_page = ''`, `modules_locations` |
| `application/config/spa.php` | `spa_nav`, shell, container id |
| `application/config/routes.php` | `default_controller` (currently `home`) |
| `application/config/database.php` | DB credentials when needed |
| `.htaccess` | `RewriteBase` for your folder / domain |

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| 404 on `/home` | Enable `mod_rewrite`; check `.htaccess` `RewriteBase` |
| CSS/JS 404 | Confirm `base_url` ends with `/` and matches folder name |
| Blank page | Set `ENVIRONMENT` to `development` in `index.php` and check `application/logs/` |
| SPA always full reloads | Ensure `assets/js/spa.js` loads; check browser console |
| Module not found | Controller filename/class must match (e.g. `Blog.php` / `class Blog`) and extend `MY_Controller` |

---

## Tech credits

- [CodeIgniter 3](https://codeigniter.com/userguide3/)
- [Modular Extensions HMVC (MX)](https://github.com/N3Cr0N/HMVC) — based on Wiredesignz Modular Extensions

---

## License

CodeIgniter is released under the [MIT License](https://opensource.org/licenses/MIT).  
HMVC Modular Extensions follow their upstream license.  
Project-specific SPA/HMVC wiring in this repo is free to use and adapt for personal or commercial projects.

---

## Contributing

Improvements welcome: new modules, SEO helpers, sitemap generator, auth module, API examples. Keep modules self-contained so others can drop them into their own apps.

---

**Sobeat.com.pk** — modular PHP, fast SPA UX, search-friendly by design.
