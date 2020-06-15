# Inpsyde

![image](https://drive.google.com/uc?export=view&id=1n1yLUMIKBJDZ7n5sN5wzbbOim2w3-9WF)

![Build Status](https://badgen.net/badge/build/passing/green)
![Build Status](https://badgen.net/badge/version/1.0/blue)
![License](https://badgen.net/badge/license/MIT/blue)

[![Email](https://badgen.net/badge/email/info@joao-vieira.pt/black)](mailto:info@joao-vieira.pt)
![Skype](https://badgen.net/badge/skype/jcv.pt/black)

## Introduction

This project was developed as part of Inpsyde recruitment process.
The Wordpress plugin enables a frontend async user list, while consuming an external API (https://jsonplaceholder.typicode.com).


## Requirements

- PHP 7.0 and later.
- PHPUnit

## Dependencies

- Httpful (https://github.com/nategood/httpful)
-- (Required to ease json API interation)  

## Installation

Instalation requires Git & Composer.

As requested, the plugin requires no aditional process besides git cloning and composer update, its also assumed that composer is properly configured into wordpress as described on email received:

> "At Inpsyde, we use Composer to manage the whole website code. We use it to install WordPress itself, alongside all plugins and themes, and we load Composer autoload in wp-config.php. We will appreciate it if the delivered plugin will be compliant with this workflow."

While on wordpress directory:

```sh
$ cd wp-content/plugins
$ git clone https://github.com/jcv-pt/inpsyde.git
$ cd inpsyde
$ composer install
```

## Activation

Activation is done under the wordpress plugin manager, simply access the url (.../wp-admin/plugins.php) and activate the "Inpsyde" plugin

## Configuration

The plugin is configurable under the wordpress settings manager, in order to change / view the configuration explore the wordpress main menu via Settings > Inpsyde or access the url (../wp-admin/options-general.php?page=inpsyde-plugin)
For convinience, plugin settings are automatic installed on plugin activation.

### Settings

The settings page allows the plugin to configure the following options:

![image](https://drive.google.com/uc?export=view&id=17eNJauWufah0o1ScsLtnBzlnl-c23lLD)

| Setting | Description |
| ------ | ------ |
| Endpoint Url | The endpoint or url to make available to the frontend where the user listing will be available |
| API Endpoint Url | The endpoint or url of the API to be consumed in order to display the user information |
| Cache Enable | Enabling cache on the plugin, this will make the plugin use wordpress builtin cache to store api query results |
| Cache Expires | The expiration time of the cache |

# Development

## About
The plugin architechture tends to replicate some of the concepts established by the MVC model, separating logic of "controller","model" and "view" within distinct folders. The main reason for developing using this folder structure is to allow to quickly identification of the components and their location. 

### WP Engine

In order to apply the principle described above, ive programmed a "lib" class that ive named of "WpEngine".
This lib is extended by the files found under "src > Core", aka "pages", which in turn is responsible for handling and loading all the dependencies and requirements of a specific "page".
The WpEngine class also aims to facilitate development and code readability, by providing methods & classes for the "pages", such as the "Path" class that provides easy file path using a slug or the "Form" class that provides methods to create a form, and its respective fields by recuring to templating, value loading and providing a easy way to add fields to the form.

#### Bootstrap
The "Bootstrap" class is mainly responsible to load & initialize the plugin "pages". This class is also where the plugin uninstallation, activate & deactivate methods are declared and registered with wordpress.
In this class, its usualy defined / registered the frontend and backend javascript & css files & libs required to the plugin pages. The address of the bootstrap class will always be passed to the child pages, making the registered libs available to be loaded as needed.

#### Page
As mentioned before, the "Page" class is extended by the files in src > Core on which the logic, triggers or actions of a specific "page", "feature" or "section" of the plugin is declared.

#### Templates
The aim of "Template" class is to provide a simply way to render html file templates, from a full page template to a input element, this class is used to make the plugin architecture simple by placing all the html required to the plugin in a "templates" directory and loading the contents in the "page" class as needed. 

#### Cache
As requested the plugins contains a cache mechanism, which uses the Cache class withing the WpEngine lib, its mainly a wrap around the native wordpress cache. Caching is present in the user listing and details in the frontend and cache enabling and expire time can be controlled in the settings page as described above.

#### Notes 
At the time of this project, the WpEngine class was developed with minimal options and features, but could be a good library to have its own repository and be improved further, should it prove itself usefull, in order to ease plugin development.

### Core

The directory src > Core contains the plugin main files. Each class presented here deals with logic for a specific "page" or "section" of the plugin, this way, we can easily identify that all the code required for the Settings page for instance, is contained in Settings.php file.
This folder also contains the plugin bootstrap class, which is responsible for loading all other pages, this is named "Plugin.php", and its the plugin's entry point if you like.

### Design / Responsive
Responsive design is achieved by the use of Twitter Bootrstrap for the frontend.

### Translation
Plugin was developed using as much of translations (i18n) functions as possible.

## Structure

```bash
├── bin
│	├── install-wp-tests.sh
├── src
│   ├── Assets
│   │   ├── css
│   │   │	└── pages
│   │   │		└── endpoint.css
│   │   ├── js
│   │   │	└── pages
│   │   │		└── endpoint.js
│   │   └── libs
│   │		└── loader
│   │   		└── loader.css
│   ├── Core
│	│	├── Api
│	│	│	└── Users.php
│	│	├── Api.php
│	│	├── Endpoint.php
│	│	├── Plugin.php
│	│	└── Settings.php
│   ├── Lib
│	│	└── WpEngine
│	│		├── Form
│	│		│	├── text.php
│	│		│	└── select.php
│	│		├── Bootstrap.php
│	│		├── Cache.php
│	│		├── Form.php
│	│		├── Page.php
│	│		├── Path.php
│	│		└── Template.php
│   └── Templates
│		├── Endpoint
│		│	└── table.php
│		├── Layout
│		│	└── default.php
│		└── Settings
│			└── form.php
├── tests
│	└── src
│		└── Lib
│			└── WpEngine
│				├── test-cache.php
│				├── test-path.php
│				└── test-template.php
├── .phpcs.xmldist
├── .travis.yml
├── composer.json
├── inpsyde.php
├── phpunit.xml.dist
├── README.md
└── Licence
```

| Path | Description |
| ------ | ------ |
| src / Assets | Location for all the CSS & JSS files, this includes page specific files and other 3rd party libraries required to the pages |
| src / Core | Location for the plugin logic, this is where the bootstrap class of the plugin is found (responsible for loading all the pages logic) and other pages classes|
| src / Lib | Location of plugin specific libraries |
| src / Templates | Location of the php files responsible for render html content |

# Tests
Unit test were made available:

![image](https://drive.google.com/uc?export=view&id=12H-0g21PpbUzZn-eaZj-XFeQ8xHcsNpw)

Simply run phpunit inside the plugin dir.

```sh
$ cd wpcontent/plugins/inpsyde
$ phpunit
```

# Checklist

As per request on the checklist provided in the email:
> For your convenience find here a checklist that might help you to make sure you’re ready to submit your code for review

Please see below:

| ID | Item | Status |
| ------ | ------ | ------ |
| 1 | The plugin has all required features, and you’ve tested them in a browser using a real WP installation the custom endpoint is available | OK |
| 2 | A table with users’ details is visible when visiting the endpoint | OK |
| 3 | Clicking a user name/username/id in the table loads that user details via AJAX and print them in the page | OK |
| 4 | Unit tests are available and it is possible to run them and all pass with no errors | OK |
| 5 | PHPCS checks pass with no errors/warnings (you can exclude tests files from check if you wish) | MISSING |
| 6 | the README is complete with: writing in Markdown format, list of requirements, installation and usage instructions, explanations behind non-obvious implementation choices | OK |
| 7 | Your composer.json is valid and complete, and running composer install makes the plugin ready to be used. | OK |
| 8 | The work has a license. | OK |
| 9 | There are no /** TODO **/ leftover | OK |
| 10 | The latest version of all the code has been committed to GitHub in a private repository | OK |
| 11 | The user jobs@inpsyde.com has read access to the repository | OK |

## Notes

Unfortunently point #5 was not met, the PHPCS check and code compliance reference was only provided in the checklist requirements, by the time ive realize this, my plugin was already fully codded, ive still tried to evalute the code but the time required to emend the scripts to be fully compliant was fairly long. So hopefuly the evaluator considers the amount of work and effort on developing this plugin and appeal to his / hers best compreension whist hoping that this exercise / test is enought to demonstrate my coding and problem solving skills as i believe that programming acordingly to PHPCS or any other design pattern rules and specs can be easily achieved.

# Abouts
As final consideration please note that this plugin development and architecture was designed in mind of recreating the essence of the MVC model, however, i do realise that some of the wordpress plugins follow their own wordpress plugin architecture and that PHPCS has strict code standards to make sure plugins follow its rules, so in future i have absolutely no problem of developing a plugin that follows those patterns.

Should the evaluator have any questions about this plugin or any information contained in this document please feel free to contact me via email to info@joao-vieira.pt or via skype JCV.pt. Thank you.

# License
MIT
