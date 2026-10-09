<?php
// Cookie de session : inaccessible au JavaScript, non envoyé par les requêtes tierces
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

// Définir le chemin de base du projet
define('BASE_PATH', __DIR__ . '/../');

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'utils/helpers.php';
require_once BASE_PATH . 'app/core/Router.php';
require_once BASE_PATH . 'app/core/Database.php';

$router = new Router();

// ===========================================
// ROUTES PUBLIQUES (pas de middleware)
// ===========================================

// Authentification
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');

// Mot de passe oublié
$router->get('/forgot-password', 'AuthController@forgotPassword');


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
$router->get('/vente/edit/{id}', 'VenteController@edit', 'AdminMiddleware');
$router->post('/vente/edit/{id}', 'VenteController@update', 'AdminMiddleware');
$router->get('/ventes/export/excel', 'VenteController@exportExcel', 'AuthMiddleware');
$router->post('/vente/delete/{id}', 'VenteController@delete', 'AdminMiddleware');
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
$router->post('/api/fonctions', 'FonctionController@store', 'AdminMiddleware');
$router->post('/api/unites-mesure', 'ApiController@creerUniteMesure', 'AdminMiddleware');
$router->get('/api/tables/prochains-numeros', 'ApiController@prochainsNumerosTables', 'AuthMiddleware');



// Gestion des articles
$router->get('/articles', 'ArticleController@index', 'AuthMiddleware');
$router->get('/articles/create', 'ArticleController@create', 'AdminMiddleware');
$router->post('/articles/create', 'ArticleController@create', 'AdminMiddleware');
$router->get('/articles/edit/{id}', 'ArticleController@edit', 'AdminMiddleware');
$router->post('/articles/edit/{id}', 'ArticleController@edit', 'AdminMiddleware');
$router->post('/articles/delete/{id}', 'ArticleController@delete', 'AdminMiddleware');
$router->get('/articles/export/excel', 'ArticleController@exportExcel', 'AuthMiddleware');

// Gestion des unités de mesure
$router->get('/gestion-unites', 'UniteMesureController@index', 'AuthMiddleware');
$router->post('/gestion-unites/create', 'UniteMesureController@create', 'AdminMiddleware');
$router->post('/gestion-unites/update/{id}', 'UniteMesureController@update', 'AdminMiddleware');
$router->post('/gestion-unites/delete/{id}', 'UniteMesureController@delete', 'AdminMiddleware');

// Gestion des approvisionnements
$router->get('/approvisionnements', 'ApprovisionnementController@index', 'AuthMiddleware');
$router->get('/approvisionnements/create', 'ApprovisionnementController@create', 'AuthMiddleware');
$router->post('/approvisionnements/create', 'ApprovisionnementController@create', 'AuthMiddleware');
$router->get('/approvisionnement/{id}', 'ApprovisionnementController@show', 'AuthMiddleware');
$router->get('/approvisionnement/edit/{id}', 'ApprovisionnementController@edit', 'AdminMiddleware');
$router->post('/approvisionnement/edit/{id}', 'ApprovisionnementController@update', 'AdminMiddleware');
$router->post('/approvisionnement/delete/{id}', 'ApprovisionnementController@delete', 'AdminMiddleware');

// Gestion des tables
$router->get('/gestion-tables', 'TableController@index', 'AuthMiddleware');
$router->post('/gestion-tables/create', 'TableController@createTable', 'AdminMiddleware');
$router->post('/gestion-tables/batch-create', 'TableController@createTableBatch', 'AdminMiddleware');
$router->post('/gestion-tables/edit/{id}', 'TableController@updateTable', 'AdminMiddleware');
$router->post('/gestion-tables/update-statut/{id}', 'TableController@updateStatut', 'AuthMiddleware');
$router->post('/gestion-tables/delete/{id}', 'TableController@delete', 'AdminMiddleware');

// Gestion des zones (via l'interface des tables)
$router->post('/zones/create', 'TableController@createZone', 'AdminMiddleware');
$router->post('/zones/edit/{id}', 'TableController@updateZone', 'AdminMiddleware');

// Post-paiement
$router->get('/post-paiement', 'VenteController@post_paiement', 'AuthMiddleware');
$router->get('/manage-table/{id}', 'VenteController@manage_table', 'AuthMiddleware');
$router->post('/add-order/{id}', 'VenteController@add_order', 'AuthMiddleware');
$router->post('/initiate-post-payment', 'VenteController@initiate_post_payment', 'AuthMiddleware');
$router->post('/remove-ligne/{id}', 'VenteController@remove_ligne', 'AuthMiddleware');
$router->post('/close-vente/{id}', 'VenteController@close_vente', 'AuthMiddleware');

// Gestion des Agents
$router->get('/users', 'UserController@index', 'AdminMiddleware');
$router->get('/users/create', 'UserController@create', 'AdminMiddleware');
$router->post('/users/store', 'UserController@store', 'AdminMiddleware');
$router->get('/users/edit/{id}', 'UserController@edit', 'AdminMiddleware');
$router->post('/users/update/{id}', 'UserController@update', 'AdminMiddleware');
$router->post('/users/delete/{id}', 'UserController@delete', 'AdminMiddleware');

// Gestion des Équipements
$router->get('/equipements', 'EquipementController@index', 'AuthMiddleware');
$router->get('/equipements/create', 'EquipementController@create', 'AdminMiddleware');
$router->post('/equipements/store', 'EquipementController@store', 'AdminMiddleware');
$router->get('/equipements/edit/{id}', 'EquipementController@edit', 'AdminMiddleware');
$router->post('/equipements/update/{id}', 'EquipementController@update', 'AdminMiddleware');
$router->post('/equipements/delete/{id}', 'EquipementController@delete', 'AdminMiddleware');
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