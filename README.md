# Laravel 10 Webhook Notifier Application

This Laravel 10 application allows individuals to create a business by providing a name and a `hook_url`. Upon creation, they receive a response containing their business information and a generated `api_key` for subsequent requests.

## Features

- **Business Creation**: Create a business by passing a name and a `hook_url`.
- **Joke Delivery**: Schedule a joke to be sent to the registered `hook_url` with a 10-second delay.
- **Retrieve Jokes**: Retrieve all jokes received by a business.

## Endpoints

### Create Business

**Route**: `/business`

**Method**: `POST`

**Request Body**:
```json
{
    "name": "Awesome Business",
    "hook_url": "http://localhost/client2/public/api/webhook-receiving-url"
}
```

**Response**:
```json
{
    "success": true,
    "message": "Business Created",
    "data": {
        "id": "9c7f6504-3bee-4331-a8e1-b1221b846f0b",
        "business": "Awesome Business",
        "hook_url": "http://localhost/client2/public/api/webhook-receiving-url",
        "api_key": "Lbpax73GUPST1SjQfVJxPJjqBPD0hgEY"
    }
}
```

### Send Joke

**Route**: `/send-joke`

**Method**: `POST`

**Response**:
```json
{
    "success": true,
    "message": 200,
    "data": "Joke delivery scheduled in 10 seconds"
}
```

### Retrieve Jokes

**Route**: `/jokes`

**Method**: `GET`

**Response**:
```json
[
    {
        "id": 1,
        "joke": "Why don't scientists trust atoms? Because they make up everything!",
        "business_id": "9c7f6504-3bee-4331-a8e1-b1221b846f0b"
    },
    ...
]
```
## Testing Endpoints

You can test the endpoints using Postman or any other API testing tool.

## Relationships

- **Business**: A business has many jokes.
- **Joke**: A joke belongs to a business.

## Job Configuration

- The job for sending jokes runs with Redis as the queue connection.
- A 10-second delay is implemented before the job is dispatched.
- The job is configured to retry 3 times in case of failure.
- `laravel-webhook-server` provides configurations for the number of tries and the duration between tries.

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/your-repo/your-project.git
    ```
2. Navigate to the project directory:
    ```sh
    cd your-project
    ```
3. Install dependencies:
    ```sh
    composer install
    ```
4. Set up your `.env` file.
5. Run migrations:
    ```sh
    php artisan migrate
    ```
6. Start the Redis server and activate the queue worker:
    ```sh
    redis-server
    php artisan queue:work
    ```
7. Start the development server:
    ```sh
    php artisan serve
    ```

This project is licensed under the MIT License.
