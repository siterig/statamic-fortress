# Fortress for Statamic

A comprehensive security addon for Statamic that provides Web Application Firewall (WAF), brute force protection, country blocking, vulnerability scanning, and audit logging capabilities.

## Features

- **Web Application Firewall (WAF)**
  - Protection against SQL injection
  - XSS attack prevention
  - Path traversal protection
  - Command injection protection
  - Customizable rules and whitelist

- **Brute Force Protection**
  - Configurable attempt limits
  - Automatic IP blocking
  - Customizable lockout duration
  - Attempt tracking and logging

- **Country Blocking**
  - Block access by country
  - GeoIP-based detection using MaxMind's GeoLite2 database
  - Automatic database updates
  - Whitelist support
  - Easy country code management

- **Vulnerability Scanner**
  - Package vulnerability detection
  - Regular automated scanning
  - Severity-based reporting
  - Detailed vulnerability information

- **Audit Logging**
  - User activity tracking
  - Login/logout monitoring
  - IP address tracking
  - Configurable retention period

## Installation

1. Install the addon via Composer:
```bash
composer require siterig/fortress
```

2. Publish the configuration:
```bash
php artisan vendor:publish --tag=fortress-config
```

3. Run the migrations:
```bash
php artisan migrate
```

4. Add the following to your `config/logging.php` channels array:
```php
'fortress' => [
    'driver' => 'daily',
    'path' => storage_path('logs/fortress.log'),
    'level' => 'debug',
    'days' => 30,
],
```

5. Set up GeoIP (Required for Country Blocking):
   - Create a free MaxMind account at https://www.maxmind.com/en/geolite2/signup
   - Get your license key from your MaxMind account
   - Add the license key to your `.env` file:
   ```
   MAXMIND_LICENSE_KEY=your_license_key_here
   ```
   - Download the initial database:
   ```
   php artisan fortress:update-geoip
   ```

## Configuration

The addon can be configured through the `config/fortress.php` file. Here are the main configuration options:

### WAF Configuration
```php
'waf' => [
    'enabled' => true,
    'whitelist' => [
        // Add IP addresses to whitelist
    ],
],
```

### Brute Force Protection
```php
'brute_force' => [
    'enabled' => true,
    'max_attempts' => 5,
    'decay_minutes' => 30,
    'lockout_minutes' => 60,
],
```

### Country Blocking
```php
'country_blocking' => [
    'enabled' => false,
    'blocked_countries' => [
        // Add country codes to block (e.g., 'RU', 'CN')
    ],
],
```

### GeoIP Configuration
```php
'geoip' => [
    'license_key' => env('MAXMIND_LICENSE_KEY'),
    'database_path' => storage_path('app/geoip/GeoLite2-Country.mmdb'),
    'update_frequency' => 'weekly',
],
```

## Dashboard Widgets

The addon provides three dashboard widgets:

1. **Security Overview**
   - Vulnerability statistics
   - Blocked attempts counter
   - Active threats monitor
   - Last scan timestamp

2. **Recent Security Incidents**
   - Latest attack attempts
   - Blocked IP addresses
   - Attack type and details
   - Timestamp information

3. **Package Vulnerabilities**
   - Current vulnerabilities
   - Severity levels
   - Package information
   - Update recommendations

## Usage

### Middleware

Add the following middleware to your routes:

```php
Route::middleware(['fortress.waf', 'fortress.brute-force', 'fortress.country-block'])->group(function () {
    // Your protected routes
});
```

### Logging

The addon automatically logs security events. You can access the logs through:

```php
Log::channel('fortress')->info('Your message');
```

### GeoIP Database Updates

The GeoLite2 database is updated weekly by MaxMind. You can update it manually using:

```bash
php artisan fortress:update-geoip
```

For automatic updates, add this to your scheduler in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('fortress:update-geoip')->weekly();
}
```

## Security

- All security features are enabled by default
- IP whitelisting is available for trusted sources
- Regular vulnerability scanning helps maintain security
- Comprehensive audit logging for security monitoring
- GeoIP database is kept up to date for accurate country blocking

## Support

For support, please open an issue on the GitHub repository or contact the maintainers.

## License

This addon is open-sourced software licensed under the MIT license.

### GeoLite2 Database License

The GeoLite2 database is provided by MaxMind under a Creative Commons Attribution-ShareAlike 4.0 International License. You can find the license details at: https://creativecommons.org/licenses/by-sa/4.0/
