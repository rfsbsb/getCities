<?php

class DomFinder {
  function __construct($page) {
    $html = @file_get_contents($page);
    $doc = new DOMDocument();
    $this->xpath = null;
    if ($html) {
      $doc->preserveWhiteSpace = true;
      $doc->resolveExternals = true;
      @$doc->loadHTML($html);
      $this->xpath = new DOMXPath($doc);
      $this->xpath->registerNamespace("html", "http://www.w3.org/1999/xhtml");
    }
  }

  function find($criteria = NULL, $getAttr = FALSE) {
    if ($criteria && $this->xpath) {
      $entries = $this->xpath->query($criteria);
      $results = array();
      foreach ($entries as $entry) {
        if (!$getAttr) {
          $results[] = $entry->nodeValue;
        } else {
          $results[] = $entry->getAttribute($getAttr);
        }
      }
      return $results;
    }
    return NULL;
  }

  function count($criteria = NULL) {
    $items = 0;
    if ($criteria && $this->xpath) {
      $entries = $this->xpath->query($criteria);
      foreach ($entries as $entry) {
        $items++;
      }
    }
    return $items;
  }

}


class City {
  protected $dom = null;

  function __construct($dom = NULL) {
    if ($dom) {
      $this->dom = $dom;
    }
  }

  function getName() {
    $name_cell = $this->dom->find("//div[@id='conteudo']/h1");
    $name_parts = explode(" - ", $name_cell[0]);
    return $name_parts[0];
  }

  function getCapital() {
    $capital_cell = $this->dom->find("//ul[@id='info']/li[1]");
    $capital_parts = explode("Capital: ", $capital_cell[0]);
    return $capital_parts[1];
  }

  function getNumberOfCities() {
    $capital_cell = $this->dom->find("//ul[@id='info']/li[2]");
    $capital_parts = explode("Nº de municípios: ", $capital_cell[0]);
    return $capital_parts[1];
  }

  function getCodes() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[@class='codigo']");
  }

  function getNames() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[@class='nome']");
  }

  function getDemonym() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[@class='gentilico']");
  }

  function getPopulation() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[4]");
  }

  function getArea() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[5]");
  }

  function getPopulationDensity() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[6]");
  }

  function getGdp() {
    return $this->dom->find("//table[@id='municipios']/tbody/tr/td[7]");
  }

  function getAll() {
    $name               = $this->getName();
    $capital            = $this->getCapital();
    $number_cities      = $this->getNumberOfCities();
    $codes              = $this->getCodes();
    $names              = $this->getNames();
    $demonym            = $this->getDemonym();
    $population         = $this->getPopulation();
    $area               = $this->getArea();
    $population_density = $this->getPopulationDensity();
    $gdp                = $this->getGdp();
    $all = array(
      'name'          => $name,
      'capital'       => $capital,
      'number_cities' => $number_cities,
      'cities'        => array()
    );
    foreach ($codes as $id => $code) {
      $all['cities'][$code]["name"] = $names[$id];
      $all['cities'][$code]["demonym"] = $demonym[$id];
      $all['cities'][$code]["population"] = $population[$id];
      $all['cities'][$code]["area"] = $area[$id];
      $all['cities'][$code]["population_density"] = $population_density[$id];
      $all['cities'][$code]["gdp"] = $gdp[$id];
    }

    return $all;
  }

}

class CityRetriever {
  protected $base_url = "http://www.ibge.com.br/cidadesat/download/mapa_e_municipios.php?uf=";
  protected $dom = NULL;

  function __construct($state = 'ac') {
    if ($state) {
      $this->state = strtolower($state);
      $this->dom = new DomFinder($this->base_url . $this->state);
    }
  }

  function findAll($state = "ac") {
    $city = new City($this->dom);
    return $city->getAll();
  }

}
