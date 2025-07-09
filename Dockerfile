FROM php:8.1-cli

# Copy semua file ke container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Jalankan server PHP di port 10000 (harus 10000 untuk Render)
CMD ["php", "-S", "0.0.0.0:10000"]
