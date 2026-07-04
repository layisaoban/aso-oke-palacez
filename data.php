<?php
$contacts = [
  ["label" => "Primary line", "display" => "+234 708 268 6649", "tel" => "+2347082686649", "wa" => "2347082686649"],
  ["label" => "Second line", "display" => "+234 816 465 6076", "tel" => "+2348164656076", "wa" => "2348164656076"],
];

$categories = ["Sanyan", "Etu", "Alari", "Demask", "Loom", "Alawe"];

$categoryTiles = [
  ["name" => "All", "note" => "Every style in store", "search" => ""],
  ["name" => "Sanyan", "note" => "Gold ceremonial sets", "search" => "Sanyan"],
  ["name" => "Etu", "note" => "Deep blue heritage weave", "search" => "Etu"],
  ["name" => "Alari", "note" => "Rich crimson ceremonial cloth", "search" => "Alari"],
  ["name" => "Demask", "note" => "Bold formal fabric", "search" => "Demask"],
  ["name" => "Loom", "note" => "Textured woven pieces", "search" => "Loom"],
  ["name" => "Alawe", "note" => "Heritage Alawe pieces", "search" => "Alawe"],
];

$orders = [
  ["id" => "AOP-1024", "customer" => "Sample Customer", "status" => "Pending", "total" => 235000, "method" => "Bank Transfer", "date" => "2026-06-16"],
  ["id" => "AOP-1023", "customer" => "Wedding Client", "status" => "Completed", "total" => 420000, "method" => "Paystack", "date" => "2026-06-14"],
];

$products = [
  [
    "id" => 1,
    "slug" => "royal-gold-sanyan-couple-set",
    "name" => "Royal Gold Sanyan Couple Set",
    "category" => "Sanyan",
    "price" => 185000,
    "rating" => 4.9,
    "popularity" => 98,
    "stock" => 7,
    "color" => "Gold",
    "sizes" => ["Couple Set", "Family Pack", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260701,
    "description" => "A premium gold-toned Sanyan set prepared for introductions, weddings, and formal family ceremonies."
  ],
  [
    "id" => 2,
    "slug" => "midnight-etu-heritage-weave",
    "name" => "Midnight Etu Heritage Weave",
    "category" => "Etu",
    "price" => 128000,
    "rating" => 4.8,
    "popularity" => 92,
    "stock" => 12,
    "color" => "Blue",
    "sizes" => ["4 Yards", "6 Yards", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260630,
    "description" => "Deep blue Etu weave with a crisp ceremonial finish and strong everyday cultural presence."
  ],
  [
    "id" => 3,
    "slug" => "alari-crimson-bridal-bundle",
    "name" => "Alari Crimson Bridal Bundle",
    "category" => "Alari",
    "price" => 245000,
    "rating" => 5.0,
    "popularity" => 96,
    "stock" => 4,
    "color" => "Red",
    "sizes" => ["Bride Set", "Family Pack", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260628,
    "description" => "Rich crimson Alari bundle with wrapper, gele, and matching ceremonial styling options."
  ],
  [
    "id" => 4,
    "slug" => "teal-loom-texture-pack",
    "name" => "Teal Loom Texture Pack",
    "category" => "Loom",
    "price" => 94000,
    "rating" => 4.7,
    "popularity" => 81,
    "stock" => 16,
    "color" => "Teal",
    "sizes" => ["4 Yards", "6 Yards", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260625,
    "description" => "Textured handwoven piece for customers who want subtle pattern, color depth, and daily elegance."
  ],
  [
    "id" => 5,
    "slug" => "wine-demask-prestige-cloth",
    "name" => "Wine Demask Prestige Cloth",
    "category" => "Demask",
    "price" => 165000,
    "rating" => 4.8,
    "popularity" => 88,
    "stock" => 6,
    "color" => "Wine",
    "sizes" => ["4 Yards", "6 Yards", "8 Yards"],
    "image" => "",
    "images" => [],
    "created" => 20260622,
    "description" => "Formal Demask cloth with a wine finish, ideal for chiefs, family heads, and standout guests."
  ],
  [
    "id" => 6,
    "slug" => "alawe-cream-heritage-piece",
    "name" => "Alawe Cream Heritage Piece",
    "category" => "Alawe",
    "price" => 118000,
    "rating" => 4.6,
    "popularity" => 74,
    "stock" => 9,
    "color" => "Cream",
    "sizes" => ["4 Yards", "6 Yards", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260620,
    "description" => "Soft cream Alawe piece with a calm traditional feel for naming ceremonies and family events."
  ],
  [
    "id" => 7,
    "slug" => "multi-color-celebration-set",
    "name" => "Multi Color Celebration Set",
    "category" => "Loom",
    "price" => 138000,
    "rating" => 4.7,
    "popularity" => 86,
    "stock" => 10,
    "color" => "Multi",
    "sizes" => ["4 Yards", "6 Yards", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260618,
    "description" => "A colorful celebration set for birthdays, aso ebi groups, and modern cultural styling."
  ],
  [
    "id" => 8,
    "slug" => "gold-fila-and-ipele-add-on",
    "name" => "Gold Fila and Ipele Add-on",
    "category" => "Sanyan",
    "price" => 52000,
    "rating" => 4.5,
    "popularity" => 70,
    "stock" => 18,
    "color" => "Gold",
    "sizes" => ["M", "L", "XL", "Custom"],
    "image" => "",
    "images" => [],
    "created" => 20260616,
    "description" => "Matching fila and ipele accessory set for finishing an outfit with a coordinated touch."
  ],
];

function e($value) {
  return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

function money($amount) {
  return "NGN " . number_format((float) $amount, 0);
}
