Get Cities
========

This is a simple PHP script to retrieve book data from **IBGE** website.
This script relies on PHP DOM classes and was only tested in UNIX environment with  **PHP >= 5.3**

There are three classes:

* DomFinder - This is an abstraction on PHP DOM class to make it easier to navigate in the document using XPATH
* City - City data and info parsing methods.
* CityRetriever - Methods for search and retrieve some especific city data.

Licensing
=========

This code is licensed under GPL (GNU Public License) V3.
http://www.gnu.org/copyleft/gpl.html

