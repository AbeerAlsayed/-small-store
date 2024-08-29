. At the beginning, create products and categories tables, then implement CRUD for its.
-Use seeders and factories to create fake date, 250 record for each table.
-When you execute index or show functions, using created_at column value add
   dynamically created_from column, the value of it must be like (2 hours ago, two hours
   ago, etc..).
-There is no specific number of subcategories for each category.
   EX:
   category 1 => [category 2, category 3, category 4]
   category 2 => [category 5, category 6]
   category 6 => [category 7, category 8, category 9, category 10]
   category 7 => [no sub categories]
   etc…
