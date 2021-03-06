# Moodle Plugin Template

[![License](https://img.shields.io/badge/License-GPL--3.0-blue.svg)](https://www.gnu.org/licenses/gpl-3.0.en.html)

<!-- TOC -->

- [Moodle Plugin Template](#moodle-plugin-template)
		- [IF IN DOUBT, READ THE DOCS!](#if-in-doubt-read-the-docs)
	- [Autofix of all linter Errors](#autofix-of-all-linter-errors)
			- [!UPDATE!](#update)
			- [!UPDATE!](#update-1)
	- [Moodle Instances](#moodle-instances)
	- [Official Moodle Docs With Additional Comments](#official-moodle-docs-with-additional-comments)
	- [Local testing](#local-testing)

<!-- /TOC -->

### IF IN DOUBT, READ THE DOCS!

## Autofix of all linter Errors

#### !UPDATE!  
You no longer need to install and run composer, as the necessary libs are now included in the `/source/lib/` folder.  
You can still install composer, if you like.
#### !UPDATE!


We are using `composer` for dependency paket management. This tool allows to automatically install necessary PHP pakets. It is vital that you follow the installation steps below to get `composer` up and running.


1. Install [PHP](https://secure.php.net/manual/de/install.windows.manual.php) 
Install the Non-Thread-Safe version for your system (most likely x64).
1. Install [Composer](https://getcomposer.org/doc/00-intro.md)  
If your system provides a version of composer (like most Linux ditributions), then you can use that version. If you have to install it manually, install it globally to use the shortcut `composer` in your shell. If you install it locally, you need to specify the path to the composer file, as mentioned in the installation docs for composer.
1. `$ composer install --no-dev`
1. `$ composer autofix`

## Moodle Instances

Currently, there is only one generell Moodle instance for this project:
[https://moodle.ganymed.me](https://moodle.ganymed.me)

I am currently working on providing both processes with their own development instance of Moodle, each with their own database to prevent any complications. 
When these instances are available, I will link to them here as well as inform everybody through Slack. Keep in mind that each new instance does not contain data that you entered in the database on the generell instance.

## Official Moodle Docs With Additional Comments

The following steps should get you up and running with this module template code.

For the sake of this tutorial, it is assumed that you have a shell (or cmd on Windows) in the directory of this cloned repository. In all following command lines, it is assumed that you are not in any subdirectory. If `/` is used leading a path, it is assumed that this means the directory of this cloned repository and not your systems root directory. 

Code lines beginning with `$` are commands to bre run in a shell like bash. If you do not have a bash shell installed on your system, and also do not have the git bash installed, you need to perform some tasks manually.

* DO NOT PANIC!

* Clone the repository and read this file

* Install composer as illustrated [here.](https://getcomposer.org/doc/00-intro.md)

* The file `version.php` **does not need to be edited**.  
  If a module with the same version number is installed, Moodle deletes the old version and installs the newly uploaded module.  
  **This prevents databse errors and is the preferred way for development.**

* Pick a name for your module (e.g. "mysupercoolmodname").
  The module name MUST be lower case and can't contain underscores. You should check the [CVS contrib](http://cvs.moodle.org/contrib/plugins/mod/) to make sure that your name is not already used by an other module. Registering the plugin name @ [http://moodle.org/plugins](http://moodle.org/plugins) will secure it for you.

  * Keep in mind Moodle does not like numbers or special characters like `.` or `,` in names or paths. Name your plugin accordingly.

* Edit all the files in this directory and its subdirectories and change
  all the instances of the string "apsechseins" to your module name
  (eg "mysupercoolmodname"). If you are using Linux, you can use the following command:  
  `$ find . -type f -exec sed -i 's/apsechseins/mysupercoolmodname/g' {} \;`  
  `$ find . -type f -exec sed -i 's/APSECHSEINS/MYSUPERCOOLMODNAME/g' {} \;`  

  On a mac, use:  
  `$ find . -type f -exec sed -i '' 's/apsechseins/mysupercoolmodname/g' {} \;`  
  `$ find . -type f -exec sed -i '' 's/APSECHSEINS/MYSUPERCOOLMODNAME/g' {} \;`  

  On a Windows system, you can use the following PowerShell commands. Use the command `cd` to change into the directory of your code.  
  `PS> $files = Get-ChildItem . -recurse -include *.* ; foreach ($file in $files) { (Get-Content $file.PSPath) | ForEach-Object { $_ -replace "apsechseins", "mysupercoolmodname" } | Set-Content $file.PSPath }`  
  `PS> $files = Get-ChildItem . -recurse -include *.* ; foreach ($file in $files) { (Get-Content $file.PSPath) | ForEach-Object { $_ -replace "APSECHSEINS", "MYSUPERCOOLMODNAME" } | Set-Content $file.PSPath }`  

  Replace "mysupercoolmodname" in the commands above with your module name.

* Rename the file `/source/lang/en/apsechseins.php` to lang/en/mysupercoolmodname.php
  where "mysupercoolmodname" is the name of your module

* Rename all files in `/source/backup/moodle2/` folder by replacing "apsechseins" with
  the name of your module

  On Linux (and Mac) you can perform this and previous steps by calling:  
  `$ find . -depth -name '*apsechseins*' -execdir bash -c 'mv -i "$1" "${1//apsechseins/mysupercoolmodname}"' bash {} \;`

  On a Windows system, you can use the following command to perfrom this and the previous step:  
  `PS> $files = Get-ChildItem . -recurse -include *.* | Where-Object {$_.Name -like "*apsechseins*"}; foreach ($file in $files) { $newname = ([String]$file).Replace("apsechseins", "mysupercoolmodname"); Rename-Item -Path $file $newname }`

* Implement new functionality in `new_resource_view.php` and `locallib.php`. In `locallib.php`, most functions are not specific and just require input as a parameter to work with any given process definition.

  * You need the process definition key of your process. This can either be done with the API by using [Swaggerhub](https://app.swaggerhub.com/apis/sWIm/sWIm_activi/v0.2.0#/Process%20Definitions/getProcessDefinitions), or with the key that you specified yourself while uploading the plugin to Activiti (in this case, the field you have to fill out is called "Model key" in the menu "Edit model details". You can edit this field at any point.).

* If you encounter any problems while uploading, delete the whole folder `/source/vendor/trahloff/activiti/.git`. Deleting this folder is generally no bad idea.

* Create a ZIP archive of the `/source` folder and name it according to your app (in this tutorial "mysupercoolmodname").

* Login in to [our Moodle instance](https://moodle.ganymed.me), navigate to the Management of Moodle and select the Option to install a new plugin.

* Upload your ZIP archive and click the button to proceed. You do not need to edit any other fields in this interface. 

* When asked if you want to update the Moodle database, do so. This is required for your plugin to be recognized by Moodle. The site might freeze for a short moment. DO NOT PANIC. This is normal.

  * If you get a timeout message, then your ZIP Archive is too big. Please run `composer install --no-dev` again, this time with the flag at the end to prevent all unnecessary libs from installing. This should slim down your ZIP archive (that you have to recreate, of course) down a bit, and processing should no longer take longer than 30 seconds.

* Go the main page of Moodle, select the "Test Course" and click "Enable Editing" in the options on the upper right. by clicking the option of "Add a resource ...", you should see a list of available plugins including your new module.

  * If you don't see your module, DO NOT PANIC! Moodle uses a Unix-daemon called `cron` that periodically runs specific tasks. In this case, specific commands are run every 5 minutes. Wait 5 minutes before trying anything else, and refresh the site.

  * If this does not help, then enter the Management section again, manually uninstall your plugin (accepting to update the Moodle database on the way), and upload your module again. Chances are somebody else just uploaded their plugin and there was a race for the database update that you lost.

* Select your module, name it in a way that others may understand what you are trying to test, and click on your new module instance.

* Have fun playing with your Moodle plugin!

* You may now proceed to run your own code in an attempt to develop
  your module. You will probably want to modify mod_form.php and new_resource_view.php
  as a first step. Check db/access.php to add capabilities.
  Go to Settings > Site Administration > Development > XMLDB editor
  and modify the module's tables.

We encourage you to share your code and experience - visit [http://moodle.org](http://moodle.org)

Good luck, you will need it...

## Local testing

You can test the PHP code you have written locally on your system against the Activiti API. My attempts for this can be found in the file `bla.php` in the root directory of this repo. Keep in mind that you do not have access to any Moodle API while testing on your local system.
