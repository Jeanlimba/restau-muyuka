<?php

class ZoneController {
    
    /**
     * Affiche la liste des zones de vente.
     */
    public function index() {
        $zones = (new Zone())->findAll();
        view('zones/index', ['zones' => $zones]);
    }
}
