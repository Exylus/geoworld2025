<?php
/**
 * Ce script est composé de fonctions d'exploitation des données
 * détenues pas le SGBDR MySQL utilisées par la logique de l'application.
 *
 * C'est le seul endroit dans l'application où a lieu la communication entre
 * la logique métier de l'application et les données en base de données, que
 * ce soit en lecture ou en écriture.
 *
 * PHP version 7
 *
 * @category  Database_Access_Function
 * @package   Application
 * @author    SIO-SLAM <sio@ldv-melun.org>
 * @copyright 2019-2021 SIO-SLAM
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link      https://github.com/sio-melun/geoworld
 */

/**
 *  Les fonctions dépendent d'une connection à la base de données,
 *  cette fonction est déportée dans un autre script.
 */
require_once 'connect-db.php';

/**
 * Obtenir la liste de tous les pays référencés d'un continent donné
 *
 * @param string $continent le nom d'un continent
 * 
 * @return array tableau d'objets (des pays)
 */
function getCountriesByContinent($continent)
{
    // pour utiliser la variable globale dans la fonction
    global $pdo;
    $query = 'SELECT * FROM Country WHERE Continent = :cont;';
    $prep = $pdo->prepare($query);
    // on associe ici (bind) le paramètre (:cont) de la req SQL,
    // avec la valeur reçue en paramètre de la fonction ($continent)
    // on prend soin de spécifier le type de la valeur (String) afin
    // de se prémunir d'injections SQL (des filtres seront appliqués)
    $prep->bindValue(':cont', $continent, PDO::PARAM_STR);
    $prep->execute();
    // var_dump($prep);  pour du debug
    // var_dump($continent);

    // on retourne un tableau d'objets (car spécifié dans connect-db.php)
    return $prep->fetchAll();
}

/**
 * Obtenir la liste des pays
 *
 * @return liste d'objets
 */
function getAllCountries()
{
    global $pdo;
    $query = 'SELECT * FROM Country;';
    return $pdo->query($query)->fetchAll();
}

function getCountryInformations($country)
{
    global $pdo;
    $query = 'SELECT Country.*, City.Name as Capitale FROM Country INNER JOIN City ON Country.Capital = City.id WHERE Country.Name = :country';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':country', $country, PDO::PARAM_STR);
    $prep->execute();
    return $prep->fetchAll();
}

function getAllContinents()
{
    global $pdo;
    $query = 'SELECT DISTINCT Continent FROM Country;';
    return $pdo->query($query)->fetchAll();
}

// Add these new functions to manager-db.php

/**
 * Get paginated countries by continent
 * 
 * @param string $continent Continent name
 * @param int $page Page number
 * @param int $perPage Items per page
 * @return array Array of countries
 */
function getPaginatedCountriesByContinent($continent, $page = 1, $perPage = 10)
{
    global $pdo;
    $offset = ($page - 1) * $perPage;
    $query = 'SELECT * FROM Country WHERE Continent = :cont LIMIT :offset, :perPage;';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':cont', $continent, PDO::PARAM_STR);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);
    $prep->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();
}

/**
 * Get all countries with pagination
 * 
 * @param int $page Page number
 * @param int $perPage Items per page
 * @return array Array of countries
 */
function getAllPaginatedCountries($page = 1, $perPage = 10)
{
    global $pdo;
    $offset = ($page - 1) * $perPage;
    $query = 'SELECT * FROM Country LIMIT :offset, :perPage;';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':offset', $offset, PDO::PARAM_INT);
    $prep->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();
}

/**
 * Count total countries
 * 
 * @return int Total count
 */
function countAllCountries()
{
    global $pdo;
    $query = 'SELECT COUNT(*) as total FROM Country;';
    return $pdo->query($query)->fetch()->total;
}

/**
 * Count countries by continent
 * 
 * @param string $continent Continent name
 * @return int Total count
 */
function countCountriesByContinent($continent)
{
    global $pdo;
    $query = 'SELECT COUNT(*) as total FROM Country WHERE Continent = :cont;';
    $prep = $pdo->prepare($query);
    $prep->bindValue(':cont', $continent, PDO::PARAM_STR);
    $prep->execute();
    return $prep->fetch()->total;
}