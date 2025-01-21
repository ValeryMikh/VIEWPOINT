# VIEWPOINT (beta version)
ViewQL(View Oriented Query Language)
 
## Developer
Valery Mikhailovski

## System requirements
Minimum hosting requirements: PHP v5 language and a “mySQL” compatible relational database. <br>
The minimum requirement for a computer is to have an APACHE type server, PHP v5 and a “mySQL” compatible database on it. <br>
(Otherwise, you can use, for example, WAMPSERVER or a similar package).

## Installation
The process of deploying the software package is very simple and is carried out by the user himself. <br>
Upload files to your computer or hosting.
Then follow these steps:<br>
1. Create a new database in MyAdmin mySQL or in the C-panel with the name "Database name".
2. Connect the ViewQL language interpreter "VIEWPOINT/ViewQL/library_api/gddl.php" to the database you created. To do this, you need to fill the file "VIEWPOINT/ViewQL/library_api/connect_mysql_server.php" with the variables necessary to connect to the MySQL database you created:
```
var $mySQL= "<Host name>";
var $mySQL_User = "<Admin name>";
var $password = "<Password>";
var $mySQL_Database = "<Database name>".
```
3. Use the utility ”VIEWPOINT/REPOSITORY/repository_creation.php” to create the VIEWPOINT database structure. It will ask you to create an administrator password.

4. IDE ADMIN - an integrated environment for developing, modifying and testing databases is ready for use. Start IDE ADMIN - "VIEWPOINT/admin/index.php".

5. BROKER - Module Management System does not require installation.

## Usage
import:
```
VIEWPOINT/
    ADMIN/
    BROKER/
    ViewQL/
    IMAGES/
    REPOSITORY/
    INSTALLATION.md
    README.md
    LICENSE.rtf
```

## License [GPL](https://www.gnu.org/licenses/gpl-3.0.ru.html)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge , publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
