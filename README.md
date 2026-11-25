# DNS lookup

A small web page for looking up DNS records. Type a hostname, click a record type, and see what the DNS servers return.

Supported record types: A, AAAA, ANY, CAA, CNAME, MX, NS, PTR, SOA, SRV and TXT.

It is plain PHP with no framework, no Composer packages and no build step. Lookups use PHP's built-in [`dns_get_record()`](https://www.php.net/manual/en/function.dns-get-record.php). The UI uses Bootstrap 4, which is included in `assets/`.

## Requirements

- PHP 7 or newer (developed on PHP 8.4)
- A web server that runs PHP, such as Apache (for example through WAMP, XAMPP or MAMP) or PHP's built-in server

## Setup

1. Clone the repository:

   ```sh
   git clone https://github.com/dananghel89/dns-lookup.git
   ```

2. Point a virtual host at the project folder. The default config expects `http://dns-lookup.test`.

3. If you use a different address, change `APPURL` near the top of `index.php`. Leave off the trailing slash:

   ```php
   define( 'APPURL', 'http://dns-lookup.test' );
   ```

4. Open the address in your browser.

To try it without setting up a virtual host, use PHP's built-in server and set `APPURL` to `http://localhost:8000`:

```sh
php -S localhost:8000
```

## Usage

Enter a hostname such as `example.com` and click a record type. You can also link to a lookup directly:

```
http://dns-lookup.test/?hostname=example.com&type=mx
```

If `type` is missing or not supported, the page looks up the A record.

## Notes

- **CAA on Windows:** PHP for Windows can't look up CAA records, so the CAA button only appears when PHP supports it (usually on Linux and macOS).
- **Where results come from:** lookups use the DNS resolver configured on the server, not the visitor's resolver.
