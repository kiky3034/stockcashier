# StockCashier

**StockCashier** adalah aplikasi kasir, inventory, purchase, refund, dan reporting berbasis **Laravel 13**. Project ini dibuat untuk membantu operasional toko dengan alur multi-role: **Admin**, **Owner**, **Cashier**, dan **Warehouse Staff**.

Repository: [github.com/kiky3034/stockcashier](https://github.com/kiky3034/stockcashier)

---

## Preview Singkat

StockCashier memiliki fitur utama:

- POS cashier dengan scan barcode / SKU.
- Manajemen produk, kategori, satuan, supplier, warehouse, dan stok.
- Stock adjustment dan stock movement tracking.
- Purchase barang masuk dari supplier.
- Sales history, invoice detail, print receipt, void sale, dan refund.
- Owner reports untuk sales, profit, stock, dan purchase.
- Store settings, logo toko, receipt 58mm / 80mm, dan auto print.
- Activity log untuk audit aktivitas sistem.
- Backup database manual.
- UI modern bertema **lightblue**, responsive, SweetAlert2, Toast, dan landing page animatif.

---

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 13 |
| PHP | PHP 8.3+ |
| Frontend | Blade, Tailwind CSS, Vite |
| Database | MySQL / MariaDB |
| Authorization | Spatie Laravel Permission |
| Alert / Dialog | SweetAlert2 |
| Build Tool | Vite |
| Styling | Tailwind CSS |
| Animation Landing Page | GSAP + ScrollTrigger via CDN |

---

## Role dan Hak Akses

### Admin

Admin berfokus pada pengelolaan sistem dan master data.

Fitur utama:

- Dashboard admin.
- User management.
- Master data: categories, units, suppliers, warehouses, products.
- Inventory: stocks, stock movements, stock adjustments.
- Purchases.
- Activity logs.
- Settings.
- Backups.

### Owner

Owner berfokus pada monitoring dan laporan.

Fitur utama:

- Owner dashboard.
- Sales report.
- Profit report.
- Stock report.
- Purchase report.
- Export laporan.

### Cashier

Cashier berfokus pada transaksi penjualan.

Fitur utama:

- Cashier dashboard.
- POS.
- Scan barcode / SKU.
- Sales history.
- Receipt / print invoice.
- Void sale sesuai permission route.
- Refund sale.

### Warehouse Staff

Warehouse staff berfokus pada operasional gudang.

Fitur utama:

- Warehouse dashboard.
- Products.
- Stocks.
- Stock movements.
- Stock adjustment.
- Purchases.

---

## Fitur Utama

## 1. Authentication & Authorization

- Login dan logout.
- Role-based routing.
- Middleware permission menggunakan Spatie Laravel Permission.
- Pencegahan back button setelah logout dengan no-cache middleware.
- Sidebar menampilkan menu sesuai role.

---

## 2. POS Cashier

Fitur POS:

- Search product.
- Scan barcode / SKU.
- Cart interaktif.
- Tombol tambah / kurang quantity.
- Validasi stok berdasarkan warehouse.
- Exact payment / bayar pas.
- Payment method: cash, QRIS, transfer, card.
- Payment reference.
- Notes transaksi.
- Keyboard shortcut:
  - `F2` untuk search produk.
  - `F4` untuk scan barcode.
  - `F9` untuk input pembayaran.
  - `Ctrl + Enter` untuk complete sale.
- SweetAlert2 untuk error penting.
- Toast untuk feedback ringan.

---

## 3. Sales, Receipt, Void, dan Refund

Fitur sales:

- Sales history.
- Invoice detail.
- Print receipt.
- Receipt ukuran 58mm / 80mm.
- Auto print receipt.
- Payment method tampil pada receipt.
- Void sale.
- Refund sebagian / penuh.
- Refund history.
- Copy invoice dengan Toast.

---

## 4. Inventory

Fitur inventory:

- Product management.
- Upload gambar produk.
- SKU dan barcode.
- Category.
- Unit.
- Supplier.
- Warehouse.
- Product active / inactive.
- Track stock / non-track stock.
- Stock alert level.
- Stock per warehouse.
- Low stock indicator.

---

## 5. Stock Movement & Adjustment

Fitur stok:

- Stock index per warehouse.
- Stock movement history.
- Stock adjustment in / out.
- Validasi quantity.
- Activity log untuk adjustment.
- Detail stock movement dengan SweetAlert2.

---

## 6. Purchase

Fitur purchase:

- Purchase dari supplier.
- Warehouse tujuan barang masuk.
- Multiple purchase items.
- Discount dan tax.
- Notes.
- Purchase detail.
- Purchase history.
- Copy purchase number.
- Purchase otomatis menambah stok.

---

## 7. Owner Reports

Report yang tersedia:

- Sales report.
- Profit report.
- Stock report.
- Purchase report.
- Filter berdasarkan tanggal.
- Export CSV.
- Copy summary dengan Toast.
- Low stock warning.

---

## 8. App Settings

Admin dapat mengatur:

- Store name.
- Store address.
- Store phone.
- Store email.
- Store logo.
- Currency prefix.
- Receipt footer.
- Receipt paper size: 58mm / 80mm.
- Auto print receipt.
- Show / hide logo on receipt.

---

## 9. Backup Database

Fitur backup:

- Download backup database dalam format SQL.
- Activity log untuk event backup.
- Cocok untuk backup manual tahap awal.

> Untuk production besar, tetap disarankan menggunakan backup otomatis dari server / hosting.

---

## 10. Activity Log

Activity log mencatat aktivitas penting seperti:

- User login / logout.
- User created / updated / deleted.
- Product created / updated / deleted.
- Stock adjusted.
- Purchase created.
- Sale created.
- Sale voided.
- Sale refunded.
- Receipt viewed.
- Settings updated.
- Database backup downloaded.

---

## Instalasi Lokal

### 1. Clone repository

```bash
git clone https://github.com/kiky3034/stockcashier.git
cd stockcashier
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Install dependency JavaScript

```bash
npm install
```

### 4. Buat file `.env`

```bash
cp .env.example .env
```

Untuk Windows PowerShell:

```powershell
copy .env.example .env
```

### 5. Generate app key

```bash
php artisan key:generate
```

### 6. Konfigurasi database

Sesuaikan file `.env`:

```env
APP_NAME=StockCashier
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockcashier
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

Buat database:

```sql
CREATE DATABASE stockcashier;
```

### 7. Jalankan migration dan seeder

```bash
php artisan migrate --seed
```

### 8. Buat storage link

```bash
php artisan storage:link
```

Ini diperlukan untuk menampilkan:

- Logo toko.
- Gambar produk.
- File upload dari disk `public`.

### 9. Jalankan aplikasi

```bash
composer run dev
```

Atau jalankan manual di dua terminal:

```bash
php artisan serve
```

```bash
npm run dev
```

Buka aplikasi:

```text
http://127.0.0.1:8000
```

---

## Build Production

Untuk production:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Pastikan `.env` production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainkamu.com
```

---

## Struktur Folder Penting

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── Cashier/
│   │   └── Owner/
│   └── Middleware/
├── Models/
└── Services/

database/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/
    ├── auth/
    ├── components/
    ├── pages/
    │   ├── admin/
    │   ├── cashier/
    │   ├── owner/
    │   └── warehouse/
    └── welcome.blade.php

routes/
└── web.php
```

---

## Route Utama

| Area | URL |
|---|---|
| Welcome | `/` |
| Login | `/login` |
| Dashboard Redirect | `/dashboard` |
| Admin Dashboard | `/admin/dashboard` |
| Products | `/admin/products` |
| Stocks | `/admin/stocks` |
| Stock Movements | `/admin/stock-movements` |
| Stock Adjustment | `/admin/stock-adjustments/create` |
| Purchases | `/admin/purchases` |
| Users | `/admin/users` |
| Settings | `/admin/settings` |
| Backups | `/admin/backups` |
| Cashier Dashboard | `/cashier/dashboard` |
| POS | `/cashier/pos` |
| Sales History | `/cashier/sales` |
| Owner Dashboard | `/owner/dashboard` |
| Sales Report | `/owner/reports/sales` |
| Profit Report | `/owner/reports/profit` |
| Stock Report | `/owner/reports/stock` |
| Purchase Report | `/owner/reports/purchases` |
| Warehouse Dashboard | `/warehouse/dashboard` |

---

## Default User

Default user dibuat melalui seeder.

Cek file berikut untuk email dan password default:

```text
database/seeders/AdminUserSeeder.php
```

Role yang tersedia:

```text
admin
owner
cashier
warehouse staff
```

---

## Perintah Artisan Berguna

Clear cache:

```bash
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
```

Migrate ulang dari awal:

```bash
php artisan migrate:fresh --seed
```

Jalankan seeder tertentu:

```bash
php artisan db:seed --class=AppSettingSeeder
```

Cek route:

```bash
php artisan route:list
```

Format kode jika Laravel Pint tersedia:

```bash
./vendor/bin/pint
```

---

## Catatan Development Windows / Laragon

Jika menggunakan Windows / Laragon, fitur Laravel Pail bisa bermasalah karena membutuhkan ekstensi `pcntl`, sedangkan `pcntl` umumnya tidak tersedia di Windows.

Gunakan script dev yang hanya menjalankan server dan Vite:

```bash
composer run dev
```

Atau manual:

```bash
php artisan serve
npm run dev
```

---

## Security Notes

- Jangan commit file `.env`.
- Pastikan `APP_DEBUG=false` di production.
- Gunakan password database yang kuat.
- Batasi akses backup database hanya untuk admin.
- Pastikan folder `storage` dan `bootstrap/cache` writable di server.
- Gunakan HTTPS untuk production.
- Backup database secara berkala.

---

## Deployment Checklist

Sebelum deploy:

- [ ] `.env` production sudah benar.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_KEY` sudah dibuat.
- [ ] Database production sudah dibuat.
- [ ] `composer install --no-dev --optimize-autoloader`.
- [ ] `npm ci`.
- [ ] `npm run build`.
- [ ] `php artisan migrate --force`.
- [ ] `php artisan storage:link`.
- [ ] `php artisan optimize`.
- [ ] Login admin berhasil.
- [ ] POS berhasil membuat transaksi.
- [ ] Receipt bisa dicetak.
- [ ] Owner report tampil.
- [ ] Activity log berjalan.

---

## Roadmap Pengembangan

Ide pengembangan berikutnya:

- Import produk dari CSV / Excel.
- Export produk.
- Barcode generator.
- Customer management.
- Multi outlet.
- Purchase return.
- Expense management.
- Dashboard chart lebih detail.
- API endpoint untuk mobile app.
- Notification low stock.
- Scheduled backup otomatis.

---

## Kontribusi

Project ini masih dapat dikembangkan. Pull request, issue, dan saran sangat terbuka.

Alur kontribusi:

1. Fork repository.
2. Buat branch fitur.
3. Commit perubahan.
4. Push branch.
5. Buat pull request.

---

## License

Project ini mengikuti license bawaan Laravel skeleton, yaitu **MIT License**.

---

## Author

Dibuat oleh [@kiky3034](https://github.com/kiky3034).
