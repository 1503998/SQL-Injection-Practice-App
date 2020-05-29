Deutsche Anleitung unten!

Web Cookbook Version 0.9.9

The intention was to program a management system for my own created/modified recipes. Everyone in the internet can see and print them, but in distinction from cooking communities like Recipezaar or Chefkoch only the manager(s) of the site can add and modify recipes. To interact with users, I implemented 'my little forum' in an iframe and there is also a function to assess the recipes. To avoid programming an own user management, the userdata table of 'my little forum' is used.

Copyright (C) 2012  Joachim Gabel

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

===========================================================================
The roadmap:
1. Input and edit module for text entries like standard meals, beverages, decription of ingredients and special productions
2. Debugging of the statistic functions
3. A function to compute calories of a recipe from its ingredients
4. A function to compute the amount of ingredients with different servings

===========================================================================
Current implemented:
1. Menu driven display of recipes and text entries
2. Direct input of recipes via mask
3. Editing and deleting of recipes (Editing is now possible) and text entries is only possible with direct access to the database
4. Manage recipe sections
5. Sort recipes into additional sections
6. Switchable for small screen devices like netbooks. (The term mobile refers only to the display, that is not an application which you can install on an USB Stick!)
7. Menu customized.
8. Debugging the admin scripts.
9. Statistics function protected against tampering. (Temporarily set aside by unknown errors (It works on my local computer, on my provider's space there are non verifiable errors)
10. Import of recipe files (my own format, have a look on format.txt)
11. Import of recipe files (Mealmaster)
12. Import of recipe files (Rezkonv, the German standard format)
13. Export of recipes to the formats above.
14. Editing recipes
15. Export of single recipes
16. Debugging of several errors.
17. Usage of the userdata table of 'my little forum' to identify users
18. Comment function for recipes
19. Admin functions are now secured via user management
20. Debugging of several errors in admin functions.
21. Rating of recipes
22. Deleting recipes
23. Help system with showtext.php

===========================================================================
Requirements:
Apache Webserver 2.2.x or newer
MySQL 5.x or newer
MySQLClient 5.x or newer
PHP 5.x or newer

Recommended:
phpMyAdmin 3.4.x or newer
my little forum (https://sourceforge.net/projects/mylittleforum/) [If you don't want to use it, no user management will be used in the future.]
shadowbox-js media viewer (http://www.shadowbox-js.com ... Free for single and non-commercial use. I'm still on the search for an open source alternative.) [If you don't want to use it, only single images instead of slideshows in recipes and text entries are allowed; the display of images refers to a new page instead of showing it in the shadowbox.]

===========================================================================

Installation:
Extract the archive to a directory. On your local machine copy the whole directory structure as a subdirectory to your web server's htdocs directory. For your internet presence use a ftp program to do the same. If you want to use 'my little forum' (http://mylittleforum.net/), install it into the /forum directory. If you want to use the shadowbox-js media viewer (http://www.shadowbox-js.com/), install it in the /shadowbox subdirectory. In MySQL create a database. Run the install/en_tables.sql or de_tables.sql. If you speak any other language than English or German, translate the entries below INSERT INTO dat_category to your needs. Edit the env_db.php and replace the database access entries to your needs. Edit and/or uncomment your language in the language.php. Now you can call the application in your web browser with 'http://yourdomain.tld/yourdirectory/'. To insert single recipes, call 'http://yourdomain.tld/yourdirectory/admin/' and choose 'Create recipes' (or whatever in your own language).

Securing your /admin subdirectory with a .htpass/.htaccess combination is not longer necessary!

But the safest option is still this way:
Install the application completely locally on your computer (with web server environment, XAMPP (http://www.apachefriends.org) on Windows, Linux users know themselves better ways). Then install the application on the web space of your ISP and immediately delete the /admin subdirectory. Run the data maintenance on your local machine and then move the table contents to the database at your provider via phpMyAdmin. For better understanding where and what data is stored in, check the datamodel.jpg or datamodel.svg. To avoid overwriting statistics of the recipes, the content of the table sys_zaehler should only be maintained on the database of your provider (This is why the data is outsourced from das_rezept, the relational data model otherwise should fit in the table das_rezept).

To implement the help system, create entries in the dat_category table above id 10,000 and below id 11,000, then create entries with the same id in the dat_texte table. You'll see the entries by clicking the Help menu. You can find German texts in install/de_help.sql. Translate them for your needs or write your own texts.


=============================================================================
Web Cookbook Version 0.9.9

Die Idee hinter der Anwendung war, eine Darstellungsmöglichkeit für meine selbst entwickelten/modifizierten Rezepte zu haben. Jeder im Internet kann meine Rezepte sehen und drucken aber im Gegensatz zu Kochcommunities wie Recipezaar oder Chefkoch kann nur der/die Administrator(en) der Seite Rezepte einstellen und ändern. Zur Interaktion mit den Usern habe ich 'my little forum' in einem Iframe eingebaut. Außerdem können User Rezepte bewerten und kommentieren. Um kein extra Usermanagement zu schreiben, werden die Funktionen von 'my little forum' benutzt.

Copyright (C) 2012  Joachim Gabel

Dieses Programm ist freie Software. Sie können es unter den Bedingungen der GNU General Public License, wie von der Free Software Foundation veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung dieses Programms erfolgt in der Hoffnung, dass es Ihnen von Nutzen sein wird, aber OHNE IRGENDEINE GARANTIE, sogar ohne die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FÜR EINEN BESTIMMTEN ZWECK. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit diesem Programm erhalten haben. Falls nicht, siehe <http://www.gnu.org/licenses/>.

===========================================================================
Die Roadmap:
1. Eingabe- und Editmodul für Texteinträge wie Menüs, Getränke, Beschreibung von Zutaten und Herstellungsanleitungen
2. Fehlerbeseitigung bei der Statistik
3. Eine Funktion, die den Brennwert eines Rezepts aus dessen Zutaten errechnet
4. Eine Funktion, die die Zutatenmenge eines Rezepts anhand veränderbaren Portionsmengen umrechnet

===========================================================================
Zur Zeit eingebaut:
1. Ein Menü anstatt einer Baumstruktur zur Navigation
2. Eingabe von Rezepten über eine Maske
3. Ändern und Löschen von Rezepten (Ändern ist nun möglich) und Texteinträgen ist nur über direkten Zugriff auf die Datenbank möglich
4. Verwalten von Rezept Kategorien
5. Einordnen von Rezepten in zusätzliche Kategorien
6. Umschaltmöglichkeit für Geräte mit kleinem Bildschirm, wie Netbooks. (Die Bezeichnung Mobile bezieht sich nur auf die Darstellung, das ist keine Anwendung, die man z.B. auf einem USB Stick installieren kann!)
7. Menü angepasst.
8. Fehlerbeseitigung in den Admin Scripts.
9. Statistikfunktion vor Manipulation geschützt (Temporär ausgeschaltet bis ich den Fehler bei meinem Provider gefunden habe ... bei mir lokal gibts komischerweise keine Fehlermeldung)
10. Import von Rezeptdateien (mein eigenes Format, siehe format.txt)
11. Import von Rezeptdateien (Mealmaster)
12. Import von Rezeptdateien (Rezkonv)
13. Export von Rezepten in die obigen Formate
14. Rezepte bearbeiten
15. Export von einzelnen Rezepten
16. Fehlerbeseitigung verschiedener Bugs.
17. Benutzung der userdata Tabelle von 'my little forum' zur Identifikation von Nutzern
18. Kommentarfunktion für Rezepte
19. Anwahl der Admin-Funktionen wird nun über Usermanagement gelöst
20. Fehlerbeseitigung in den Admin Funktionen
21. Bewertungsfunktion für Rezepte
22. Löschen von Rezepten
23. Hilfesystem mit der showtext.php

===========================================================================
Anforderungen:
Apache Webserver 2.2.x oder neuer
MySQL 5.x oder neuer
MySQLClient 5.x oder neuer
PHP 5.x oder neuer

Empfohlen:
phpMyAdmin 3.4.x oder neuer
my little forum (https://sourceforge.net/projects/mylittleforum/) [Falls nicht gewünscht, wird es auch kein künftiges Usermanagement geben.]
shadowbox-js Media Viewer (http://www.shadowbox-js.com ... Frei für nicht-kommerzielle Benutzung. Ich bin nach wie vor auf der Suche nach einer Open Source Alternative.) [Falls nicht gewünscht, sind Slideshows bei den Rezepten nicht möglich. Außerdem wird das Bild beim Anklicken nicht in der Shadowbox angezeigt sondern in einem neuen Fenster.]

===========================================================================

Installation:
Extrahiere das Archiv in ein Verzeichnis. Kopiere auf deinem lokalen Rechner die komplette Verzeichnisstruktur in ein Unterverzeichnis vom htdocs deines Webservers. Mit einem FTP Programm kannst du den Inhalt auf den Webspace bei deinem Provider schicken. Falls gewünscht, installiere 'my little forum' in das forum Unterverzeichnis. Falls gewünscht, installiere den shadowbox-js Media Viewer in das shadowbox Unterverzeichnis. Erzeuge in MySQL eine leere Datenbank. Führe die de_tables.sql innerhalb der Datenbank aus. Ändere die Datenbankzugriff Einträge in der env_db.php. Du kannst jetzt über 'http://yourdomain.tld/yourdirectory/' auf die Anwendung zugreifen. Die Admin Funktionen wie Rezepteingabe, Kategoriemanagement etc. findest du unter 'http://yourdomain.tld/yourdirectory/admin/'.

Die Sicherung des admin Unterverzeichnis mit einer .htpass/.htaccess Kombination ist nicht mehr nötig!

Aber die sicherste Variante ist immer noch diese:
Installiere die Anwendung komplett lokal auf deinem Rechner (Natürlich mit Webserver Umgebung etc. z.B. mit XAMPP [http://www.apachefriends.org] unter Windows, Linux User wissen sich da sicher besser zu helfen). Danach installierst du die Anwendung auf dem Webspace deines Providers und löschst sofort wieder das admin Unterverzeichnis. Die Datenpflege betreibst du dann auf deinem lokalen Rechner und schiebst per phpMyAdmin nur noch die Tabelleninhalte auf die Datenbank bei deinem Provider. Zum besseren Verständnis, wo welche Daten liegen kannst du in der datamodel.jpg oder datamodel.svg nachsehen. Um die Rezeptstatistik nicht ständig zu überschreiben, sollte der Inhalt der sys_zaehler Tabelle nur auf der Datenbank beim Provider gepflegt werden (Darum ist die ja auch ausgelagert, vom relationalen Datenmodell hätte die auch in die das_rezept Tabelle rein gepasst).

Um das Hilfesystem zu generieren, kannst du die install/de_help.sql importieren und nach Wunsch anpassen oder du generierst Einträge in die dat_category Tabelle oberhalb der Ids 10.000 bis 10.999 und die zugehörigen Texteinträge in der dat_texte Tabelle mit der selben Id.

