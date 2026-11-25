# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

A single-page DNS lookup tool in plain PHP (no framework, no Composer, no build step, no tests). The user enters a hostname, clicks a record-type button, and the page shows the result of PHP's `dns_get_record()`. Frontend is Bootstrap 4 + jQuery slim, vendored as minified files under `assets/`.

## Running

The app is served by a local WAMP stack (PHP 8.4 with Xdebug, at `C:/Wamp/bin/php/php8.4.26/php`) through a vhost at `http://dns-lookup.test/`, whose document root is this repo. That URL is hardcoded as `APPURL` in [index.php](index.php) (no trailing slash), and every asset link and the form action are built from it, so the page only renders correctly at that address.

Quick check without a browser: `curl -s "http://dns-lookup.test/?hostname=example.com&type=a"`

To use PHP's built-in server instead, change `APPURL` to match first, e.g. `php -S localhost:8000` with `APPURL = 'http://localhost:8000'`.

Syntax check: `php -l index.php include/app.php include/misc.php`

## Architecture

- [index.php](index.php) is the only entry point. It defines `ABSPATH` and `APPURL`, includes the two files below, then renders the whole page. The DNS lookup itself runs inline in the template: it maps the type to a `DNS_*` constant via `constant('DNS_' . strtoupper($type))`, calls `dns_get_record()`, and flattens each record into `key: value` lines in a `<pre>`.
- [include/app.php](include/app.php) does request handling: it redirects any URL containing `index.php` to `APPURL`, defines `$dns_records` (the list of record types, each rendered as a submit button named `type`), and reads `$hostname` and `$type` from `$_GET`, filtering both against what the PHP build supports.
- [include/misc.php](include/misc.php) holds helpers: `test_input()` (trim, stripslashes, htmlspecialchars) and `test_print()` (an `array_walk_recursive` callback that prints string-keyed fields, escaped, and skips integer keys).
- The include files start with `defined('ABSPATH') || exit;`, so they cannot be requested directly. Keep that guard in any new include.

To add a record type, add it to `$dns_records`. It must match a PHP `DNS_*` constant name (lowercase here, uppercased at lookup).

## Known pitfalls

- The Windows PHP build has no `DNS_CAA`, and `dns_get_record()` throws a `ValueError` for any type value it doesn't know (even the raw `8192`). [include/app.php](include/app.php) therefore drops types whose `DNS_*` constant is undefined, so the CAA button only shows on builds that support it (typically Linux).
- `$type` must stay validated against `$dns_records` before it reaches `constant()`; an undefined constant is a fatal `Error` on PHP 8. A missing or unknown type falls back to `a`.
- DNS data is untrusted (TXT records can hold any text). `test_print()` escapes keys and values with `htmlspecialchars`; any new output of record data must do the same.
- Asset paths are appended as `APPURL . '/assets/...'`, so `APPURL` must not end in `/`. Note the form action and brand link use bare `APPURL`.
