# Aso Oke Palacez - PHP Website

This version runs through PHP instead of the previous Next.js/Node.js app.
The main website entry is `index.php`, shared store data lives in `data.php`,
and the browser-side cart, wishlist, filters, admin product entry, image
preview, and theme controls live in `assets/app.js`.

The old Next.js folders are still present as reference files, but they are no
longer needed to open the PHP website.

## Run Locally

Install PHP, then run this from the project folder:

```powershell
php -S localhost:8000 index.php
```

Open:

```text
http://localhost:8000
```

## PHP Routes

The PHP router in `index.php` handles these pages:

| Route | Page |
|---|---|
| `/` | Home, categories, featured products |
| `/shop` | Shop filters and product grid |
| `/product/{id}` | Product detail page |
| `/wishlist` | Saved products |
| `/orders` | Order tracking |
| `/support` | Customer support |
| `/about` | Store story |
| `/admin` | Product and site-image controls |

## Storage

Products, cart items, wishlist items, orders, theme, and uploaded image previews
are stored in the browser with `localStorage`, matching the previous frontend
behavior. A real PHP database connection can replace that later without needing
Node.js.
