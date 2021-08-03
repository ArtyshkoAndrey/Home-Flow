![image](https://i.ibb.co/99QZ663/logo.png)
# HomeFlow

This project is intended for personal use, and does not compete with existing analogues.
This is a web-based system for the Hub, and is designed to manage ready-made smart home modules.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install.

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

## Usage

```bash
# Install library
composer install
npm install
npm run dev

# Optional
php artisan key:generate
php artisan migrate

# Run MQTT listener
php artisan modules:run
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
