=== Skriptx Content Generator ===
Contributors: skriptx
Tags: ai, content generator, blog automation, article generator
Requires at least: 6.2
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 3.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically generate and publish AI-powered content at scheduled intervals directly from your WordPress dashboard.

== Description ==

Skriptx Content Generator is a WordPress plugin that helps website owners automate content creation using artificial intelligence.

Create content generation schedules, define prompts, and let the plugin generate articles automatically at specified intervals. Whether you run a blog, news website, niche content site, affiliate website, or content marketing campaign, Skriptx Content Generator can help streamline your publishing workflow.

This plugin requires the Skriptx Content Generation Service to generate AI-powered content and images. Before any information is transmitted to the service, the website administrator must explicitly provide consent and activate the service.

= Key Features =

* Generate AI-powered content automatically.
* Schedule content generation at custom intervals.
* Create and manage multiple AI prompts.
* Publish articles directly to WordPress.
* Automatically assign categories using AI.
* Generate SEO-friendly titles and content.
* Generate AI-powered images (when enabled).
* Track content generation status and history.
* Dashboard analytics and monitoring.
* Secure authenticated API communication.
* WordPress Cron integration.
* Clean and user-friendly administration interface.

= Typical Use Cases =

* Blog automation
* News and media websites
* Niche content websites
* Affiliate marketing websites
* Business blogs
* Educational content publishing
* Content marketing campaigns

= How It Works =

1. Install and activate the plugin.
2. Open Skriptx Content Generator from the WordPress admin menu.
3. Read and accept the consent screen.
4. Generate a unique secret key for your website.
5. Create one or more AI prompts.
6. Configure publishing schedules.
7. The plugin automatically generates and publishes content according to your configured schedules.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/skriptx-content-generator/` directory, or install the plugin through the WordPress Plugins screen.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Navigate to **Skriptx Content Generator** in the WordPress admin area.
4. Read and accept the consent screen.
5. Generate your website's secret key.
6. Create your prompts.
7. Configure schedules and begin generating AI-powered content.

== Frequently Asked Questions ==

= Does the plugin generate content automatically? =

Yes. Once a schedule is created and activated, the plugin automatically generates and publishes content according to the configured schedule.

= Can I create multiple schedules? =

Yes. Multiple schedules can be created and managed independently.

= How are categories assigned to generated articles? =

Categories are automatically determined by the AI based on the generated content. Administrators may manually change the assigned categories after publication if desired.

= Does the plugin use WordPress Cron? =

Yes. The plugin uses WordPress Cron to schedule and process automated content generation.

= Are generated articles automatically published? =

Yes. Generated content is automatically published according to the schedules configured by the administrator.

= Is an internet connection required? =

Yes. This plugin requires access to the Skriptx Content Generation Service to generate AI-powered content and images.

= Does this plugin work without the Skriptx Content Generation Service? =

No. The plugin depends on the Skriptx Content Generation Service for AI-powered content and image generation.

= Is there a usage limit? =

Yes. New accounts currently receive a limited number of complimentary AI content generation credits. Once those credits are exhausted, additional credits may be required to continue using the Skriptx Content Generation Service.

== External Services ==

This plugin requires the **Skriptx Content Generation Service** to function.

The service is hosted at:

https://congen.skriptx.com

The plugin communicates with this service only after the website administrator explicitly provides consent and activates the service.

The service is used for:

* Registering and authenticating your website.
* Generating AI-powered article content.
* Generating AI-powered images (when enabled).
* Submitting content generation jobs.
* Monitoring submitted job status.
* Returning generated content and images.
* Retrieving available account credits.
* Disconnecting the website from the service when requested.

Depending on the operation being performed, the plugin may securely transmit:

* Website URL (Domain Name)
* Administrator Email Address
* AI prompts created by the administrator
* Content generation requests
* Image generation requests
* Generated job identifiers
* Authentication metadata
* Plugin version (when required for compatibility)

Information is transmitted only in the following situations:

* When the administrator explicitly generates and activates a secret key.
* When a manual or scheduled content generation request is submitted.
* When AI image generation is requested.
* When checking the status of submitted generation jobs.
* When retrieving available account credits.
* When disconnecting the website from the Skriptx Content Generation Service.

No website information is transmitted until the administrator explicitly provides consent.

Privacy Policy:
https://congen.skriptx.com/privacy

Terms of Service:
https://congen.skriptx.com/terms

Disclaimer:
https://congen.skriptx.com/disclaimer

Support:
https://support.skriptx.com

== Changelog ==

= 3.0.0 =

* Added administrator consent before connecting to the Skriptx Content Generation Service.
* Added comprehensive External Services documentation.
* Added explicit consent workflow before transmitting website information.
* Improved privacy and transparency.
* Improved API authentication.
* Improved security.
* Various bug fixes and performance improvements.

== Upgrade Notice ==

= 3.0.0 =

Introduces an explicit administrator consent workflow before connecting to the Skriptx Content Generation Service and improves transparency regarding external services and transmitted data.
