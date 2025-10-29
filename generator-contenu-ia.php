<?php
/*
Plugin Name: Générateur de Contenu IA
Description: Un plugin pour générer du contenu utilisant différentes API d'IA comme OpenAI, Gemini et OpenRouter.
Version: 1.0
Author: Adib et Aymen
*/

if (!defined('ABSPATH')) {
    exit; // Empêcher l'accès direct
}

// Inclure les fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/api-handlers.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-templates.php';

// Enregistrer les styles et scripts
add_action('admin_enqueue_scripts', 'gcai_enqueue_scripts');
function gcai_enqueue_scripts() {
    wp_enqueue_style('gcai-admin-style', plugins_url('assets/css/admin-style.css', __FILE__));
    wp_enqueue_script('gcai-admin-script', plugins_url('assets/js/admin-script.js', __FILE__), array('jquery'), '1.0', true);
    
    // Localiser le script pour utiliser ajaxurl
    wp_localize_script('gcai-admin-script', 'gcai_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('gcai-nonce')
    ));
}

// Créer le menu admin
add_action('admin_menu', 'gcai_create_admin_menu');
function gcai_create_admin_menu() {
    add_menu_page(
        'Générateur de Contenu IA',
        'Générateur IA',
        'manage_options',
        'generateur-contenu-ia',
        'gcai_admin_page',
        'dashicons-edit-page',
        30
    );
}

// Fonction pour enregistrer les paramètres
add_action('admin_init', 'gcai_register_settings');
function gcai_register_settings() {
    register_setting('gcai_settings_group', 'gcai_openai_key');
    register_setting('gcai_settings_group', 'gcai_gemini_key');
    register_setting('gcai_settings_group', 'gcai_openrouter_key');
}

// Shortcode pour afficher le formulaire frontend
add_shortcode('generateur_ia', 'gcai_frontend_shortcode');
function gcai_frontend_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/frontend-form.php';
    return ob_get_clean();
}