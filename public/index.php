<?php
session_start();

require_once '../vendor/autoload.php';
require_once '../utils/helpers.php';
require_once '../app/core/Router.php';
require_once '../app/core/Database.php';

$router = new Router();

// ===========================================
// ROUTES PUBLIQUES (pas de middleware)
// ===========================================

// Authentification
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');

// Mot de passe oublié
$router->get('/forgot-password', 'AuthController@forgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');

// API pour les unités de mesure
$router->post('/api/unites-mesure', 'ApiController@creerUniteMesure');

// API pour les tables
$router->get('/api/tables/prochains-numeros', 'ApiController@prochainsNumerosTables');

// ===========================================
// ROUTES PROTÉGÉES (avec middleware d'auth)
// ===========================================

// Dashboard Analytique (page d'accueil par défaut)
$router->get('/', 'DashboardController@index', 'AuthMiddleware');
$router->get('/dashboard', 'DashboardController@index', 'AuthMiddleware');

// Ventes & Zones
$router->get('/zones', 'ZoneController@index', 'AuthMiddleware');
$router->get('/ventes', 'VenteController@index', 'AuthMiddleware');
$router->get('/vente/pos', 'VenteController@pos', 'AuthMiddleware');
$router->get('/vente/facture/{id}', 'VenteController@facture', 'AuthMiddleware');
$router->get('/vente/print_facture/{id}', 'VenteController@print_facture', 'AuthMiddleware');
$router->get('/vente/show/{id}', 'VenteController@show', 'AuthMiddleware');
$router->get('/vente/edit/{id}', 'VenteController@edit', 'AuthMiddleware');
$router->post('/vente/edit/{id}', 'VenteController@update', 'AuthMiddleware');
$router->get('/ventes/export/excel', 'VenteController@exportExcel', 'AuthMiddleware');
$router->get('/vente/delete/{id}', 'VenteController@delete', 'AuthMiddleware');
$router->get('/tables', 'VenteController@tables', 'AuthMiddleware');

// ===========================================
// ROUTES DE L'API PROTÉGÉES
// ===========================================
$router->get('/api/zones', 'ApiController@getZones', 'AuthMiddleware');
$router->get('/api/articles', 'ApiController@getArticles', 'AuthMiddleware');
$router->get('/api/articles/prix', 'ApiController@getArticlePriceForZone', 'AuthMiddleware');
$router->get('/api/tables', 'ApiController@getTables', 'AuthMiddleware');
$router->post('/api/ventes', 'ApiController@createVente', 'AuthMiddleware');
$router->get('/api/ventes/facture/{id}', 'ApiController@getFacture', 'AuthMiddleware');
$router->post('/api/fonctions', 'FonctionController@store', 'AuthMiddleware');



// Gestion des articles
$router->get('/articles', 'ArticleController@index', 'AuthMiddleware');
$router->get('/articles/create', 'ArticleController@create', 'AuthMiddleware');
$router->post('/articles/create', 'ArticleController@create', 'AuthMiddleware');
$router->get('/articles/edit/{id}', 'ArticleController@edit', 'AuthMiddleware');
$router->post('/articles/edit/{id}', 'ArticleController@edit', 'AuthMiddleware');
$router->get('/articles/delete/{id}', 'ArticleController@delete', 'AuthMiddleware');
$router->get('/articles/export/excel', 'ArticleController@exportExcel', 'AuthMiddleware');

// Gestion des unités de mesure
$router->get('/gestion-unites', 'UniteMesureController@index', 'AuthMiddleware');
$router->post('/gestion-unites/create', 'UniteMesureController@create', 'AuthMiddleware');
$router->post('/gestion-unites/update/{id}', 'UniteMesureController@update', 'AuthMiddleware');
$router->get('/gestion-unites/delete/{id}', 'UniteMesureController@delete', 'AuthMiddleware');

// Gestion des approvisionnements
$router->get('/approvisionnements', 'ApprovisionnementController@index', 'AuthMiddleware');
$router->get('/approvisionnements/create', 'ApprovisionnementController@create', 'AuthMiddleware');
$router->post('/approvisionnements/create', 'ApprovisionnementController@create', 'AuthMiddleware');
$router->get('/approvisionnement/{id}', 'ApprovisionnementController@show', 'AuthMiddleware');
$router->get('/approvisionnement/edit/{id}', 'ApprovisionnementController@edit', 'AuthMiddleware');
$router->post('/approvisionnement/edit/{id}', 'ApprovisionnementController@update', 'AuthMiddleware');
$router->get('/approvisionnement/delete/{id}', 'ApprovisionnementController@delete', 'AuthMiddleware');

// Gestion des tables
$router->get('/gestion-tables', 'TableController@index', 'AuthMiddleware');
$router->post('/gestion-tables/create', 'TableController@createTable', 'AuthMiddleware');
$router->post('/gestion-tables/batch-create', 'TableController@createTableBatch', 'AuthMiddleware');
$router->post('/gestion-tables/edit/{id}', 'TableController@updateTable', 'AuthMiddleware');
$router->post('/gestion-tables/update-statut/{id}', 'TableController@updateStatut', 'AuthMiddleware');
$router->get('/gestion-tables/delete/{id}', 'TableController@delete', 'AuthMiddleware');

// Gestion des zones (via l'interface des tables)
$router->post('/zones/create', 'TableController@createZone', 'AuthMiddleware');
$router->post('/zones/edit/{id}', 'TableController@updateZone', 'AuthMiddleware');

// Post-paiement
$router->get('/post-paiement', 'VenteController@post_paiement', 'AuthMiddleware');
$router->get('/manage-table/{id}', 'VenteController@manage_table', 'AuthMiddleware');
$router->post('/add-order/{id}', 'VenteController@add_order', 'AuthMiddleware');
$router->post('/initiate-post-payment', 'VenteController@initiate_post_payment', 'AuthMiddleware');
$router->get('/remove-ligne/{id}', 'VenteController@remove_ligne', 'AuthMiddleware');
$router->post('/close-vente/{id}', 'VenteController@close_vente', 'AuthMiddleware');

// Gestion des Agents
$router->get('/users', 'UserController@index', 'AuthMiddleware');
$router->get('/users/create', 'UserController@create', 'AuthMiddleware');
$router->post('/users/store', 'UserController@store', 'AuthMiddleware');
$router->get('/users/edit/{id}', 'UserController@edit', 'AuthMiddleware');
$router->post('/users/update/{id}', 'UserController@update', 'AuthMiddleware');
$router->get('/users/delete/{id}', 'UserController@delete', 'AuthMiddleware');

// Gestion des Équipements
$router->get('/equipements', 'EquipementController@index', 'AuthMiddleware');
$router->get('/equipements/create', 'EquipementController@create', 'AuthMiddleware');
$router->post('/equipements/store', 'EquipementController@store', 'AuthMiddleware');
$router->get('/equipements/edit/{id}', 'EquipementController@edit', 'AuthMiddleware');
$router->post('/equipements/update/{id}', 'EquipementController@update', 'AuthMiddleware');
$router->get('/equipements/delete/{id}', 'EquipementController@delete', 'AuthMiddleware');
$router->get('/equipements/etat-lieu', 'EquipementController@etatLieu', 'AuthMiddleware');
$router->post('/equipements/etat-lieu', 'EquipementController@updateEtatLieu', 'AuthMiddleware');
$router->get('/equipements/historique', 'EquipementController@historique', 'AuthMiddleware');
$router->get('/equipements/historique/{id}', 'EquipementController@showHistorique', 'AuthMiddleware');
$router->get('/equipements/rapport', 'EquipementController@rapport', 'AuthMiddleware');

// Gestion des Inventaires
$router->get('/inventaires', 'InventaireController@index', 'AuthMiddleware');
$router->get('/inventaires/create', 'InventaireController@create', 'AuthMiddleware');
$router->post('/inventaires/store', 'InventaireController@store', 'AuthMiddleware');
$router->get('/inventaires/show/{id}', 'InventaireController@show', 'AuthMiddleware');
$router->get('/inventaires/export/excel/{id}', 'InventaireController@exportExcel', 'AuthMiddleware');

// Reporting
$router->get('/rapports/ventes', 'RapportController@ventes', 'AuthMiddleware');
$router->get('/rapports/ventes/export', 'RapportController@exportVentes', 'AuthMiddleware');
$router->get('/rapports/articles', 'RapportController@articles', 'AuthMiddleware');

// Aide
$router->get('/aide/manuel', 'AideController@manuel', 'AuthMiddleware');

// Déconnexion
$router->get('/logout', 'AuthController@logout', 'AuthMiddleware');

// Dispatcher la requête

$router->dispatch();