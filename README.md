# Student Registration Plugin
This Moodle Plugin has been created to realize the Student registration plugin. It contains 5 seperate subprocesses.

## Development Environment 

1. Install [PhpStorm](https://www.jetbrains.com/phpstorm/download/#section=windows)
1. Access to the Moodle instance for development (https://studentregistration.moodle-dhbw.de/)
1. Access to the Adminer instance for DB Management (https://adminer.studentregistration.moodle-dhbw.de)
1. Additional documentation on the Wiki Page (https://wiki.moodle-dhbw.de/Student_Registration/Specification)

## Subproccesses
* Dashboard to Display Statistics
* Initial Demand Reporting
* Record Planned Lecture Hours
* Course Creation
* Student Creation

## How to deploy?

* Create a ZIP archive of the `/source` folder and name it according to your app.

* Login in to the Moodle Instance , navigate to the Management of Moodle and select the Option to install a new plugin.

* Upload your ZIP archive and click the button to proceed. You do not need to edit any other fields in this interface. 

* When asked if you want to update the Moodle database, do so. 

* Go the main page of Moodle, select a Course and click "Enable Editing" in the options on the upper right. by clicking the option of "Add a resource ...", you should see a list of available plugins including your new module.

*Good luck, you will need it...*
