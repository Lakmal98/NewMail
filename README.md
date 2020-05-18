# **NewMail - Gmail To SMS gateway**

#### Installation
```
composer require google/apiclient:^2.0
```

#### Debugging Dependencies

If you are using PHP 7.2 or higher the parameter for `count()` cannot be `NULL` in `"SERVER_PATH\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php"` on line 66 or 67.

If following warning encountered, replace the suitable line with given code.

```Warning: count(): Parameter must be an array or an object that implements Countable in C:\xampp\htdocs\google\vendor\guzzlehttp\guzzle\src\Handler\CurlFactory.php on line 67```

Replace this 👇
```
if (($this->handles ? count($this->handles) : 0) >= $this->maxHandles) {
```