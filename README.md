# Forminator Email Domain Blocker

A powerful WordPress plugin that blocks specific email domains from submitting Forminator forms. Manage a blacklist of unwanted email domains directly from your WordPress admin panel with ease and flexibility.

## Overview

Forminator Email Domain Blocker is a lightweight, efficient plugin designed to enhance security and control over your Forminator forms by preventing submissions from specific email domains. Whether you want to block spam, test domains, or unwanted external addresses, this plugin provides a simple yet effective solution.

---

## Features

✨ **Key Features:**

- **Easy Domain Management** – Add or remove blocked email domains from a user-friendly admin interface
- **Multiple Domain Support** – Block unlimited email domains with a simple list format (one domain per line)
- **Case-Insensitive Matching** – Automatically handles uppercase and lowercase domains
- **Smart Domain Validation** – Built-in validation automatically cleans up pasted domains
  - Removes accidental protocols (http://, https://)
  - Strips path segments if included
  - Validates proper domain format
- **Exact Domain Matching** – Blocks all emails from a domain (e.g., blocking `example.com` blocks `user@example.com`, `admin@example.com`, etc.)
- **Targeted Field Validation** – Focuses on the "email-1" field in Forminator forms
- **Clean Error Messages** – Users receive clear feedback when their email domain is blocked
- **Deduplication** – Automatically removes duplicate entries from your domain list
- **Safe Uninstall** – Cleanly removes all plugin data when uninstalled
- **Lightweight & Fast** – Minimal performance impact on your WordPress site
- **Internationalization Ready** – Fully translatable (Text Domain: `forminator-email-domain-blocker`)

---

## Requirements

### System Requirements

- **WordPress** version 5.0 or higher
- **PHP** version 7.4 or higher
- **Forminator Plugin** (version 1.0 or higher) – Must be installed and activated

### Browser Compatibility

- Modern web browsers (Chrome, Firefox, Safari, Edge) for admin interface
- No JavaScript requirements for form blocking

---

## Installation

### Step 1: Upload the Plugin

1. Download the plugin folder `forminator-email-domain-blocker`
2. Upload it to `/wp-content/plugins/` on your WordPress installation
3. Alternatively, use WordPress admin → **Plugins** → **Add New** → **Upload Plugin**

### Step 2: Activate the Plugin

1. Navigate to **Plugins** in your WordPress admin dashboard
2. Find "Forminator Email Domain Blocker" in the list
3. Click **Activate**

### Step 3: Configure Blocked Domains

1. Go to **Settings** → **Forminator Email Blocker**
2. Enter one domain per line in the textarea (e.g., `example.com`)
3. Click **Save Changes**

---

## Usage

### Adding Blocked Domains

1. Navigate to **Settings** → **Forminator Email Blocker** in your WordPress admin
2. Enter domains you want to block, one per line:
   ```
   example.com
   spam.com
   test.com
   ```
3. The plugin automatically:
   - Converts domains to lowercase
   - Removes duplicate entries
   - Strips protocols and paths if accidentally pasted
   - Validates proper domain format

### Example Scenarios

**Blocking Spam Domains:**

```
gmail.com
yahoo.com
domain-known-for-spam.com
```

**Blocking Test/Development Domains:**

```
test.com
dev.com
localhost.local
```

**Blocking Competitor or Unwanted Domains:**

```
competitor.com
unwanted-domain.com
```

### How It Works

| Aspect            | Detail                                            |
| ----------------- | ------------------------------------------------- |
| **Target Field**  | `email-1` (the default email field in Forminator) |
| **Match Type**    | Exact domain match (case-insensitive)             |
| **Behavior**      | Blocks form submission and shows error message    |
| **Error Message** | "This email domain is not allowed."               |

### Examples

- ✅ Block `example.com` → Blocks `user@example.com`, `admin@example.com`, `test@example.com`
- ✅ Multiple domains → Each domain is evaluated independently
- ✅ Duplicate domains → Automatically removed and deduplicated
- ✅ Domain format variations → Normalized to lowercase

---

## Configuration

### Admin Settings Page

**Location:** Settings → Forminator Email Blocker

**Options:**

- **Domain List** (textarea)
  - Enter one domain per line
  - Domains are automatically validated and cleaned
  - Duplicates are automatically removed
  - Supports newline variations (CR, LF, CRLF)

---

## Frequently Asked Questions (FAQ)

**Q: Will this block subdomain emails too?**

- A: No. The plugin uses exact domain matching. Blocking `example.com` will NOT block `mail.example.com`. You must add each domain separately.

**Q: What happens when a blocked domain tries to submit?**

- A: The form submission is rejected with the error message: "This email domain is not allowed."

**Q: Can I block wildcard domains like `*.example.com`?**

- A: Currently, the plugin uses exact domain matching. Wildcard matching is not supported in this version.

**Q: Does this affect other plugins or email functionality?**

- A: No. The plugin only targets Forminator forms and specifically the "email-1" field. It doesn't affect WordPress email sending or other plugins.

**Q: How do I know if a domain is blocked?**

- A: Visit Settings → Forminator Email Blocker and check your domain list.

**Q: Can I block multiple domains at once?**

- A: Yes. Add as many domains as you need, one per line.

**Q: Will blocking a domain affect existing form submissions?**

- A: No. Blocking only applies to new submissions after the domain is added to the list.

---

## Troubleshooting

### Plugin Not Working?

**Issue:** Domains are not being blocked

- **Solution:** Ensure Forminator plugin is installed and activated
- **Solution:** Verify the target field name is exactly `email-1`
- **Solution:** Clear browser cache and test again

**Issue:** Error message not showing

- **Solution:** Check that domain is correctly formatted in the domain list
- **Solution:** Ensure no duplicate spaces or special characters

**Issue:** Valid domains being rejected

- **Solution:** Check domain format - must contain at least one dot (e.g., `example.com`)
- **Solution:** Verify domain doesn't have accidental spaces or special characters

**Issue:** Unable to add domains

- **Solution:** Verify you have admin capabilities
- **Solution:** Check WordPress file permissions on `/wp-content/` folder

---

## Security Considerations

- **Data Storage:** Blocked domains are stored in WordPress options (serialized)
- **Validation:** All domains are validated before storage
- **Sanitization:** Input is sanitized and normalized
- **Permission Control:** Only users with `manage_options` capability can edit settings
- **Data Cleanup:** Plugin cleanly removes all data on uninstall

---

## API Reference

### Hooks & Filters

**Filter: `forminator_custom_form_submit_errors`**

- Used internally to validate and block domains
- Priority: 10

**Filter: `forminator_custom_form_invalid_form_message`**

- Customizes global error message for blocked domains

**Option Key:** `forminator_blocked_domains`

- Stores the list of blocked domains in WordPress options

---

## Changelog

### Version 1.1.1

- Enhanced domain validation with improved regex patterns
- Added deduplication of domain entries
- Improved sanitization for accidentally pasted URLs
- Better error handling for edge cases
- Increased plugin stability

### Version 1.0.0

- Initial release
- Basic domain blocking functionality
- Admin settings interface
- Forminator form validation

---

## Development & Support

### Developer Information

**Author:** Noushad Nipun

**Links:**

- 🌐 **Website:** [noushadnipun.com](https://noushadnipun.com)
- 💻 **GitHub:** [github.com/noushadnipun](https://github.com/noushadnipun)
- 📧 **Email:** [hello@noushadnipun.com](mailto:hello@noushadnipun.com)

### Contributing

Contributions are welcome! If you'd like to improve this plugin:

1. Fork the repository on [GitHub](https://github.com/noushadnipun)
2. Create a feature branch for your changes
3. Submit a pull request with detailed description
4. Ensure code follows WordPress coding standards

### Reporting Issues

Found a bug or have a feature request? Please:

1. Check the [GitHub Issues](https://github.com/noushadnipun) page
2. Provide detailed information about the issue
3. Include WordPress version, PHP version, and Forminator version
4. Contact [hello@noushadnipun.com](mailto:hello@noushadnipun.com) for support

### Development Notes

- **Code Structure:** Object-oriented with `FEDB_Plugin` class
- **Hooks Used:** `admin_menu`, `admin_init`, `plugins_loaded`, `forminator_custom_form_submit_errors`
- **Text Domain:** `forminator-email-domain-blocker`
- **License:** GPL-2.0-or-later

---

## License

This plugin is licensed under the **GNU General Public License v2.0 or later** (GPL-2.0-or-later).

You are free to use, modify, and distribute this plugin in accordance with the terms of the GPL-2.0-or-later license.

---

## Support & Maintenance

This plugin is actively maintained. For updates, issues, or questions:

- 📧 Email: [hello@noushadnipun.com](mailto:hello@noushadnipun.com)
- 🌐 Website: [noushadnipun.com](https://noushadnipun.com)
- 💬 GitHub: [github.com/noushadnipun](https://github.com/noushadnipun)

---

## Acknowledgements

Special thanks to the WordPress and Forminator communities for their support and inspiration in developing this plugin.

---
