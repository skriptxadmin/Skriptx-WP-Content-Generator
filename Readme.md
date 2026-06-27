# Skriptx Content Generator

🚀 **Download the Plugin:** https://congen.skriptx.com

Automatically generate and publish AI-powered content at scheduled intervals directly from your WordPress dashboard.

---

## Plugin Information

| Property          | Value                                                     |
| ----------------- | --------------------------------------------------------- |
| Contributors      | skriptx                                                   |
| Tags              | ai, content generator, blog automation, article generator |
| Requires at least | 6.2                                                       |
| Tested up to      | 7.0                                                       |
| Requires PHP      | 8.1                                                       |
| Version           | **3.0.0**                                                 |
| License           | GPLv2 or later                                            |
| License URI       | https://www.gnu.org/licenses/gpl-2.0.html                 |

---

## Description

Skriptx Content Generator is a WordPress plugin that helps website owners automate content creation using artificial intelligence.

Create content generation schedules, define prompts, and let the plugin generate articles automatically at specified intervals.

Whether you run a blog, news website, niche content site, affiliate website, or content marketing campaign, Skriptx Content Generator can help streamline your publishing workflow.

The plugin uses the **Skriptx Content Generation Service** to generate AI-powered content and images. Before any information is transmitted to the service, the website administrator is presented with a consent screen and must explicitly approve the connection.

---

## Key Features

* Generate AI-powered content automatically.
* Schedule content generation at custom intervals.
* Create and manage multiple AI prompts.
* Publish articles directly to WordPress.
* Automatically assign categories.
* Generate SEO-friendly titles and article content.
* AI image generation support.
* Dashboard analytics and monitoring.
* Secure authenticated API communication.
* WordPress Cron integration.
* Clean and user-friendly administration interface.

---

## Typical Use Cases

* Blog automation
* News and media websites
* Niche content websites
* Affiliate marketing websites
* Business blogs
* Educational websites
* Content marketing campaigns

---

## How It Works

1. Install and activate the plugin.
2. Provide consent to connect your website to the Skriptx Content Generation Service.
3. Generate a unique secret key for your website.
4. Create one or more AI prompts.
5. Configure publishing options and schedules.
6. The plugin automatically submits content generation jobs.
7. Generated content is returned to your website and published according to your configured schedule.

---

## Installation

1. Download the plugin from https://congen.skriptx.com.
2. Upload the plugin to `/wp-content/plugins/skriptx-content-generator/` or install it through the WordPress Plugins screen.
3. Activate the plugin.
4. Open **Skriptx Content Generator** from the WordPress admin menu.
5. Read and accept the consent screen.
6. Generate your website's secret key.
7. Create your prompts and schedules.

---

# External Services

This plugin requires the **Skriptx Content Generation Service** to function.

The service is hosted at:

https://congen.skriptx.com

The plugin communicates with this service only after the website administrator explicitly provides consent and activates the service.

### Purpose of the service

The Skriptx Content Generation Service is used to:

* Register and authenticate your website.
* Generate AI-powered article content.
* Generate AI-powered images (when enabled).
* Submit content generation jobs.
* Monitor job progress.
* Retrieve generated content.
* Retrieve generated images.
* Retrieve available account credits.
* Deactivate the website connection when requested.

### Information transmitted

Depending on the operation being performed, the plugin may securely transmit:

* Website URL (Domain Name)
* Administrator Email Address
* AI prompts created by the administrator
* Content generation requests
* Image generation requests
* Generated job identifiers
* Authentication metadata
* Plugin version (when required for compatibility)

### When information is transmitted

Information is transmitted only in the following situations:

* When the administrator explicitly generates and activates a secret key.
* When content generation is manually or automatically requested.
* When AI image generation is requested.
* When checking the status of submitted generation jobs.
* When retrieving available account credits.
* When the administrator disconnects the website from the service.

**No website information is transmitted until the administrator explicitly provides consent.**

### Privacy Policy

https://congen.skriptx.com/privacy

### Terms of Service

https://congen.skriptx.com/terms

### Disclaimer

https://congen.skriptx.com/disclaimer

### Support

https://support.skriptx.com

---

## Frequently Asked Questions

### Does the plugin generate content automatically?

Yes. Once a schedule is configured and activated, the plugin can automatically generate and publish content using WordPress Cron.

### Can I create multiple schedules?

Yes. Multiple schedules can be managed independently.

### Can generated articles be assigned to categories?

Yes. Categories are automatically determined by the AI based on the generated content and assigned during publication.

### Does the plugin require an internet connection?

Yes. The plugin communicates with the Skriptx Content Generation Service to generate content and images.

### Are generated articles automatically published?

Yes. Generated content is automatically published according to the schedules and publishing options configured by the administrator.

### Can I review generated content before it is published?

No. The plugin is designed to automatically publish AI-generated content according to the configured schedules. Administrators should carefully design their prompts and scheduling before enabling automatic publishing.

### Does the plugin work without the external service?

No. The plugin requires the Skriptx Content Generation Service to generate AI-powered content and images.

---


---

## Changelog

## 3.0.0

* Added administrator consent workflow before any external communication.
* Added transparent disclosure of external services.
* Secret key generation now requires explicit administrator consent.
* Improved security and privacy compliance.
* Improved API authentication.
* Performance improvements and bug fixes.

---

## Upgrade Notice

### 3.0.0

This release introduces an explicit administrator consent workflow before connecting to the Skriptx Content Generation Service, along with improved transparency regarding external services and data transmission.

---

## Privacy

The plugin does not communicate with external services until the website administrator explicitly grants consent.

Administrators are responsible for ensuring that the use of AI-generated content complies with applicable laws, regulations, copyright requirements, and their website's privacy obligations.

---

## Disclaimer

AI-generated content should always be reviewed before publication.

Website owners remain solely responsible for all published content, including compliance with applicable laws, copyright, privacy regulations, search engine guidelines, and platform policies.

---

## Website

https://congen.skriptx.com
