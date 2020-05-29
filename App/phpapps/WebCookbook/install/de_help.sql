-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 21. Jul 2012 um 14:47
-- Server Version: 5.1.63
-- PHP-Version: 5.3.6-13ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `rezepte`
--

--
-- Daten für Tabelle `dat_texte`
--

INSERT INTO `dat_texte` (`id`, `chapter`, `text`, `image`, `position`) VALUES
(10001, NULL, '[H]Bewertung und Kommentierung[/H]von Rezepten ist nur im Forum angemeldeten Benutzern erlaubt.\r\n\r\nUm ein Rezept zu bewerten, klickst du auf die Scheiben und kommst nun auf die reine Darstellung der Scheiben. Hier kannst du solange irgendwelche Werte von 1 bis 10 anwählen, bis du mit dem Ergebnis zufrieden bist. Mit Speichern setzt du deine Bewertung des Rezepts fest und landest wieder auf dem Rezept. Danach ist es nicht mehr möglich, das Rezept ein weiteres Mal zu bewerten. Bei der Wahl von Abbrechen wird dein Wert verworfen, auch hier landest du wieder auf der Rezeptanzeige.\r\n\r\nDie Bewertungsfunktion funktioniert folgendermaßen: Solange ein Rezept nicht bewertet wurde, steht es auf Rang Null. Bei der ersten Bewertung werden als Sicherheitsmechanismus 20 sonst nicht angezeigte Bewertungen mit fünf vorangestellt. Dadurch wird verhindert, dass das Rezept von einem Einzelnen in den Keller bzw. in den Himmel gewertet werden kann. Nur wenn auf Dauer viele Benutzer ein Rezept bewertet haben, kann ein Rezept so dauerhaft besonders schlecht bzw. besonders gut bewertet werden.\r\n\r\nDie Kommentierung funktioniert wie bei einem Forumseintrag. Beiträge können zur Zeit nicht selbst geändert werden, das ist einer späteren Version vorbehalten. Solltest du einen Eintrag korrigieren wollen, bitte den geänderten Text an mich schicken (siehe Impressum), ich tausch das dann aus.\r\n\r\nIch bitte bei den Einträgen, sich an die Netiquette zu halten, ansonsten fliegen Beleidigungen, rassistische, sexistische und sonstwie sittenwidrige Bemerkungen gnadenlos raus. Ich bin auch nicht an Canadian Online Pharmacies und ähnlichem Spam weder in den Foren noch in den Rezeptkommentaren interessiert.', NULL, NULL),
(10002, NULL, '[H]Einleitung[/H]Die Idee hinter der Anwendung war, eine Darstellungsmöglichkeit für meine selbst entwickelten/modifizierten Rezepte zu haben. Jeder im Internet kann meine Rezepte sehen und drucken aber im Gegensatz zu Kochcommunities wie Recipezaar oder Chefkoch kann nur der/die Administrator(en) der Seite Rezepte einstellen und ändern.\r\n\r\nJetzt kann man natürlich sagen, es gibt doch schon etliche Anwendungen für solche Zwecke. Leider sind die meisten davon Desktop-Programme und was sich wirklich fürs Internet eignet, besitzt weder Import- noch Exportfunktionen. Leider bieten auch die verworrenen und nicht dokumentierten Tabelleninhalte kaum eine Möglichkeit, diese Beschränkung zu umgehen. Es ist auch nicht jeder der Programmierer vor dem Herrn, um eben mal so locker Datenbankinhalte in Mealmaster oder Rezkonv Dateien zu speichern.\r\n\r\nZur Interaktion mit den Usern habe ich ''my little forum'' in einem Iframe eingebaut. Außerdem können User Rezepte bewerten und kommentieren. Um kein extra Usermanagement zu schreiben, werden die Funktionen von ''my little forum'' benutzt.', NULL, NULL),
(10003, NULL, '[H]Die Menüpunkte[/H][UL][LI][B]Rezepte[/B]\r\nZeigt die einzelnen Rezeptrubriken wie Vorspeisen, Suppen, Dessert als Liste an. Beim Klick auf ein Rezept erscheint das vollständige Rezept. Eine Besonderheit sind regionale und internationale Rezepte. Hier erscheinen Rezepte für deutsche Regionen/Länder bzw. Länder/Regionen (aka Arabische Halbinsel anstatt Saudi Arabien) auf der ganzen Welt.[/LI][LI][B]Menüs[/B]\r\nZeigt typische aufeinender abgestimmte Speisefolgen an. Außerdem finden sich Sammlungen von Speisen, die alle gleichzeitig auf den Tisch kommen wie bei einer indonesichen Reistafel oder einem schwedischen Smoergasbord.[/LI][LI][B]Getränke[/B]\r\nist eine Mischung aus Getränkebeschreibung wie Wein, Spirituosen und Rezepten wie Cocktailrezepten oder Punsch.[/LI][LI][B]Zutaten[/B]\r\nHier werden spezielle Zutaten wie Dörrfleisch oder Fonds beschrieben.[/LI][LI][B]Herstellung[/B]\r\nHier findet ihr Anleitungen, wie man Tomaten schält, einen Schweinebauch füllt oder für Hobbymetzger, wie man einen Schweineschinken fachmännisch zerlegt.[/LI][LI][B]System[/B]\r\nfasst alle systemrelevanten Funktionen zusammen: Einstellungen für den Rezeptexport und die Anmeldung, das Forum, das Impressum, den Link zum Download der Anwendung, die Umschaltmöglichkeit auf die mobile Version und den Rücksprung auf die Startseite.[/LI][LI][B]Hilfe[/B]\r\nNa ratet mal ;o)[/LI][/UL]', NULL, NULL),
(10004, NULL, '[H]Einstellungen[/H][I][UL][LI][B]Ausgabeformat[/B]\r\nLegt fest, in welchem Format Rezepte mit dem Speichern Knopf exportiert werden. Zur Auwahl stehen das systemeigene Format, Mealmaster und Rezkonv. Standardmäßig ist in der deutschen Version der Anwendung Rezkonv voreingestellt. Mit Speichern wird die Ausgabe für die Dauer der Session auf das von dir gewählte Format umgestellt. Nachdem der Browser geschlossen wurde, ist die Einstellung wieder auf dem Ursprungszustand.[/LI][LI][B]Anmelden[/B]\r\nUm sich hier anmelden zu können, musst du dich zunächst im Forum registrieren, unabhängig davon, ob du das Forum benutzt oder nicht. Danach gibst du hier dieselbe Benutzernamen/Password Kombination wie im Forum ein. Nun stehen dir sowohl Kommentar- wie Bewertungsfunktion in den Rezepten offen.[/LI][/UL]', 'system/settings.jpg', 'r');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
