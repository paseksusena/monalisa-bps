## Cara install lewat git:

1. git clone https://github.com/paseksusena/monalisa-bps.git
2. masuk ke folder monalisa (pindah ke folder monalisa)
3. install composer, ketik "composer install" 
4. ubah file .env.example menjadi .env 
5. Masuk ke file .env ubah nama database dengan monalisa. DB_DATABASE=monalisa 
5. php artisan key:generate
6. php artisan migrate --seed
7. npm install
8. jalankan server dengan perintah php artisan serve
9. jalankan npm dengan ketik perintah npm run dev atau npm run build.


NB : KETIKA INGIN MEMBUKA ATAU MENGENBANGKAN FTUR TEKNIS, UNCOMMENT SYNTAK DI FILE WEB.PHP PADA FOLDER ROUTES (CARI ROUTES-ROUTES YANG BERKAITAN DENGAN FITUR TEKNIS), DAN PADA FILE HEADER.BLADE.PHP & FOOTER.BLADE.PHP PADA FOLDER: RESOURCES/VIEWS/LAYOUTS/PARTIALS