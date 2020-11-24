<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.1
* File                    : includes/translation/class-translation-text-search.php
* File Version            : 1.1.1
* Created / Last Modified : 25 August 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Search translation text PHP class.
*/

    if (!class_exists('DOPBSPTranslationTextSearch')){
        class DOPBSPTranslationTextSearch{
            /*
             * Constructor
             */
            function __construct(){
                /*
                 * Initialize search text.
                 */
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searches'));
                
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesSearch'));
                
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesAddSearch'));
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesEditSearch'));
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesDeleteSearch'));
                
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesHelp'));
                
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesFrontEnd'));
                add_filter('dopbsp_filter_translation_text', array(&$this, 'searchesWidget'));
            }

            /*
             * Search text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searches($text){
                array_push($text, array('key' => 'PARENT_SEARCHES',
                                        'parent' => '',
                                        'text' => 'Search'));
                
                array_push($text, array('key' => 'SEARCHES_TITLE',
                                        'parent' => 'PARENT_SEARCHES',
                                        'text' => 'Search',
                                        'de' => 'Suche', // !
                                        'es' => 'Búsqueda', // !
                                        'fr' => 'Recherche')); //!
                
                array_push($text, array('key' => 'SEARCHES_CREATED_BY',
                                        'parent' => 'PARENT_SEARCHES',
                                        'text' => 'Created by',
                                        'de' => 'Erstellt von', // !
                                        'es' => 'Creada por', // !
                                        'fr' => 'Créé par')); //!
                array_push($text, array('key' => 'SEARCHES_LOAD_SUCCESS',
                                        'parent' => 'PARENT_SEARCHES',
                                        'text' => 'Search list loaded.',
                                        'de' => 'Suchliste geladen.', // !
                                        'es' => 'La lista de búsqueda cargó.', // !
                                        'fr' => 'La liste de recherche a chargé.')); //!
                array_push($text, array('key' => 'SEARCHES_NO_SEARCHES',
                                        'parent' => 'PARENT_SEARCHES',
                                        'text' => 'No searches. Click the above "plus" icon to add a new one.',
                                        'de' => 'Keine Suche. Klicken Sie auf das obige "Plus"-Symbol, um ein neues hinzuzufügen.', // !
                                        'es' => 'No hay búsquedas. Haga clic en el icono "plus" de arriba para agregar uno nuevo.', // !
                                        'fr' => 'Aucune recherche. Cliquez sur l<<single-quote>>icône "plus" ci-dessus pour en ajouter une nouvelle.')); //!
                
                return $text;
            }
            
            /*
             * Search - Search text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesSearch($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_SEARCH',
                                        'parent' => '',
                                        'text' => 'Search'));
                
                array_push($text, array('key' => 'SEARCHES_SEARCH_NAME',
                                        'parent' => 'PARENT_SEARCHES_SEARCH',
                                        'text' => 'Name',
                                        'de' => 'Name', // !
                                        'es' => 'Nombre', // !
                                        'fr' => 'Nom')); //!
                
                array_push($text, array('key' => 'SEARCHES_SEARCH_LOADED',
                                        'parent' => 'PARENT_SEARCHES_SEARCH',
                                        'text' => 'Search loaded.',
                                        'de' => 'Suche geladen.', // !
                                        'es' => 'La búsqueda cargó.', // !
                                        'fr' => 'La recherche a chargé.')); //!
                
                return $text;
            }
            
            /*
             * Search - Add search text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesAddSearch($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_ADD_SEARCH',
                                        'parent' => '',
                                        'text' => 'Search - Add search'));
                
                array_push($text, array('key' => 'SEARCHES_ADD_SEARCH_NAME',
                                        'parent' => 'PARENT_SEARCHES_ADD_SEARCH',
                                        'text' => 'New search',
                                        'de' => 'Neue suche', // !
                                        'es' => 'Nueva búsqueda', // !
                                        'fr' => 'Nouvelle recherche')); //!
                
                array_push($text, array('key' => 'SEARCHES_ADD_SEARCH_SUBMIT',
                                        'parent' => 'PARENT_SEARCHES_ADD_SEARCH',
                                        'text' => 'Add search',
                                        'de' => 'Suche hinzufügen', // !
                                        'es' => 'Añada búsqueda', // !
                                        'fr' => 'Ajoutez la recherche')); //!
                array_push($text, array('key' => 'SEARCHES_ADD_SEARCH_ADDING',
                                        'parent' => 'PARENT_SEARCHES_ADD_SEARCH',
                                        'text' => 'Adding a new search ...',
                                        'de' => 'Hinzufügen einer neuen Suche ...', // !
                                        'es' => 'Añadir una nueva búsqueda ...', // !
                                        'fr' => 'Ajout d<<single-quote>>une nouvelle recherche ...')); //!
                array_push($text, array('key' => 'SEARCHES_ADD_SEARCH_SUCCESS',
                                        'parent' => 'PARENT_SEARCHES_ADD_SEARCH',
                                        'text' => 'You have successfully added a new search.',
                                        'de' => 'Sie haben eine neue Suche erfolgreich hinzugefügt.', // !
                                        'es' => 'Ha añadido con éxito una nueva búsqueda.', // !
                                        'fr' => 'Vous avez réussi à ajouter une nouvelle recherche.')); //!
                
                return $text;
            }
            
            /*
             * Search - Edit search text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesEditSearch($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'parent' => '',
                                        'text' => 'Search - Edit search'));
                
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH',
                                        'parent' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'text' => 'Edit search details',
                                        'de' => 'Such Details bearbeiten', // !
                                        'es' => 'Detalles de búsqueda de revisión', // !
                                        'fr' => 'Détails de recherche d<<single-quote>>édition')); //!
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_SETTINGS',
                                        'parent' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'text' => 'Edit search settings',
                                        'de' => 'Sucheinstellungen bearbeiten', // !
                                        'es' => 'Editar los ajustes de búsqueda', // !
                                        'fr' => 'Modifier les paramètres de recherche')); //!
                
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_DAYS',
                                        'parent' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'text' => 'Exclude calendars from search [hours filters disabled]',
                                        'de' => 'Kalender von der Suche ausschließen [Stundenfilter deaktiviert]', // !
                                        'es' => 'Excluir calendarios de búsqueda [horas filtros desactivados]', // !
                                        'fr' => 'Exclure les calendriers de la recherche [filtres d<<single-quote>>heures désactivés]')); //!
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_HOURS',
                                        'parent' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'text' => 'Exclude calendars from search [hours filters enabled]',
                                        'de' => 'Kalender von der Suche ausschließen [Stundenfilter aktiviert]', // !
                                        'es' => 'Excluir calendarios de búsqueda [horas de filtros habilitados]', // !
                                        'fr' => 'Exclure les calendriers de la recherche [filtres des heures activés]')); //!
                
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_NO_CALENDARS',
                                        'parent' => 'PARENT_SEARCHES_EDIT_SEARCH',
                                        'text' => 'There are no calendars created. Go to <a href="%s">calendars</a> page to create one.',
                                        'de' => 'Es wurden keine Kalender erstellt. Gehen Sie zur <a href="%s">Kalenderseite</a>, um eine zu erstellen.', // !
                                        'es' => 'No hay calendarios creados. Vaya a la página <a href="%s">calendarios</a>, para crear uno.', // !
                                        'fr' => 'Aucun calendrier n<<single-quote>>est créé. Allez à la page <a href="%s">calendriers</a> pour en créer un.')); //!
                
                return $text;
            }
            
            /*
             * Search - Delete search text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesDeleteSearch($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_DELETE_SEARCH',
                                        'parent' => '',
                                        'text' => 'Search - Delete search'));
                
                array_push($text, array('key' => 'SEARCHES_DELETE_SEARCH_CONFIRMATION',
                                        'parent' => 'PARENT_SEARCHES_DELETE_SEARCH',
                                        'text' => 'Are you sure you want to delete this search?',
                                        'de' => 'Möchten Sie diese Suche wirklich löschen?', // !
                                        'es' => '¿Seguro que quieres borrar esta búsqueda?', // !
                                        'fr' => 'Êtes-vous sûr de vouloir supprimer cette recherche?')); //!
                array_push($text, array('key' => 'SEARCHES_DELETE_SEARCH_SUBMIT',
                                        'parent' => 'PARENT_SEARCHES_DELETE_SEARCH',
                                        'text' => 'Delete search',
                                        'de' => 'Suche löschen', // !
                                        'es' => 'Suprima búsqueda', // !
                                        'fr' => 'Supprimez recherche')); //!
                array_push($text, array('key' => 'SEARCHES_DELETE_SEARCH_DELETING',
                                        'parent' => 'PARENT_SEARCHES_DELETE_SEARCH',
                                        'text' => 'Deleting search ...',
                                        'de' => 'Suche wird gelöscht ...', // !
                                        'es' => 'Supresión de búsqueda...', // !
                                        'fr' => 'Suppression de recherche...')); //!
                array_push($text, array('key' => 'SEARCHES_DELETE_SEARCH_SUCCESS',
                                        'parent' => 'PARENT_SEARCHES_DELETE_SEARCH',
                                        'text' => 'You have successfully deleted the search.',
                                        'de' => 'Sie haben die Suche erfolgreich gelöscht.', // !
                                        'es' => 'Ha eliminado con éxito la búsqueda.', // !
                                        'fr' => 'Vous avez réussi à supprimer la recherche.')); //!
                
                return $text;
            }
            
            /*
             * Search - Help text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesHelp($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_HELP',
                                        'parent' => '',
                                        'text' => 'Search - Help'));
                
                array_push($text, array('key' => 'SEARCHES_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Click on a search item to open the editing area.',
                                        'de' => 'Klicken Sie auf ein Suchelement, um den Bearbeitungsbereich zu öffnen.', // !
                                        'es' => 'Haga clic en un elemento de búsqueda para abrir el área de edición.', // !
                                        'fr' => 'Cliquez sur un élément de recherche pour ouvrir la zone d<<single-quote>>édition.')); //!
                array_push($text, array('key' => 'SEARCHES_ADD_SEARCH_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Click on the "plus" icon to add a search.',
                                        'de' => 'Klicken Sie auf das "Plus"-Symbol, um eine Suche hinzuzufügen.', // !
                                        'es' => 'Haga clic en el icono "plus" para añadir una búsqueda.', // !
                                        'fr' => 'Cliquez sur l<<single-quote>>icône "plus" pour ajouter une recherche.')); //!
                
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Click on the "search" icon to edit search details.',
                                        'de' => 'Klicken Sie auf das Symbol "Suchen", um die Suchdetails zu bearbeiten.', // !
                                        'es' => 'Haga clic en el icono de "búsqueda" para editar los detalles de búsqueda.', // !
                                        'fr' => 'Cliquez sur l<<single-quote>>icône "recherche" pour modifier les détails de la recherche.')); //!
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_SETTINGS_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Click on the "gear" icon to edit search settings.',
                                        'de' => 'Klicken Sie auf das "Zahnrad"-Symbol, um die Sucheinstellungen zu bearbeiten.', // !
                                        'es' => 'Haga clic en el icono "engranaje" para editar la configuración de búsqueda.', // !
                                        'fr' => 'Cliquez sur l<<single-quote>>icône "gear" pour modifier les paramètres de recherche.')); //!
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_DELETE_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Click the "trash" icon to delete the search.',
                                        'de' => 'Klicken Sie auf das Symbol "Papierkorb", um die Suche zu löschen.', // !
                                        'es' => 'Haga clic en el icono "basura" para eliminar la búsqueda.', // !
                                        'fr' => 'Cliquez sur l<<single-quote>>icône "corbeille" pour supprimer la recherche.')); //!
                array_push($text, array('key' => 'SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'If hours filters are enabled only calendars that have availability set for hours are included in search, else only calendar that have availability set for days are included.',
                                        'de' => 'Wenn Stundenfilter aktiviert sind, werden nur Kalender mit festgelegter Verfügbarkeit für Stunden in die Suche einbezogen, andernfalls werden nur Kalender mit festgelegter Verfügbarkeit für Tage einbezogen.', // !
                                        'es' => 'Si los filtros de horas están habilitados sólo calendarios que tienen la disponibilidad fijada para las horas se incluyen en la búsqueda, sino sólo calendario que tienen la disponibilidad fijada para los días se incluyen.', // !
                                        'fr' => 'Si les filtres d<<single-quote>>heures sont activés, seuls les calendriers dont la disponibilité est définie pour les heures sont inclus dans la recherche, sinon seuls les calendriers dont la disponibilité est établie pour les jours sont inclus.')); //!
                
                array_push($text, array('key' => 'SEARCHES_SEARCH_NAME_HELP',
                                        'parent' => 'PARENT_SEARCHES_HELP',
                                        'text' => 'Change search name.',
                                        'de' => 'Suchnamen ändern.', // !
                                        'es' => 'Cambio la nombre de búsqueda.', // !
                                        'fr' => 'Nom de recherche de changement.')); //!
                
                return $text;
            }
            
             /*
             * Search - Search widget text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesWidget($text){
                array_push($text, array('key' => 'PARENT_SEARCHES_WIDGET',
                                        'parent' => '',
                                        'text' => 'Search - Widget'));
                array_push($text, array('key' => 'SEARCHES_WIDGET',
                                        'parent' => 'PARENT_SEARCHES_WIDGET',
                                        'text' => 'Check Availability',
                                        'de' => 'Verfügbarkeit prüfen', // !
                                        'es' => 'Consultar disponibilidad', // !
                                        'fr' => 'Vérifier la disponibilité')); //!
                return $text;
            }
            
            /*
             * Search front end text.
             * 
             * @param lang (array): current translation
             * 
             * @return array with updated translation
             */
            function searchesFrontEnd($text){
                array_push($text, array('key' => 'PARENT_SEARCH_FRONT_END',
                                        'parent' => '',
                                        'text' => 'Search - Front end'));
                     
                array_push($text, array('key' => 'SEARCH_FRONT_END_TITLE',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Search',
                                        'de' => 'Suche', // !
                                        'es' => 'Búsqueda', // !
                                        'fr' => 'Recherche', //!
                                        'location' => 'all'));
                     
                array_push($text, array('key' => 'SEARCH_FRONT_END_CHECK_IN',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Check in',
                                        'de' => 'Anreise',
                                        'es' => 'Llegada', // !
                                        'nl' => 'Check in',
                                        'fr' => 'Arrivée',
                                        'pl' => 'Przyjazd',
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_CHECK_OUT',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Check out',
                                        'de' => 'Abreise',
                                        'es' => 'Salida', // !
                                        'nl' => 'Check uit',
                                        'fr' => 'Départ',
                                        'pl' => 'Wyjazd',
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_START_HOUR',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Start at',
                                        'de' => 'Start um',
                                        'es' => 'Comenzar en', // !
                                        'nl' => 'Start op',
                                        'fr' => 'Arrivée à',
                                        'pl' => 'Rozpoczęcie',
                                        'location' => 'all')); 
                array_push($text, array('key' => 'SEARCH_FRONT_END_END_HOUR',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Finish at',
                                        'de' => 'Ende um',
                                        'es' => 'Terminar', // !
                                        'nl' => 'Eindigd op',
                                        'fr' => 'Départ à',
                                        'pl' => 'Zakończenie',
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_NO_ITEMS',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'No book items',
                                        'de' => 'Anzahl von Artikeln, um zu reservieren',
                                        'es' => 'Número de artículos por reservar', // !
                                        'nl' => '# Accomodaties',
                                        'fr' => 'Aucun élément de réservation',
                                        'pl' => 'Brak rezerwacji',
                                        'location' => 'all'));
                /*
                 * No data.
                 */
                array_push($text, array('key' => 'SEARCH_FRONT_END_NO_AVAILABILITY',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Nothing available.',
                                        'de' => 'Nichts verfügbar', // !
                                        'es' => 'Nada disponible', // !
                                        'fr' => 'Rien de disponible.', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_NO_SERVICES_AVAILABLE',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'There are no services available for the period you selected.',
                                        'de' => 'Für den ausgewählten Zeitraum sind keine Dienste verfügbar.', // !
                                        'es' => 'No hay servicios disponibles para el periodo seleccionado.', // !
                                        'nl' => 'Er zijn geen er zijn geen diensten beschikbaar voor de periode die u hebt geselecteerd.',
                                        'fr' => 'Il n<<single-quote>>y a pas de services disponibles pour la période que vous avez sélectionné.',
                                        'pl' => 'W wybranych terminie nie posiadamy wolnych miejsc.',
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_NO_SERVICES_AVAILABLE_SPLIT_GROUP',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'You cannot add divided groups to a reservation.', 
                                        'de' => 'Sie können einer Reservierung keine geteilten Gruppen hinzufügen.', //!
                                        'es' => 'No se pueden añadir grupos divididos a una reserva.', // !
                                        'fr' => 'Vous ne pouvez pas ajouter des groupes divisés à une réservation.', //!
                                        'location' => 'all'));
                /*
                 * Sort
                 */
                array_push($text, array('key' => 'SEARCH_FRONT_END_SORT_TITLE',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Sort by',
                                        'de' => 'Sortieren nach', // !
                                        'es' => 'Ordenar por', // !
                                        'fr' => 'Trier par', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_SORT_NAME',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Name',
                                        'de' => 'Name', // !
                                        'es' => 'Nombre', // !
                                        'fr' => 'Nom', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_SORT_PRICE',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Price',
                                        'de' => 'Preis', // !
                                        'es' => 'Precio', // !
                                        'fr' => 'Prix', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_SORT_ASC',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Ascending',
                                        'de' => 'Aufsteigender', // !
                                        'es' => 'Ascendente', // !
                                        'fr' => 'Ascendant', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_SORT_DESC',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Descending',
                                        'de' => 'Absteigender', // !
                                        'es' => 'Descendente', // !
                                        'fr' => 'Descendant', //!
                                        'location' => 'all'));
                /*
                 * View
                 */
                array_push($text, array('key' => 'SEARCH_FRONT_END_VIEW_GRID',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Grid view',
                                        'de' => 'Rasteransicht', // !
                                        'es' => 'Vista cuadrícula', // !
                                        'fr' => 'Vue de grille', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_VIEW_LIST',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'List view',
                                        'de' => 'Listenansicht', // !
                                        'es' => 'Vista de lista', // !
                                        'fr' => 'Vue de liste', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_VIEW_MAP',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Map view',
                                        'de' => 'Kartenansicht', // !
                                        'es' => 'Vista del mapa', // !
                                        'fr' => 'Vue de carte', //!
                                        'location' => 'all'));
                /*
                 * Results
                 */
                array_push($text, array('key' => 'SEARCH_FRONT_END_RESULTS_PRICE',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'Start at %s',
                                        'de' => 'Beginnen bei %s', // !
                                        'es' => 'Comenzar en %s', // !
                                        'fr' => 'Début à %s', //!
                                        'location' => 'all'));
                array_push($text, array('key' => 'SEARCH_FRONT_END_RESULTS_VIEW',
                                        'parent' => 'PARENT_SEARCH_FRONT_END',
                                        'text' => 'View',
                                        'de' => 'Ansicht', // !
                                        'es' => 'Vista', // !
                                        'fr' => 'Vue', //!
                                        'location' => 'all'));
                
                return $text;
            }
        }
    }