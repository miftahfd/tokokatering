## Restfulapi Mantools

Restful API which will consume by Mantools Mobile. The API documentation access in http://10.83.253.175/redoc-static.html

### Installation

Let's get started

1. Clone the repository
    ```
    git clone https://gitlab.telkomsat.net/mantools/mantools-mobile-api.git
    ```

2. Install dependencies
    ```
    cd folder-project
    composer install
    ```

3. Copy .env.example to .env and set DB configuration
    ```
    cp .env.example .env
    ```

4. Generate key application
    ```
    php artisan key:generate
    ```

5. Run web server
    ```
    php artisan serve
    ```