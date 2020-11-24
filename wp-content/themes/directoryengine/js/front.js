(function (Views, Models, $, Backbone, Collections) {
    Views.Map = Backbone.View.extend({
        // load info window content template
        events: {},
        // initialize view
        initialize: function (options) {
            _.bindAll(this, 'setCenter', 'renderMap');
            var view = this;
            if ($('#map-top-wrapper').length === 0) {
                return;
            }
            view.options = _.extend(this, options);
            view.center = new google.maps.LatLng(this.options.latitude, this.options.longitude);
            view.map_options = {
                /*'zoom': parseInt(ae_globals.map_zoom),*/
                'zoom': 15,
                'center': view.center,
                'mapTypeId': google.maps.MapTypeId.ROADMAP,
                'scrollwheel': true,
                'zoomControl': true,
                'styles': [{"featureType": "poi.business", "stylers": [{"is_submit_projectvisibility": "off"}]}]
            };
            this.template = _.template($('#ae_info_content_template').html());
            // map marker collections
            view.markers = [];
            view.currentMarker = null;
            // map marker cluster             view.markerCluster = [];
            this.initMapWindow();
            // Map for default save-widget
            this.map = new google.maps.Map(document.getElementById("map-top-wrapper"), view.map_options);
            var map_style = ae_globals.global_map_style;

            if (map_style != null) {
                if (ae_globals.map_typestyle != 1) {
                    // remove point and transit on mobile
                    map_style.push(
                        {featureType: "poi", stylers: [{visibility: 'off'}]},
                        {featureType: "transit.station.bus", stylers: [{visibility: 'off'}]}
                    );
                } else {
                    map_style.push(
                        {featureType: "poi", stylers: [{visibility: 'on'}]},
                        {featureType: "transit.station.bus", stylers: [{visibility: 'on'}]}
                    );
                }
            } else {
                if (ae_globals.map_typestyle != 1) {
                    // remove point and transit on mobile
                    this.map.set('styles', [
                        {featureType: "poi", stylers: [{visibility: 'off'}]},
                        {featureType: "transit.station.bus", stylers: [{visibility: 'off'}]}
                    ]);
                }
            }

            if (ae_globals.global_map_style) {
                this.map.setOptions({
                    styles: map_style
                });
            }

            this.categories = [];
            if ($('#de-categories-data').length > 0) {
                this.categories = JSON.parse($('#de-categories-data').html());
            }

            this.addControl();
            this.initMapIcon();
            //this.renderMap();
            this.lockView = true;
            this.nearby = false;
            if ($('#nearby_location').length > 0) {
                this.nearby = true;
            }
            //bind event when user give location
            AE.pubsub.on('de:getCurrentPosition', this.setCenter, this);
            AE.pubsub.on('de:map:drawGeoLocate', this.drawGeoLocate, this);
            AE.pubsub.on('de:map:GeoLocate', this.geoLocate, this);
            AE.pubsub.on('de:map:geoDirection', this.geoDirection, this);

            if (this.options.ajax == "1")
                this.renderMap();
            else
                google.maps.event.addListener(view.map, 'idle', view.renderMap);


            view.blockUi = new Views.BlockUi();
        },
        geoLocate: function (successEventId) {
            GMaps.geolocate({
                success: function (position) {
                    AE.pubsub.trigger(successEventId, position);
                },
                error: function (error) {
                    AE.pubsub.trigger('ae:notification', {msg: ae_globals.geolocation_failed, notice_type: 'error',});

                },
                not_supported: function () {
                    alert(ae_globals.browser_supported);
                    AE.pubsub.trigger('ae:notification', {msg: ae_globals.browser_supported, notice_type: 'error',});
                }
            });
        },
        drawGeoLocate: function (position) {
            var view = this;
            if (typeof this.model === 'undefined' || this.nearby || !this.lockView) {
                AE.pubsub.trigger('de:getCurrentPosition', position.coords);
                // view.map.setZoom(15);
            }
            var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            if (view.currentMarker === null) {
                var icon = {
                    url: ae_globals.current_possition_img,
                    size: new google.maps.Size(40, 40),
                };
                view.currentMarker = new google.maps.Marker({
                    map: view.map,
                    animation: google.maps.Animation.DROP,
                    title: ae_globals.current_possition_title,
                    position: latLng,
                    icon: icon
                });
            }
            else {
                view.currentMarker.setPosition(latLng);
                view.currentMarker.setAnimation(google.maps.Animation.DROP);
            }
        },
        /**
         * Show direction
         *
         *@Since from version 1.8.4
         */
        geoDirection: function (position) {
            var view = this;
            if ($('#place_id').length > 0) {
                var place = JSON.parse($('#place_id').html()),
                    height = new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                    oceanBeach = new google.maps.LatLng(place.et_location_lat, place.et_location_lng),
                    directionsDisplay = new google.maps.DirectionsRenderer({polylineOptions: {strokeColor: "red"}}),
                    directionsService = new google.maps.DirectionsService(),
                    selectedMode = document.getElementById('mode').value;
                directionsDisplay.setMap(view.map);
                //change icon direct of maps
                var divIcon = $('#showDirectoryon .icon');
                $('#showDirectoryon .icon').remove();
                switch (selectedMode) {
                    case 'DRIVING':
                        $('#showDirectoryon').append('<div class="icon fa fa-car"></div>');
                        break;
                    case 'WALKING':
                        $('#showDirectoryon').append('<div class="icon fa fa-male"></div>');
                        break;
                    case 'BICYCLING':
                        $('#showDirectoryon').append('<div class="icon fa fa-bicycle"></div>');
                        break;
                    case 'TRANSIT':
                        $('#showDirectoryon').append('<div class="icon fa fa-bus"></div>');
                        break;
                }
                var request = {
                    origin: height,
                    destination: oceanBeach,
                    // Note that Javascript allows us to access the constant
                    // using square brackets and a string value as its
                    // "property."
                    travelMode: google.maps.TravelMode[selectedMode]
                };
                directionsService.route(request, function (response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });
            }
        },
        addControl: function () {
            var view = this;
            // Create a div to hold the control.
            var controlDiv = document.createElement('div');
            controlDiv.className = "app-vertical-widget-holder";
            controlDiv.title = ae_globals.map_gohome_title;
            // Set CSS for the control border.
            var controlUI = document.createElement('div');
            controlUI.className = 'app-vertical-item';
            controlDiv.appendChild(controlUI);

            // Mylocation Button.
            var goHomeButton = document.createElement('div');
            goHomeButton.id = "gohome";
            goHomeButton.className = "widget-button";
            goHomeButton.innerHTML = '<div class="icon fa fa-location-arrow" ></div>';
            controlUI.appendChild(goHomeButton);

            // directory Button.
            if (( typeof ae_globals.is_single !== 'undefined' && ae_globals.is_single === '1')) {
                var showDirectoryButton = document.createElement('div');
                showDirectoryButton.id = "showDirectoryon";
                showDirectoryButton.className = "widget-button";
                showDirectoryButton.innerHTML = '<div class="icon fa fa-car"></div>';
                controlUI.appendChild(showDirectoryButton);
                var selectDirectionMode = document.createElement('select');
                selectDirectionMode.id = 'mode';
                selectDirectionMode.innerHTML = '';
                selectDirectionMode.innerHTML += '<option value="DRIVING">' + ae_globals.geo_direction.driving + '</option>';
                selectDirectionMode.innerHTML += '<option value="WALKING">' + ae_globals.geo_direction.walking + '</option>';
                selectDirectionMode.innerHTML += '<option value="BICYCLING">' + ae_globals.geo_direction.bicycling + '</option>';
                selectDirectionMode.innerHTML += '<option value="TRANSIT">' + ae_globals.geo_direction.transit + '</option>';
                controlUI.appendChild(selectDirectionMode);
                google.maps.event.addDomListener(showDirectoryButton, 'click', function () {
                    view.lockView = false;
                    AE.pubsub.trigger('de:map:GeoLocate', 'de:map:geoDirection');
                });
            }
            view.map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);

            // add Button Fullscreen
            // if(ae_globals.ae_is_mobile === '1'){
            //     var fullScreenButton = view.FullScreenControl(map, 'Fullscreen','Exit fullscreen');
            //     view.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(fullScreenButton);
            // }
            google.maps.event.addDomListener(goHomeButton, 'click', function () {
                view.lockView = false;
                AE.pubsub.trigger('de:map:GeoLocate', 'de:map:drawGeoLocate');
            });
        },
        addContextMenu: function () {
            this.map.setContextMenu({
                control: 'map',
                options: [{
                    title: 'Add marker',
                    name: 'add_marker',
                    action: function (e) {
                        this.addMarker({
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng(),
                            title: 'New marker'
                        });
                    }
                }, {
                    title: 'Center here',
                    name: 'center_here',
                    action: function (e) {
                        this.setCenter(e.latLng.lat(), e.latLng.lng());
                    }
                }]
            });
        },
        /**
         *
         * @param origin array of [lat, long]
         * @param destination
         */
        travelRoute: function (origin, destination) {
            map.travelRoute({
                origin: origin,
                destination: destination,
                travelMode: 'driving',
                step: function (e) {
                    $('#instructions').append('<li>' + e.instructions + '</li>');
                    $('#instructions li:eq(' + e.step_number + ')').delay(600 * e.step_number).fadeIn(200, function () {
                        map.drawPolyline({
                            path: e.path,
                            strokeColor: '#131540',
                            strokeOpacity: 0.6,
                            strokeWeight: 6
                        });
                    });
                }
            });
        },
        initMapIcon: function () {
            var view = this;
            this.icons = {};
            this.colors = {};
            this.fontClass = {};
            _.each(this.categories, function (element) {
                var icon = {
                    path: 'M 50 -119.876 -50 -119.876 -50 -19.876 -13.232 -19.876 0.199 0 13.63 -19.876 50 -19.876 Z',
                    fillColor: element.color,
                    fillOpacity: 1,
                    scale: 0.3,
                    strokeColor: element.color,
                    strokeWeight: 0
                };
                if (element.parent !== 0 && typeof element.icon === 'undefined') {
                    view.icons[element.term_id] = view.icons[element.parent];
                    view.fontClass[element.term_id] = view.fontClass[element.parent];
                } else {
                    view.icons[element.term_id] = icon;
                    view.fontClass[element.term_id] = element.icon;
                }
                if (element.parent !== 0 && typeof element.color === 'undefined') {
                    view.colors[element.term_id] = view.colors[element.parent];
                }
                else {
                    view.colors[element.term_id] = element.color;
                }

            });
            view.labelAnchor = new google.maps.Point(10, 31);
        },

        initMapWindow: function () {
            var view = this,
                // init map info window
                iw1 = new InfoBubble({
                    content: '',
                    // position: new google.maps.LatLng(-35, 151),
                    shadowStyle: 0,
                    padding: 0,
                    borderRadius: 0,
                    arrowSize: 0,
                    borderWidth: 5,
                    borderColor: '#ccc',
                    disableAutoPan: false,
                    backgroundColor: '#fff',
                    arrowStyle: 0,
                    maxWidth: 280,
                    minWidth: 260,
                    minHeight: 70,
                    maxHeight: 400,
                    autoPan: true
                });

            view.infoWindow = iw1;
        },
        /**
         * render map call ajax to get marker data
         * @author Dakachi
         */
        renderMap: function (data) {

            var view = this,
                cat = '',
                data = {
                    action: 'de_get_map_data'
                };


            view.markers = [];
            /**
             * ajax request get all place on map
             */
            if ($('.main-pagination .ae_query').length > 0) {
                var query = JSON.parse($('.main-pagination .ae_query').html());
                data.query = query;
            }
            var i = 100,
                k = 1;
            current_place = Array();
            if ($('#total_place').length > 0) {
                i = JSON.parse($('#total_place').html());
                current_place = Array(i.current_place);
                i = i.number;
            }
            // get category data to query post in category var cat = '';
            if ($('#place_cat_slug').length > 0) {
                cat = JSON.parse($('#place_cat_slug').html());
                cat = cat.slug;
            }


            if (view.loaded === true && ae_globals.single_map_marker === "1") return;

            data.paged = k;
            data.showposts = 50;
            data.place_category = cat;


            if (ae_globals.is_single && ae_globals.single_map_marker === "1") {
                data = current_place;
                view.map.setZoom(16);  // zoom de la ficha del anuncio
                view.ajaxSuccess(data);

            } else if (ae_globals.is_search == 1 ||
                ae_globals.is_tax == 1 ||
                ae_globals.is_archive == 1) {

                var center = this.map.getCenter(),
                    bounds = this.get_bound_distance();

                data = current_place[0];

                if (data) {
                    view.ajaxSuccess(data);
                }
                else {
                    view.map.setCenter(center);
                    view.map.setZoom(6);  // zoom a españa
                }


            }
            else {
                var center = this.map.getCenter(),
                    bounds = this.get_bound_distance();
                data.query = {center: String(center.lat()) + ',' + String(center.lng()), radius: bounds};
                $.ajax({
                    type: 'get',
                    url: ae_globals.ajaxURL,
                    data: data,
                    beforeSend: function () {
                    },
                    success: function (resp) {
                        if (IsJsonString(resp)) {
                            resp = JSON.parse(resp);
                        }
                        if (typeof resp.data !== 'undefined' && resp.data.length > 0) {
                            var data = resp.data;
                            // bind data markers to map
                            view.ajaxSuccess(data);
                        }
                    }
                });
            }
        },

        get_bound_distance: function () {
            var bounds = new google.maps.LatLngBounds();
            var center = bounds.getCenter();
            var ne = bounds.getNorthEast();

            // r = radius of the earth in statute miles
            var r = 3963.0;

            // Convert lat or lng from decimal degrees into radians (divide by 57.2958)
            var lat1 = center.lat() // 57.2958;
            var lon1 = center.lng() // 57.2958;
            var lat2 = ne.lat() // 57.2958;
            var lon2 = ne.lng() // 57.2958;

            // distance = circle radius from center to Northeast corner of bounds
            var dis = r * Math.acos(Math.sin(lat1) * Math.sin(lat2) +
                    Math.cos(lat1) * Math.cos(lat2) * Math.cos(lon2 - lon1));

            return dis;
        },

        /**
         * after successful request map data
         */
        ajaxSuccess: function (data) {
            var view = this;
            var bounds = new google.maps.LatLngBounds();
            if (data) {
                for (var i = 0; i < data.length; i++) {
                    var content = '',
                        // place latitude and longitude
                        latLng = new google.maps.LatLng(data[i].latitude, data[i].longitude),
                        // get place category
                        term = data[i].term_taxonomy_id,
                        color = view.colors[term],
                        fontClass = view.fontClass[term],
                        icon = view.icons[term];
                    bounds.extend(latLng);
                    if (typeof color === 'undefined') {
                        color = '#F59236';
                    }
                    if (typeof fontClass === 'undefined') {
                        fontClass = 'fa-map-marker';
                    }
                    if (typeof icon === 'undefined') {
                        var icon = {
                            path: 'M 50 -119.876 -50 -119.876 -50 -19.876 -13.232 -19.876 0.199 0 13.63 -19.876 50 -19.876 Z',
                            fillColor: '#F59236',
                            fillOpacity: 1,
                            scale: 0.3,
                            strokeColor: '#F59236',
                            strokeWeight: 0
                        };
                    }
                    var marker = new MarkerWithLabel({
                        position: latLng,
                        // label by place category color and icon class
                        labelContent: "<span><i style='color:" + color + ";' class='fa " + fontClass + "'></i><span>",
                        labelAnchor: view.labelAnchor,
                        labelClass: "map-labels", // the CSS class for the label
                        labelStyle: {
                            opacity: 1.0
                        },
                        icon: icon
                    });
                    // set marker content using in multichoice
                    marker.content = '';
                    marker.ID = data[i].ID;
                    view.markers.push(marker);
                    // attach info window
                    view.attachMarkerInfowindow(marker, content, data[i]);


                    // if (typeof view.model !== 'undefined' && view.model.get('ID') == data[i]['ID'] && view.loaded !== true) {  // miguel

                    if (typeof view.model !== 'undefined' && ae_globals.is_single) {
                        var model_data = view.model.toJSON();
                        if (model_data.permalink) {  //Miguel.  si vienen datos los ponemos (en ficha anuncio si vienen, en search.php no)
                            content = view.template(model_data);
                            marker.content = content;
                        }
                        view.map.setCenter(latLng);
                        /**
                         * set content for info window
                         */
                        view.infoWindow.setContent(content);
                        // set border color for info window
                        view.infoWindow.setBorderColor(color);
                        // open info window
                        view.infoWindow.open(this.map, marker);

                    }


                    /* 08_11_2016 Id : 1 S*/
                    //view.map.setZoom(15);
                    /* 08_11_2016 Id : 1 E*/
                    google.maps.event.addListener(view.map, 'idle', function () {
                        // display rating on map after the map is loaded
                        $('.infowindow .rate-it').raty({
                            readOnly: true,
                            half: true,
                            score: function () {
                                return $(this).attr('data-score');
                            },
                            hints: raty.hint
                        });
                    });


                    if (i == data.length - 1) { // ultimo
                        // set the flag to check the single marker info window is trigger or not
                        view.loaded = true;
                    }

                }

            }

            if (typeof view.markerCluster !== 'undefined') {
                view.markerCluster.clearMarkers();
            }
            // init map cluster
            view.markerCluster = new MarkerClusterer(view.map, view.markers, {
                imagePath: 'https://raw.githubusercontent.com/googlemaps/v3-utility-library/master/markerclustererplus/images/m',
                zoomOnClick: true,
                gridSize: 20
            });
            // bind event click on cluster for multi marker in a position
            view.markerCluster.onClick = function (icon) {
                return view.multiChoice(icon.cluster_);
            };
            if (typeof view.model === 'undefined' && parseInt(ae_globals.fitbounds)) {
                view.map.fitBounds(bounds);
                // Set min-zoom of map is 1

                if (view.map.getZoom() == 0) view.map.setZoom(1);
                // Set center of fitbounds
                view.map.setCenter(bounds.getCenter());
            }
            if (ae_globals.is_search && parseInt(ae_globals.fitbounds) ||
                ae_globals.is_tax && parseInt(ae_globals.fitbounds) ||
                ae_globals.is_archive && parseInt(ae_globals.fitbounds)) {
                view.map.fitBounds(bounds);
                if (view.map.getZoom() == 0) view.map.setZoom(1);
                view.map.setCenter(bounds.getCenter());

                if (data.length == 1) { // si solo hay un resultado, ponemos un zoom por defecto
                    view.map.setZoom(15);
                }
            }


        },
        /**
         * attach info window to a marker
         * @param marker google marker object
         * @param content the info window content
         * @param data object data
         */
        attachMarkerInfowindow: function (marker, content, data) {
            var view = this,
                term = data.term_taxonomy_id;
            google.maps.event.addListener(marker, 'click', function () {
                if (marker.content === '') {
                    $.ajax({
                        type: 'get',
                        url: ae_globals.ajaxURL,
                        data: {action: 'de-get-map-info', ID: marker.ID},
                        beforeSend: function () {
                            view.blockUi.block($('#map-top-wrapper'));
                        },
                        success: function (resp) {
                            view.blockUi.unblock();
                            $('div.map-element').html(resp.data.content);
                            if (!ae_globals.is_single) {
                                $('.rate-it').raty({
                                    readOnly: true,
                                    half: true,
                                    score: function () {
                                        return $(this).attr('data-score');
                                    },
                                    hints: raty.hint
                                });
                            }
                            var content = $('div.map-element').html();
                            /**
                             * set content for info window
                             */
                            view.infoWindow.setContent(content);
                            // set border color for info window
                            var color = view.colors[term];
                            if (typeof color === 'undefined') {
                                color = '#F59236';
                            }
                            view.infoWindow.setBorderColor(color);
                            // open info window
                            view.infoWindow.open(view.map, marker);
                            marker.content = content;

                        }
                    });
                } else {
                    view.infoWindow.setContent(marker.content);
                    // set border color for info window
                    var color = view.colors[term];
                    if (typeof color === 'undefined') {
                        color = '#F59236';
                    }
                    $('.rate-it').raty({
                        readOnly: true,
                        half: true,
                        score: function () {
                            return $(this).attr('data-score');
                        },
                        hints: raty.hint
                    });

                    view.infoWindow.setBorderColor(color);
                    // open info window
                    view.infoWindow.open(view.map, marker);
                }
            });
        },
        /**
         * bind multi marker detail to cluster
         */
        multiChoice: function (clickedCluster) {
            var view = this;
            if (clickedCluster.getMarkers().length > 1) {
                var markers = clickedCluster.getMarkers();
                var market_id = [];
                for (var i = 0; i < markers.length; i++) {
                    market_id.push(markers[i].ID);
                }
                if (markers[0].content === '') {
                    $.ajax({
                        type: 'get',
                        url: ae_globals.ajaxURL,
                        data: {action: 'de-get-map-info', IDs: market_id},
                        beforeSend: function () {
                            view.blockUi.block($('#map-top-wrapper'));
                        },
                        success: function (resp) {
                            view.blockUi.unblock();
                            $('div.map-element').html(resp.data.content);
                            $('.rate-it').raty({
                                readOnly: true,
                                half: true,
                                score: function () {
                                    return $(this).attr('data-score');
                                },
                                hints: raty.hint
                            });
                            var content = $('div.map-element').html();
                            /**
                             * set content for info window
                             */
                            view.infoWindow.setBorderColor('#F59236');
                            view.infoWindow.setContent('<div class="jobs-wrapper">' + content + '</div>');
                            view.infoWindow.open(view.map, markers[0]);
                            markers[0].content = '<div class="jobs-wrapper">' + content + '</div>';
                            // return false;
                        }
                    });
                }
                else {
                    view.infoWindow.setBorderColor('#F59236');
                    view.infoWindow.setContent(markers[0].content);
                    view.infoWindow.open(view.map, markers[0]);
                }

            }
            return true;
        },
        /**
         * catch event when get user position and set map to his location
         */
        setCenter: function (coords) {
            var latLng = new google.maps.LatLng(coords.latitude, coords.longitude);
            this.map.setCenter(latLng);
        },

        /**
         * create button on Map
         */
        googleMapButton: function (text, className) {
            "use strict";
            var controlDiv = document.createElement("div");
            controlDiv.className = className;
            controlDiv.index = 1;
            controlDiv.style.padding = "10px";
            // set CSS for the control border.
            var controlUi = document.createElement("div");
            controlUi.style.backgroundColor = "rgb(255, 255, 255)";
            controlUi.style.color = "#565656";
            controlUi.style.cursor = "pointer";
            controlUi.style.textAlign = "center";
            controlUi.style.boxShadow = "rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px";
            controlDiv.appendChild(controlUi);
            // set CSS for the control interior.
            var controlText = document.createElement("div");
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "11px";
            controlText.style.paddingTop = "8px";
            controlText.style.paddingBottom = "8px";
            controlText.style.paddingLeft = "8px";
            controlText.style.paddingRight = "8px";
            controlText.innerHTML = text;
            controlUi.appendChild(controlText);
            $(controlUi).on("mouseenter", function () {
                controlUi.style.backgroundColor = "rgb(235, 235, 235)";
                controlUi.style.color = "#000";
            });
            $(controlUi).on("mouseleave", function () {
                controlUi.style.backgroundColor = "rgb(255, 255, 255)";
                controlUi.style.color = "#565656";
            });
            return controlDiv;
        },
        FullScreenControl: function (map, enterFull, exitFull) {
            var view = this;
            "use strict";
            if (enterFull === void 0) {
                enterFull = null;
            }
            if (exitFull === void 0) {
                exitFull = null;
            }
            if (enterFull == null) {
                enterFull = "Full screen";
            }
            if (exitFull == null) {
                exitFull = "Exit full screen";
            }
            var controlDiv = view.googleMapButton(enterFull, "fullScreen");
            var fullScreen = false;
            var interval;
            var mapDiv = view.map.getDiv();
            var divStyle = mapDiv.style;
            if (mapDiv.runtimeStyle) {
                divStyle = mapDiv.runtimeStyle;
            }
            var originalPos = divStyle.position;
            var originalWidth = divStyle.width;
            var originalHeight = divStyle.height;
            // ie8 hack
            if (originalWidth === "") {
                originalWidth = mapDiv.style.width;
            }
            if (originalHeight === "") {
                originalHeight = mapDiv.style.height;
            }
            var originalTop = divStyle.top;
            var originalLeft = divStyle.left;
            var originalZIndex = divStyle.zIndex;
            var bodyStyle = document.body.style;
            if (document.body.runtimeStyle) {
                bodyStyle = document.body.runtimeStyle;
            }
            var originalOverflow = bodyStyle.overflow;
            controlDiv.goFullScreen = function () {
                var center = view.map.getCenter();
                mapDiv.style.position = "fixed";
                mapDiv.style.width = "100%";
                mapDiv.style.height = "100%";
                mapDiv.style.top = "30";
                mapDiv.style.left = "0";
                mapDiv.style.zIndex = "10000";
                document.body.style.overflow = "hidden";
                $(controlDiv).find("div div").html(exitFull);
                fullScreen = true;
                google.maps.event.trigger(view.map, "resize");
                view.map.setCenter(center);
                // this works around street view causing the map to disappear, which is caused by Google Maps setting the
                // css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
                interval = setInterval(function () {
                    if (mapDiv.style.position !== "fixed") {
                        mapDiv.style.position = "fixed";
                        google.maps.event.trigger(map, "resize");
                    }
                }, 100);
            };
            controlDiv.exitFullScreen = function () {
                var center = view.map.getCenter();
                if (originalPos === "") {
                    mapDiv.style.position = "relative";
                } else {
                    mapDiv.style.position = originalPos;
                }
                mapDiv.style.width = originalWidth;
                mapDiv.style.height = originalHeight;
                mapDiv.style.top = originalTop;
                mapDiv.style.left = originalLeft;
                mapDiv.style.zIndex = originalZIndex;
                document.body.style.overflow = originalOverflow;
                $(controlDiv).find("div div").html(enterFull);
                fullScreen = false;
                google.maps.event.trigger(view.map, "resize");
                view.map.setCenter(center);
                clearInterval(interval);
            };
            // setup the click event listener
            google.maps.event.addDomListener(controlDiv, "click", function () {
                if (!fullScreen) {
                    controlDiv.goFullScreen();
                } else {
                    controlDiv.exitFullScreen();
                }
            });
            return controlDiv;
        }
    });
    /**
     * modal edit post
     */
    Views.EditPost = Views.Modal_Box.extend({
        events: {
            'submit form#submit_form': 'submitPost',
            // 'click form .btn-submit': 'submitPost'
            // update map lat long
            'keyup input#et_full_location': 'gecodeMap',
            'keyup input#et_full_location2': 'gecodeMap',
            // remove cover image
            'click a#delete-cover-image': 'removeCover',
            // catch event when user click on video position
            'click input.video-position': 'updateVideoPosition',
            // set claimable for admin
            'change #et_claimable': 'setClaimable',
            // Action serve Time
            'click span.select-date-all': 'selectDateAll',
            'click li.bdate': 'selectButtonDate',
            //'change .open-times input.open-time' : 'inputOpenTime',
            //'change .open-times input.close-time' : 'inputCloseTime',
            'click span.reset-all': 'resetAllDate',
        },
        initialize: function () {
            _.bindAll(this, 'cbSuccess', 'cbBeforeSend');
            var view = this;
            AE.Views.Modal_Box.prototype.initialize.call();
            this.blockUi = new Views.BlockUi();
            this.initValidator();
            if ($('#map').length > 0) {
                view.map = new GMaps({
                    div: '#map',
                    lat: ae_globals.map_center.latitude,
                    lng: ae_globals.map_center.longitude,

                    zoom: 15,
                    panControl: false,
                    zoomControl: true,
                    mapTypeControl: false
                });

                $('#map').slideDown();
            }
            $('li.bdate[data-toggle="tooltip"]').tooltip({'html': true});
        },
        /*
         *   Click select date all open time
         */
        selectDateAll: function (e) {
            var ev = e.target;
            $(ev).toggleClass('active');
            if ($(ev).hasClass('active')) {
                $(ev).text(ae_globals.translate_deselect);
                $('li.bdate').addClass('active');
                $('li.bdate').addClass('recent');
                $(".open-block-sep").show();
                $(".open-times").show();

                /* 03_12_2016 Id : 2 S*/
                $('li.bdate').each(function () {
                    if ($(this).hasClass('active')) {
                        $(this).attr('open-time', $('input.open-time').val());
                        $(this).attr('open-time-2', $('input.open-time-2').val());
                        $(this).attr('close-time', $('input.close-time').val());
                        $(this).attr('close-time-2', $('input.close-time-2').val());
                    }
                });
                /* 03_12_2016 Id : 2 E*/

            } else {
                $(ev).text(ae_globals.translate_select);
                $('li.bdate').removeClass('active');
                $('li.bdate').removeClass('recent');
                $('li.bdate').attr('open-time', '');
                $('li.bdate').attr('close-time', '');
                $('li.bdate').attr('open-time-2', '');
                $('li.bdate').attr('close-time-2', '');


                $(".open-block-sep").hide();
                $(".open-times").hide();

                $(".open-times input").val("");
                $("#alguna_opcion_date_seleccionada").val("");
            }
        },

        /*
         *   Click button date
         */
        selectButtonDate: function (e) {

            $(".open-block-sep").show();
            $(".open-times").show();

            var ev = e.target;
            var active = false;
            $(ev).toggleClass('active');
            $('li.bdate').removeClass('recent');
            /* 03_12_2016 Id : 3 S*/
            if ($(ev).hasClass('active')) {
                $('.select-date-all').addClass('active');
                $('.select-date-all').text(ae_globals.translate_deselect);
                $(ev).attr('open-time', $('input.open-time').val());
                $(ev).attr('close-time', $('input.close-time').val());
                $(ev).attr('open-time-2', $('input.open-time-2').val());
                $(ev).attr('close-time-2', $('input.close-time-2').val());
                $(ev).addClass('recent');
            } else {
                $('input.open-time').val('');
                $('input.close-time').val('');
                $('input.open-time-2').val('');
                $('input.close-time-2').val('');
                $(ev).attr('open-time', '');
                $(ev).attr('close-time', '');
                $(ev).attr('open-time-2', '');
                $(ev).attr('close-time-2', '');

                $('li.bdate').each(function () {
                    if ($(this).hasClass('active')) {
                        active = true;
                    }
                });
                if (!active) {
                    $('.select-date-all').removeClass('active');
                    $('.select-date-all').text(ae_globals.translate_select);

                    $(".open-block-sep").hide();
                    $(".open-times").hide();
                }
            }
            /* 03_12_2016 Id : 3 E*/
        },
        /*
         * Button date active
         */
        buttonDateActive: function () {
            var active = false;
            $('li.bdate').each(function (index) {
                if ($(this).hasClass('active')) {
                    active = true;
                    return;
                }
            });
            if (!active) {
                $('.select-date-all').removeClass('active');
                $('.select-date-all').text(ae_globals.translate_select);
            }
            return active;
        },
        /*
         *   Change input open time
         */
        inputOpenTime: function (time, view) {
            var _this = this;
            var active = this.buttonDateActive();
            // Check Timeformat
            if (time != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test(time)) {
                //alert(ae_globals.invalid_time);
                return;
            }
            if (active) {
                $('li.bdate').each(function (index) {
                    if ($(this).hasClass('active')) {
                        $(this).attr('open-time', time);
                        var open = $(this).attr('open-time');
                        var close = $(this).attr('close-time');
                        if (open == '--') {
                            //$('.close-time').val('');
                            //$(this).attr('data-original-title', '');
                            //$(this).removeClass('vbdate');
                            //$(this).removeClass('active');
                            //$(this).next().removeClass('nbdate');
                            $(this).attr('open-time', 'none');
                            $(this).attr('close-time', 'none');
                        }
                    }
                });
            } else {
                $(view).val("")
                $('.close-time').val('');
            }
            this.InputFormDate();
        },
        inputOpenTime2: function (time, view) {
            var _this = this;
            var active = this.buttonDateActive();
            // Check Timeformat
            if (time != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test(time)) {
                //alert(ae_globals.invalid_time);
                //$(ev).val("");
                return;
            }
            if (active) {
                $('li.bdate').each(function (index) {
                    if ($(this).hasClass('active')) {
                        $(this).attr('open-time-2', time);
                        var open2 = $(this).attr('open-time-2');
                        var close2 = $(this).attr('close-time-2');
                        if (open2 == '--') {
                            //$('.close-time-2').val('');
                            //$(this).attr('data-original-title', '');
                            //$(this).removeClass('vbdate');
                            //$(this).removeClass('active');
                            //$(this).next().removeClass('nbdate');
                            $(this).attr('open-time-2', 'none');
                            $(this).attr('close-time-2', 'none');
                        }
                    }
                });
            } else {
                $(view).val('');
                $('.close-time-2').val('');
            }
        },
        /*
         *   Change input close time
         */
        inputCloseTime: function (time, view) {
            var _this = this;
            var active = this.buttonDateActive();
            // Check Timeformat
            if (time != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test(time)) {
                //alert(ae_globals.invalid_time);
                return;
            }
            if (active) {
                $('li.bdate').each(function (index) {
                    if ($(this).hasClass('active')) {
                        $(this).attr('close-time', time);
                        var open = $(this).attr('open-time');
                        var close = $(this).attr('close-time');
                        if (close == '--') {
                            //$('.open-time').val('');
                            //$(this).attr('data-original-title', '');
                            //$(this).removeClass('vbdate');
                            //$(this).removeClass('active');
                            //$(this).next().removeClass('nbdate');
                            $(this).attr('open-time', 'none');
                            $(this).attr('close-time', 'none');
                        } else {
                            if (open == '--' || open == '') {
                                //$('.close-time').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time', 'none');
                                $(this).attr('close-time', 'none');
                            } else {
                                //$(this).addClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().addClass('nbdate');
                                //$(this).attr('data-original-title', open + ' to ' + close);
                            }
                        }
                    }
                });
                // $('.select-date-all').removeClass('active');
                // $('.select-date-all').text(ae_globals.translate_select);
            } else {
                $(view).val("");
                $('.open-time').val('');
            }
            this.InputFormDate();
        },
        inputCloseTime2: function (time, view) {
            var _this = this;
            var active = this.buttonDateActive();
            // Check Timeformat
            if (time != "--" && !/^([01]?[0-9]|2[0-4]):[0-5][0-9]$/.test(time)) {
                //alert(ae_globals.invalid_time);
                // $(ev).val("");
                return;
            }
            if (active) {
                $('li.bdate').each(function (index) {
                    if ($(this).hasClass('active')) {
                        $(this).attr('close-time-2', time);

                        var open = $(this).attr('open-time');
                        var close = $(this).attr('close-time');

                        var open2 = $(this).attr('open-time-2');
                        var close2 = $(this).attr('close-time-2');
                        if (close2 == '--') {
                            //$('.open-time-2').val('');
                            //$(this).attr('data-original-title', '');
                            //$(this).removeClass('vbdate');
                            //$(this).removeClass('active');
                            //$(this).next().removeClass('nbdate');
                            $(this).attr('open-time-2', 'none');
                            $(this).attr('close-time-2', 'none');
                        } else {
                            if (open2 == '--' || open2 == '') {
                                //$('.close-time-2').val('');
                                //$(this).attr('data-original-title', '');
                                //$(this).removeClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().removeClass('nbdate');
                                $(this).attr('open-time-2', 'none');
                                $(this).attr('close-time-2', 'none');
                            } else {
                                //$(this).addClass('vbdate');
                                //$(this).removeClass('active');
                                //$(this).next().addClass('nbdate');
                                //$(this).attr('data-original-title', open + '<span> to </span>' + close+' and '+open2 + '<span> to </span>' + close2);
                            }
                        }
                    }
                });
                $('.select-date-all').removeClass('active');
                $('.select-date-all').text(ae_globals.translate_select);
            } else {
                $(view).val('');
                $('.open-time-2').val('');
            }
        },
        /*
         *   Input form date
         */
        InputFormDate: function () {
            $('li.bdate').each(function (index) {
                var open = $(this).attr('open-time'),
                    close = $(this).attr('close-time'),
                    open2 = $(this).attr('open-time-2'),
                    close2 = $(this).attr('close-time-2'),
                    text = $(this).data('name');
                if (open != '' && close != '' && open2 != '' && close2 != '') {
                    $('input[name="serve_time[' + text + ']"]').val(open + ' to ' + close + ' and ' + open2 + ' to ' + close2);
                } else if (open != '' && close != '') {
                    $('input[name="serve_time[' + text + ']"]').val(open + ' to ' + close);
                } else {
                    $('input[name="serve_time[' + text + ']"]').val('');
                }
            });
        },
        /*
         * Reset All Date None
         */
        resetAllDate: function () {
            $('li.bdate').each(function () {
                $(this).removeClass('vbdate').removeClass('nbdate');
                $(text - heading - stepis).attr('open-time', '').attr('open-time-2', '').attr('close-time', '').attr('close-time-2', '').attr('data-original-title', '');
                var text = $(this).text();
            });
            $('.time-picker').val('');
            $('.open-input input').val('');
        },
        /**
         * set clamble value
         */
        setClaimable: function (event) {
            event.preventDefault();
            var value = $("#et_claimable_value");
            value.val(value.val() == 0 ? 1 : 0);
        },
        /**
         * init map gecode an address
         */
        initMap: function () {
            var view = this;
            setTimeout(function () {
                view.map.refresh();
                if ($('#et_location_lat').val() !== '' && $('#et_location_lng').val() !== '') {
                    var lat = $('#et_location_lat').val(),
                        lng = $('#et_location_lng').val();
                    view.map.setCenter(lat, lng);
                    view.map.setZoom(15);

                    view.map.addMarker({
                        lat: lat,
                        lng: lng,
                        draggable: true,
                        dragend: function (e) {
                            var location = e.latLng;
                            $('#et_location_lat').val(location.lat());
                            $('#et_location_lng').val(location.lng());
                            view.model.set('et_location_lat', location.lat());
                            view.model.set('et_location_lng', location.lng());
                        }
                    });
                }
            }, 500);
        },
        /**
         * init map gecode an address
         */
        gecodeMap: function (event) {
            $('#map').slideDown();
            // this.map.refresh();
            //  var address = $(event.currentTarget).val()
            var cp = $("#et_full_location").val();
            var dir = $("#et_full_location2").val();
            if (cp.length < 5) return;
            var address = dir + ", " + cp + ", España",  // para que solo busque en España

                view = this;
            //gmaps = new GMaps
            if (typeof(GMaps) !== 'undefined') {
                GMaps.geocode({
                    address: address,
                    callback: function (results, status) {
                        if (status === 'OK') {
                            var latlng = results[0].geometry.location;
                            $('#et_location_lat').val(latlng.lat());
                            $('#et_location_lng').val(latlng.lng());
                            // set value to model
                            view.model.set('et_location_lng', latlng.lng());
                            view.model.set('et_location_lat', latlng.lat());

                            view.map.setZoom(14);
                            view.map.setCenter(latlng.lat(), latlng.lng());


                            view.map.removeMarkers();
                            view.map.addMarker({
                                lat: latlng.lat(),
                                lng: latlng.lng(),
                                draggable: true,
                                dragend: function (e) {
                                    var location = e.latLng;
                                    $('#et_location_lat').val(location.lat());
                                    $('#et_location_lng').val(location.lng());
                                    view.model.set('et_location_lat', location.lat());
                                    view.model.set('et_location_lng', location.lng());
                                }
                            });

                            /* 12_11_2016 Id (M): 3 S */
                            // rellenamos la provincia si es de españa

                            var country;
                            for (i = 0; i < results[0].address_components.length; i++) {
                                for (j = 0; j < results[0].address_components[i].types.length; j++) {
                                    if (results[0].address_components[i].types[j] == "country")
                                        country = results[0].address_components[i].short_name
                                }
                            }

                            if (country == "ES") {

                                // obtenemos la provincia
                                var provincia;
                                for (i = 0; i < results[0].address_components.length; i++) {
                                    for (j = 0; j < results[0].address_components[i].types.length; j++) {
                                        if (results[0].address_components[i].types[j] == "administrative_area_level_2")
                                            provincia = results[0].address_components[i].short_name
                                    }
                                }

                                //buscamos el VALUE

                                if ($("#location.location_popup ." + provincia.toLowerCase()).val() !== 'undefined') {
                                    var value_provincia = jQuery("#location.location_popup ." + provincia.toLowerCase()).val();

                                    $('#location.location_popup').val(value_provincia);
                                    $('#location.location_popup').trigger("chosen:updated");
                                    $('#location.location_popup').parent().removeClass("error");
                                    $('#location.location_popup').parent().find("div.message").remove();
                                }
                                else {
                                    $('#location.location_popup').val(-1);  //selecciona una opción
                                    $('#location.location_popup').trigger("chosen:updated");
                                }
                            }
                            else {
                                $('#location.location_popup').val(-1);  //selecciona una opción
                                $('#location.location_popup').trigger("chosen:updated");
                            }
                            /* 12_11_2016 Id (M): 3 E */
                        }
                    }
                });
            }
        },
        initValidator: function () {
            /**
             * post form validate
             */
            $("form#submit_form").validate({
                /* Miguel */
                ignore: "",
                rules: {
                    post_title: {
                        required: true,
                        maxlength: 65,
                        minlength: 5,
                        remote: {
                            url: ae_globals.ajaxURL,
                            type: "post",
                            data: {
                                post_title: function () {
                                    return $("#post_title").val();
                                },
                                is_ajax: true,
                                post_id: ($('.postdata').length) ? JSON.parse($('.postdata').html())[0].ID : JSON.parse($('#place_id').html()).ID ,
                                action: 'checkIfExistTitle'
                            }
                        }
                    },
                    et_full_location: {
                        required: true,
                        number: true,
                        maxlength: 5,
                        minlength: 5
                    },
                    place_category: "required",
                    post_content: "required",
                    et_have_image: {
                        required: function () {
                            if (getIfApp == 0) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    location: "required",
                    tipo_reserva: "required",
                    hourly_rate1: {
                        required: {
                            depends: function (element) {
                                return jQuery("input[type='radio']:checked").val() == "horas" || jQuery("input[type='radio']:checked").val() == "servicio"
                            }
                        },
                        number: true
                    },
                    duracion_servicio: {
                        required: {
                            depends: function (element) {
                                return jQuery("input[type='radio']:checked").val() == "servicio"
                            }
                        }
                    },
                    et_phone: {
                        number: true,
                        minlength: 9,
                        maxlength: 9
                    },
                    et_url: {
                        url2: true
                    },
                    et_fb_url: {
                        url2: true
                    },
                    et_google_url: {
                        url2: true
                    },
                    et_twitter_url: {
                        url2: true
                    },
                    alguna_opcion_date_seleccionada: "required"
                },
                errorPlacement: function (label, element) {
                    // position error label after generated textarea
                    if (element.is("textarea")) {
                        $(".message_error_textarea").closest('div.row').append(label);
                    } else {
                        $(element).closest('div.row').append(label);
                    }
                    AE.pubsub.trigger('ae:notification', {
                        msg: ae_globals.error,
                        notice_type: 'error'
                    });
                },
                focusInvalid: true,
                invalidHandler: function (form, validator) {

                    //scrollToErrorDatos($(validator.errorList[0].element));

                }
            });
        },
        // user submit form edit post
        submitPost: function (event) {
            event.preventDefault();
            var view = this,
                temp = new Array();

            /**
             * update serve time
             */
            view.$el.find('ul.date-list li.bdate').each(function () {
                var name = $(this).data('name'),
                    open_time = $(this).attr('open-time'),
                    close_time = $(this).attr('close-time'),
                    open_time_2 = $(this).attr('open-time-2'),
                    close_time_2 = $(this).attr('close-time-2');
                view.model.set('serve_time[' + name + '][open_time]', open_time);

                view.model.set('serve_time[' + name + '][close_time]', close_time);
                view.model.set('serve_time[' + name + '][open_time_2]', open_time_2);
                view.model.set('serve_time[' + name + '][close_time_2]', close_time_2);
            });

            /**
             * update model from input, textarea, select
             */
            view.$el.find('input[type=text],input[type=hidden],textarea,select').each(function () {
                view.model.set($(this).attr('name'), $(this).val());
            });

            view.$el.find('input[type=checkbox]').each(function () {
                var name = $(this).attr('name');
                view.model.set(name, []);
            });
            /**
             * update input check box to model
             */
            view.$el.find('input[type=checkbox]:checked').each(function () {
                var name = $(this).attr('name');
                if (name === "et_claimable_check") {
                    return false;
                }
                if (typeof temp[name] !== 'object') {
                    temp[name] = new Array();
                }
                temp[name].push($(this).val());
                view.model.set(name, temp[name]);
            });
            /**
             * update input radio to model
             */
            view.$el.find('input[type=radio]:checked').each(function () {
                view.model.set($(this).attr('name'), $(this).val());
            });


            /***********************************************************************************************  Miguel *
             * Concatenamos et_full_location con et_full_location2 (si existe et_full_location2)
             */
            if (view.model.get('et_full_location2') != "") {
                view.model.set('et_full_location', view.model.get('et_full_location2') + ", " + view.model.get("et_full_location"));
            }
            else {
                view.model.set('et_full_location', view.model.get("et_full_location"));
            }

            if (getIfApp!=0){ // es app
                view.model.set('isAPP', 1);
            }

            /**
             * save model
             */
            view.model.save('', '', {
                beforeSend: function () {
                    view.loading();
                },
                success: function (result, res) {
                    view.finish();
                    if (res.success) {
                        if (ae_globals.is_single) {
                            window.location.reload();
                        }
                        view.closeModal();
                        view.success(res);
                        view.model.fetch();
                    } else {
                        view.error(res);
                    }
                }
            });
        },
        /**
         * remove cover phone
         */
        removeCover: function (event) {
            event.preventDefault();
            var view = this,
                $target = $(event.currentTarget),
                id = view.model.get('cover_image');
            $.ajax({
                type: 'post',
                url: ae_globals.ajaxURL,
                data: {
                    action: 'ae_remove_carousel',
                    id: id
                },
                beforeSend: function () {
                    view.blockUi.block($target);
                },
                success: function () {
                    view.blockUi.unblock();
                    view.model.set('cover_image', '');
                    view.model.set('cover_image_url', '');
                    view.model.save();
                    view.$('#cover_background').css('background', '');
                    $('#cover_thumb').remove();
                }
            });
        },
        /**
         * change video position on header cover
         */
        updateVideoPosition: function (event) {
            var $target = $(event.currentTarget);
            // preview image
            if ($target.val() === 'left') {
                this.$('.img-preview img').removeClass('right-img-preview').addClass('left-img-preview');
            }
            if ($target.val() === 'right') {
                this.$('.img-preview img').removeClass('left-img-preview').addClass('right-img-preview');
            }
        },
        onCreateCalender: function (model) {
            this.model = model;
            this.openModal();
            // setup fields
            this.setupFields();
            this.initMap();
        },
        /**
         * on edit a model and setup modal data views
         */
        onEdit: function (model) {
            this.model = model;
            // open the modal
            //$('#map').slideUp();
            this.openModal();
            // setup fields
            this.setupFields();
            this.initMap();
            var _this = this;
            // Open Time
            this.$('.open-time').on('change', function () {
                var open = $(this).val();
                _this.inputOpenTime(open, this);
            });
            this.$('.open-time-2').on('change', function () {
                var open = $(this).val();
                _this.inputOpenTime2(open, this);
            });
            // Close Time
            this.$('.close-time').on('change', function () {
                var close = $(this).val();
                _this.inputCloseTime(close, this);
            });
            this.$('.close-time-2').on('change', function () {
                var close = $(this).val();
                _this.inputCloseTime2(close, this);
            });
        },
        /**
         * setup field when open modal edit place
         */
        setupFields: function () {
            var view = this,
                form_field = view.$('.form-field'),
                location = this.model.get('location'),
                cover_image = view.model.get('cover_image_url');
            AE.pubsub.trigger('AE:beforeSetupFields', this.model);

            // bind date picker to opendate
            this.$('.open-time').timepicker({
                'appendTo': '.container-open-time',
                'minTime': '0',
                'maxTime': '14',
                'lang': {'am': ' AM', 'pm': ' PM'},
                'timeFormat': "H:i",
                'step': 60,
                //setTime: '',
                // timeFormat: ae_globals.time_format,
                'noneOption': [{'label': '--', 'value': ''}]
            });
            // bind date picker to opendate
            this.$('.close-time').timepicker({
                'lang': {'am': ' AM', 'pm': ' PM'},
                'appendTo': '.container-close-time',
                'timeFormat': "H:i",
                'minTime': '0',
                'maxTime': '14',
                'step': 60,
                //setTime: '',
                // timeFormat: ae_globals.time_format,
                'noneOption': [{'label': '--', 'value': ''}]
            });

            this.$('#datepair-time-one').datepair();

            $('.open-time-2').timepicker({
                'timeFormat': "H:i",
                'appendTo': '.container-open-time-2',
                'minTime': '14',
                'maxTime': '0',
                'step': 60,
                'lang': {'am': 'AM', 'pm': 'PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });
            $('.close-time-2').timepicker({
                'timeFormat': "H:i",
                'appendTo': '.container-close-time-2',
                'minTime': '14',
                'maxTime': '0',
                'step': 60,
                'lang': {'am': ' AM', 'pm': ' PM'},
                'noneOption': [{'label': '--', 'value': ''}]
            });

            this.$('#datepair-time-two').datepair();
            /**
             * update form value for input, textarea select
             */


            //update claim

            if (view.model.get('et_claimable') == 1) {
                form_field.find('input#et_claimable').prop('checked', true);
            }
            form_field.find('input#et_claimable_value').val(view.model.get('et_claimable') ? 1 : 0);

            form_field.find('input[type="text"],input[type="hidden"], textarea,select').each(function () {
                var $input = $(this);
                var name = $input.attr('name');
                $input.val(view.model.get(name));
                if ($input.get(0).nodeName === "SELECT") {
                    $input.trigger('chosen:updated');

                }
            });
            form_field.find('input[type="radio"]').each(function () {
                var $input = $(this),
                    name = $input.attr('name');
                if ($input.val() == view.model.get(name)) {
                    $input.attr('checked', true);
                }
            });

            form_field.find('input[type="checkbox"]').each(function () {
                var $input = $(this),
                    name = $input.attr('name');
                if ($.inArray(parseInt($input.val()), view.model.get(name)) > -1) {
                    $input.attr('checked', true);
                }
            });

            //abrimos la pestaña .tipo_servicio_hora
            if ($("input[name='tipo_reserva']:checked").val() == 'horas') {
                $('.tipo_servicio_hora').show();
                $(".tipo_servicio_hora .tiempo_servicio").hide()
            }
            if ($("input[name='tipo_reserva']:checked").val() == 'servicio') {
                $('.tipo_servicio_hora').show();
                $(".tipo_servicio_hora .tiempo_servicio").show()
            }


            if (view.model.get('video_position') === 'left') {
                this.$('.img-preview img').removeClass('right-img-preview').addClass('left-img-preview');
            } else {
                this.$('.img-preview img').removeClass('left-img-preview').addClass('right-img-preview');
            }


            /**
             * update cover image view
             */
            if (cover_image) {
                view.$('#cover_background').css('background', 'url(' + cover_image + ') no-repeat center center / cover cadetblue');
            } else {
                view.$('#cover_background').css('background', '#ccc');
            }
            // update value for post content editor
            if (typeof tinyMCE !== 'undefined') {
                // tinymce.EditorManager.execCommand('mceAddEditor', true, "post_content");
                tinymce.EditorManager.get('post_content').setContent(view.model.get('unfiltered_content'));
            }
            if (typeof view.carousels === 'undefined') {
                view.carousels = new Views.Carousel({
                    el: $('#gallery_container'),
                    name_item: 'et_carousel',
                    uploaderID: 'carousel',
                    model: view.model
                });
            } else {
                view.carousels.setModel(view.model);
                view.carousels.setupView();
            }
            var $container = view.$('#cover_container');
            if (typeof view.uploader === 'undefined') {
                view.uploader = new Views.File_Uploader({
                    el: $container,
                    uploaderID: 'cover',
                    thumbsize: 'medium',
                    multipart_params: {
                        _ajax_nonce: $container.find('.et_ajaxnonce').attr('id'),
                        data: 'cover',
                        imgType: 'cover'
                    },
                    cbUploaded: function (up, file, res) {
                        if (res.success) {
                            $('#' + this.container).parents('.desc').find('.error').remove();
                        } else {
                            $('#' + this.container).parents('.desc').append('<div class="error">' + res.msg + '</div>');
                        }
                    },
                    beforeSend: view.cbBeforeSend,
                    success: view.cbSuccess
                });
            }

            // SERVE TIME
            var serve_time = view.model.get('serve_time');
            if (!$.isEmptyObject(serve_time)) {
                form_field.find('ul.date-list li.bdate').each(function () {
                    var name = $(this).data('name');
                    object = serve_time[name],
                        open_time = object.open_time,
                        close_time = object.close_time;

                    open_time_2 = object.open_time_2,
                        close_time_2 = object.close_time_2;


                    if (open_time)
                        form_field.find('input.open-time').val(open_time);
                    if (close_time)
                        form_field.find('input.close-time').val(close_time);
                    if (open_time_2)
                        form_field.find('input.open-time-2').val(open_time_2);
                    if (close_time_2)
                        form_field.find('input.close-time-2').val(close_time_2);

                    if ((open_time != "" && close_time != "") || (open_time_2 != "" && close_time_2 != "")) {
                        $(this).addClass('active');
                        $(this).attr('open-time', open_time);
                        $(this).attr('close-time', close_time);
                        $(this).attr('open-time-2', open_time_2);
                        $(this).attr('close-time-2', close_time_2);

                        $("#alguna_opcion_date_seleccionada").val(1);
                    }
                });
            } else {
                form_field.find('ul.date-list li.bdate').each(function () {
                    $(this).removeClass('vbdate');
                    $(this).next().removeClass('nbdate');
                    $(this).attr('data-original-title', "");
                    $(this).attr('open-time', "");
                    $(this).attr('close-time', "");

                    $(this).attr('open-time-2', "");
                    $(this).attr('close-time-2', "");
                });
            }
            //this.resetUploader();

            // miguel, ponemos el CP y la Calle (Dirección)
            var et_full_location = view.model.get('et_full_location');
            if (et_full_location.length > 0) {
                var direccion = et_full_location;
                if (direccion.match(/\d{5}/)) { // hay CP
                    var cp = direccion.match(/\d{5}/)[0];
                    if (cp.length > 0) {
                        this.$('#et_full_location').val(cp);

                        var dash = direccion.indexOf(', ');
                        if (dash > 0) {  // hay dirección
                            var calle = direccion.substring(0, dash);
                            this.$('#et_full_location2').val(calle);
                        }
                    }
                }
            }

            // miguel, si tenemos et_have_car lo ponemos
            if (view.model.get('et_have_car').length > 0) {
                var cadena = view.model.get('et_have_car');
                jQuery('#et_have_car_sel').val(cadena).trigger('chosen:updated');

            }

            if ($('#DOPBSP-calendar-ID').val() != "") {
                var calendario = $('#DOPBSP-calendar-ID').val();
                view.model.set('DOPBSP-calendar-ID', calendario);
                this.$('#DOPBSP-calendar-ID').val(calendario);

            }


            AE.pubsub.trigger('AE:afterSetupFields', this.model);
        },
        resetUploader: function () {
            if (typeof this.uploader === 'undefined') {
                return;
            }
            this.uploader.controller.splice();
            this.uploader.controller.refresh();
            this.uploader.controller.destroy();
        },
        cbSuccess: function (res) {
            var view = this;
            view.blockUi.unblock();
            view.model.set('cover_image', res.data.attach_id);
            view.model.set('cover_image_url', res.data.full[0]);
            view.$('#cover_background').css('background', 'url(' + res.data.full[0] + ') no-repeat center center / cover cadetblue');
            view.model.set('uploadingCarousel', false);
        },
        cbBeforeSend: function (ele) {
            var view = this;
            button = $(ele).find('.image');
            view.blockUi.block(button);
            view.model.set('uploadingCarousel', true);
        }
    });
    /**
     * modal control
     */
    Views.SearchForm = Backbone.View.extend({
        el: '#header-wrapper',
        events: {
            //'click .search-btn': 'triggerSearchForm',
            'click .top-menu-center li': 'hideTopActive',
            'click .top-user': 'hideTopActive',
            'click .top-active': 'triggerTopActive'
        },
        //init search form view
        initialize: function () {
            _.bindAll(this, 'showMap', 'errorHandle');
            var view = this;
            this.$('.slider-ranger').on('slide', function (ev) {
                var value = ev.value;
                $('#' + $(this).attr('data-name')).val(value);
                $('.' + $(this).attr('data-name')).html(value);
            });
            this.$('.nearby').on('slideStart', function () {
                navigator.geolocation.getCurrentPosition(view.showMap, view.errorHandle);
            });
            this.$('form').validate();
        },

        showMap: function (position) {
            geolocalizacion(0, 0);
        },

        errorHandle: function () {
            alert("Ha rechazado la solicitud de Geolocalización. Para utilizar esta función, habilítela.");
        },

        hideSearchForm: function () {
            $btn_topsearch = $('ul.top-menu-right li.top-search');
            $marsk = $('.marsk-black');
            $option_search = $('.option-search-form-wrapper');

            $btn_topsearch.removeClass('active');
            $option_search.hide();
            $marsk.fadeOut();
        },
        /**
         * slide down search form
         */
        triggerSearchForm: function () {
            // HEADER TOP OPTION SEARCH
            $option_search = $('.option-search-form-wrapper');
            $marsk = $('.marsk-black');
            $btn_topsearch = $('ul.top-menu-right li.top-search');
            // toggle search form
            $marsk.fadeToggle(300);
            $btn_topsearch.toggleClass('active');
            $option_search.slideToggle(300, 'easeInOutSine', function () {
                $('.slider-ranger').slider({
                    tooltip: 'always'
                });
            });
        },
        /**
         *   show top menu content
         */
        triggerTopActive: function (e) {
            var _this = this;
            var ev = e.currentTarget;
            _this.hideTopActive(e);
            var attr = $(ev).attr('data-name');
            $(ev).toggleClass('active');
            $marsk = $('.marsk-black');
            $marsk.fadeToggle(300);
            if (attr == 'search') {
                $option_search = $('.option-search-form-wrapper');
                $option_search.slideToggle(300, 'easeInOutSine', function () {
                    $('.slider-ranger').slider({
                        tooltip: 'always'
                    });
                });
            } else {
                $option_active = $('.option-contact-' + attr);
                $option_active.slideToggle(300, 'easeInOutSine');
            }
        },
        /**
         *   hide top active
         */
        hideTopActive: function (e) {
            var ev = e.currentTarget;
            var attr = $(ev).attr('data-name');
            $('li.top-active').each(function (index) {
                var _attr = $(this).attr('data-name');
                if (_attr != attr && $(this).hasClass('active')) {
                    $(this).removeClass('active');
                    $option_active = $('.option-contact-' + _attr);
                    $option_active.hide();
                    $marsk = $('.marsk-black');
                    $marsk.fadeOut();
                }
            });
        }
    });
    /**
     * front-end control
     */
    Views.Front = Backbone.View.extend({
        el: 'body',
        model: [],
        events: {
            'click a.authenticate': 'openAuthModal',
            'click a.contact-owner': 'openContactModal',
            // open modal sign up
            'click a.page_link_sign_up': 'openSingup',
            // open modal forgot
            'click a.page_link_forgot_pass': 'openForgot',
            // open modal sign in
            'click a.page_link_sign_in': 'openSingin',
            'submit form.signin_form': 'doLogin',
            // user register
            'submit form.signup_form': 'doRegister',
            // user forgot pass
            'submit form.forgotpass_form': 'doSendPassword',
            // Resend activation code via email
            'click a.resend-activation-code': 'resendActivationCode',
            // close activation notification
            'click a.activation-notification-close': 'closeActivationMessage',
            'click #location_chosen': 'changeIcon'
        },
        initialize: function (options) {
            _.bindAll(this, 'editPost', 'updateAuthButtons', 'createEvent', 'rejectPost', 'handleLogout', 'loadNearby');
            this.blockUi = new Views.BlockUi();
            var view = this;
            this.options = _.extend(this, options);
            // console.log('init Views.Front');
            if (typeof $.validator !== 'undefined') {
                $.validator.setDefaults({
                    // prevent the form to submit automatically by this plugin
                    // so we need to apply handler manually
                    onsubmit: true,
                    onfocusout: function (element, event) {
                        if (!this.checkable(element) && element.tagName.toLowerCase() === 'textarea') {
                            this.element(element);
                        } else if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
                            this.element(element);
                        }
                    },
                    validClass: "valid", // the classname for a valid element container
                    errorClass: "message", // the classname for the error message for any invalid element
                    errorElement: 'div', // the tagname for the error message append to an invalid element container
                    // append the error message to the element container
                    errorPlacement: function (error, element) {
                        $(element).closest('div').append(error);
                    },
                    // error is detected, addClass 'error' to the container, remove validClass, add custom icon to the element
                    highlight: function (element, errorClass, validClass) {
                        var $container = $(element).closest('div');
                        if (!$container.hasClass('error')) {
                            //$container.addClass('error').removeClass(validClass).append('<i class="fa fa-exclamation-triangle" ></i>'); /* miguel. Quitado para que no aparezca el triangulo*/
                            $container.addClass('error').removeClass(validClass);
                        }
                    },
                    // remove error when the element is valid, remove class error & add validClass to the container
                    // remove the error message & the custom error icon in the element
                    unhighlight: function (element, errorClass, validClass) {
                        var $container = $(element).closest('div');
                        if ($container.hasClass('error')) {
                            $container.removeClass('error').addClass(validClass);
                        }
                        $container.find('div.message').remove().end().find('i.fa').remove();
                    }
                });
            }
            this.noti_templates = new _.template('<div class="notification autohide {{= type }}-bg">' + '<div class="main-center">' + '{{= msg }}' + '</div>' + '</div>');
            this.model = new Models.User();
            // catch event edit model
            AE.pubsub.on('ae:model:onEdit', this.editPost, this);
            AE.pubsub.on('ae:model:onReject', this.rejectPost, this);
            AE.pubsub.on('ae:model:onCreateCalender', this.createCalender, this);
            AE.pubsub.on('ae:model:onCreateEvent', this.createEvent, this);
            // catch event nofificationsºº
            AE.pubsub.on('ae:notification', this.showNotice, this);
            // event handler for when receiving response from server after requesting logout
            AE.pubsub.on('ae:user:logout', this.handleLogout, this);
            // event handler for when receiving response from server after requesting login/register
            AE.pubsub.on('ae:user:auth', this.handleAuth, this);
            // // render button in header
            this.user.on('change:ID', this.updateAuthButtons);
            /**
             * bind chosen to select
             */

            this.$('.chosen-single').chosen({
                width: '270px',
                max_selected_options: 1
            });

            /* 18_11_2016 Id : 1 S*/
            this.$('.chosen-single-category').dropsingle();
            this.$('.chosen-single_category').dropsingle();
            this.$('.chosen-single_category2').dropsingle(); // este es el filtro de categorias en search.php
            this.$('.chosen-single_category2').click();  // esto es para corregir el fallo
            /* 18_11_2016 Id : 1 E*/

            /* 09_11_2016 Id : 1 S*/
            this.$('.multi-tax-item').drop();  // esto carga las categorias multi-tax en el modal para editar anuncio
            $(".search-category input").focus(function() {
                $(this).blur();  // al hacer click en el select/input de categorias, le quitamos el focus (con blur) para que no se despliegue el teclado
            });

            /* 09_11_2016 Id : 1 E*/
            this.$('.chosen-multi').chosen({
                width: '95%',
                //max_selected_options: 2,
                inherit_select_classes: true
            });


            // this.$(".chosen-select").each(function(){
            //     var data_chosen_width = $(this).attr('data-chosen-width'),
            //         data_chosen_disable_search = $(this).attr('data-chosen-disable-search');
            //     $(this).chosen({width: data_chosen_width, disable_search: data_chosen_disable_search });
            // });

            /**
             * deslect when selected all date
             */
            $('select[name="serve_day"]').on('change', function (event, params) {
                var $container = $(this).closest('div').find('.chosen-drop');
                if (typeof params.selected !== 'undefined' && parseInt(params.selected) === 0) {
                    $container.hide();
                }
                if (typeof params.deselected !== 'undefined' && parseInt(params.deselected) === 0) {
                    $container.show();
                }
            });
            /**
             * unhighlight chosen
             */
            $('select.chosen, select.chosen-single').on('change', function (event, params) {
                if (typeof params.selected !== 'undefined') {
                    var $container = $(this).closest('div');
                    if ($container.hasClass('error')) {
                        $container.removeClass('error');
                    }
                    $container.find('div.message').remove().end().find('i.fa').remove();
                }
            });
            $('.rate-it').raty({
                readOnly: true,
                half: true,
                score: function () {
                    return $(this).attr('data-score');
                },
                hints: raty.hint
            });
            $('.rating-it').raty({
                half: true,
                hints: raty.hint
            });
            $('.fancybox').magnificPopup({
                type: 'image',
                gallery: {
                    enabled: true
                },
                // other options
            });
            this.search_form = new Views.SearchForm();
        },
        /**
         * load nearby list after catch event share position
         *
         * @since version 1.8.4
         */
        loadNearby: function (coords) {
            $('.nearby-block').each(function () {
                var view = this,
                    thisContent = this;
                $(this).find('.first_text').html('');
                PostItem = Views.PostItem.extend({
                    template: _.template($('#ae-place-nearby-loop').html()),
                    onItemBeforeRender: function () {

                    },
                    onItemRendered: function () {
                        var view = this;
                        st = $(thisContent).find('input[name="style"]').val();
                        if (typeof st !== 'undefined' && st === 'vertical') {
                            view.$el.removeClass('col-md-3 col-xs-6');
                        }
                        view.$el.find('.content-place').remove();
                        view.$('.rate-it').raty({
                            half: true,
                            score: view.model.get('rating_score'),
                            readOnly: true,
                            hints: raty.hint
                        });
                        if (parseInt(ae_globals.geolocation) === 1) {
                            var location_lat, location_lng;
                            if (navigator.geolocation) {
                                //alert('33');
                                navigator.geolocation.getCurrentPosition(function (position) {
                                    // Get GeoLocation of device
                                    location_lat = position.coords.latitude;
                                    location_lng = position.coords.longitude;

                                    lat_item = view.model.get('et_location_lat');
                                    lng_item = view.model.get('et_location_lng');
                                    var dist = distance(lat_item, lng_item, location_lat, location_lng);
                                    view.$el.find('.distance').text(dist + ' -');
                                });
                            }
                        }
                    }
                });
                view.nearbyCollection = new Collections.Posts();
                nearbyList = Views.ListPost.extend({
                    tagName: 'ul',
                    itemView: PostItem,
                    itemClass: 'place-item'
                });
                new nearbyList({
                    itemView: PostItem,
                    collection: view.nearbyCollection,
                    el: $(this).find('ul')
                });
                new Views.BlockControl({
                    collection: view.nearbyCollection,
                    el: $(this),
                });
                var data = {
                    query: {
                        post_type: 'place',
                        paginate: 'page',
                        showposts: $(this).find('input[name="showposts"]').val(),
                        radius: $(this).find('input[name="radius"]').val(),
                        near_lat: coords.coords.latitude,
                        near_lng: coords.coords.longitude
                    },
                    paginate: 'page'
                };
                view.nearbyCollection.fetch({data: data});
            });
        },
        errorloadNearby: function () {

        },
        /*
         * Show notification
         */
        showNotice: function (params) {
            var view = this;
            // remove existing notification
            $('div.notification').remove();
            var notification = $(view.noti_templates({
                msg: params.msg,
                type: params.notice_type
            }));
            if ($('#wpadminbar').length !== 0) {
                notification.addClass('having-adminbar');
            }
            notification.hide().prependTo('body').fadeIn('fast').delay(3000).fadeOut(3000, function () {
                $(this).remove();
            });
        },
        handleAuth: function (model, resp) {
            // check if authentication is successful or not
            if (resp.success) {
                AE.pubsub.trigger('ae:notification', {
                    msg: resp.msg,
                    notice_type: 'success'
                });
                var data = resp.data;
                if (data.role === "administrator") {
                    $('.field-claimable').show();
                }
                // if this is not job posting page, reload
                if (!ae_globals.is_submit_post) {
                    window.location.reload();
                }
                if (!ae_globals.user_confirm) {
                    this.model.set(resp.data);
                }
                if (typeof data.redirect_url !== 'undefined') {
                    window.location.href = data.redirect_url;
                }
            } else {
                AE.pubsub.trigger('ae:notification', {
                    msg: resp.msg,
                    notice_type: 'error'
                });
            }
        },
        handleLogout: function (data) {
            // clear the currentUser model
            // this also trigger the "change" event of this model
            this.model.clear();
            // trigger notification on the top
            AE.pubsub.trigger('ae:notification', {
                msg: data.msg,
                notice_type: 'success'
            });
            if (!ae_globals.is_submit_post) {
                window.location.href = ae_globals.homeURL;
            }
        },
        // update header profile button
        updateAuthButtons: function (model) {
            if ($('#header_login_template').length > 0) {
                if ($('.top-user').length > 0) {
                    return;
                }
                this.$('.non-login').remove();
                var header_template = _.template($('#header_login_template').html());
                this.$('.top-menu-right').append(header_template(model.attributes));
            }
        },
        /**
         * setup model for modal edit post and trigger event open the modal EditPost
         */
        editPost: function (model) {
            if (model.get('post_type') === 'place') {
                if (typeof this.editModal === 'undefined') {
                    this.editModal = new Views.EditPost({
                        el: $('#edit_' + model.get('post_type'))
                    });
                }
                this.editModal.onEdit(model);
            }
            if (model.get('post_type') === 'event') {
                if (typeof this.createEventModal === 'undefined') {
                    this.createEventModal = new Views.CreateEvent({
                        el: $('#create_event')
                    });
                }
                this.createEventModal.onEditEvent(model);
            }
        },
        /**
         * setup model for modal create event and trigger event open modal create event
         */
        createEvent: function (model) {
            if (typeof this.createEventModal === 'undefined') {
                this.createEventModal = new Views.CreateEvent({
                    el: $('#create_event')
                });
            }
            this.createEventModal.onCreateEvent(model);
        },
        createCalender: function (model) {
            if (typeof this.createCalenderModal === 'undefined') {
                this.createCalenderModal = new Views.CreateCalender({
                    el: $('#create_calender')
                });
            }
            //console.debug(model);
            //alert(model);
            this.createCalenderModal.onCreateCalender(model);
        },
        /**
         * setup reject post modal and trigger event open modal reject
         */
        rejectPost: function (model) {
            if (typeof this.rejectModal === 'undefined') {
                this.rejectModal = new Views.RejectPostModal({
                    el: $('#reject_post')
                });
            }
            this.rejectModal.onReject(model);
        },
        /**
         * setup element for modal register
         */
        openAuthModal: function (event) {
            event.preventDefault();
            this.authModal = new Views.AuthModal({
                el: $('#login_register'),
                model: this.user
            });
            this.authModal.openModal();
        },
        /**
         * Open Contact Modal
         */
        openContactModal: function (event) {
            event.preventDefault();
            var $target = $(event.currentTarget);
            if (typeof this.editContactmodal === 'undefined') {
                this.editContactmodal = new Views.ContactModal({
                    el: $("#contact_message"),
                    model: this.user,
                    user_id: $target.attr('data-user')
                });
            }
            this.editContactmodal.user_id = $target.attr('data-user');
            this.editContactmodal.openModal();
        },

        openSingup: function (event) {
            event.preventDefault();
            $('#page_signin_form').fadeOut("slow", function () {
                $(this).css({
                    'z-index': 1
                });
                $('.modal-title-sign-in').empty().text(de_front.texts.sign_up);
                $('#page_signup_form').fadeIn(500).css({
                    'z-index': 2
                });
            });
        },
        /**
         *  open Modal Forgot
         */
        openForgot: function (event) {
            event.preventDefault();
            $('#page_signin_form').fadeOut("slow", function () {
                $(this).css({
                    'z-index': 1
                });
                $('.modal-title-sign-in').empty().text(de_front.texts.forgotpass);
                $('#page_forgotpass_form').fadeIn(500).css({
                    'z-index': 2
                });
            });
        },
        /**
         *  open Modal Sign In
         */
        openSingin: function (event) {
            event.preventDefault();
            $('#page_signup_form').fadeOut("slow", function () {
                $(this).css({
                    'z-index': 1
                });
                $('.modal-title-sign-in').empty().text(de_front.texts.sign_in);
                $('#page_forgotpass_form').fadeOut(500).css({
                    'z-index': 2
                });
                $('#page_signin_form').fadeIn(500).css({
                    'z-index': 2
                });
            });
        },
        /**
         * init form validator rules
         * can override this function by using prototype
         */
        initValidator: function () {
            // login rule
            this.login_validator = $("form.signin_form").validate({
                rules: {
                    user_login: "required",
                    user_pass: "required"
                }
            });
            /**
             * register rule
             */
            this.register_validator = $("form.signup_form").validate({  //este validador es de page-login.php
                ignore: ".ignore",
                rules: {
                    user_login: "required",
                    user_pass: "required",
                    user_email: {
                        required: true,
                        email: true
                    },
                    re_password: {
                        required: true,
                        equalTo: "#reg_pass"
                    },
                    recaptcha_register_1: "required"
                }
            });
            /**
             * forgot pass email rule
             */
            this.forgot_validator = $("form.forgotpass_form").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                }
            });
        },
        /**
         * user login,catch event when user submit login form
         */
        doLogin: function (event) {
            event.preventDefault();
            event.stopPropagation();
            /**
             * call validator init
             */
            this.initValidator();
            var form = $(event.currentTarget),
                button = form.find('input.btn-submit'),
                view = this;
            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });
            // check form validate and process sign-in
            if (this.login_validator.form() && !form.hasClass("processing")) {
                this.user.set('do', 'login');
                this.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        view.closeModal();
                    }
                });
            }
        },
        /**
         * user sign-up catch event when user submit form signup
         */
        doRegister: function (event) {
            event.preventDefault();
            event.stopPropagation();
            /**
             * call validator init
             */
            this.initValidator();
            var form = $(event.currentTarget),
                button = form.find('input.btn-submit'),
                view = this;
            /**
             * scan all fields in form and set the value to model user
             */
            form.find('input, textarea, select').each(function () {
                view.user.set($(this).attr('name'), $(this).val());
            });
            // check form validate and process sign-up
            if (this.register_validator.form() && !form.hasClass("processing")) {
                this.user.set('do', 'register');
                this.user.request('create', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status, jqXHR) {
                        view.blockUi.unblock();
                        form.removeClass('processing');
                        // trigger event process authentication
                        AE.pubsub.trigger('ae:user:auth', user, status, jqXHR);
                        view.closeModal();
                    }
                });
            }
        },
        /**
         * user forgot password
         */
        doSendPassword: function (event) {
            event.preventDefault();
            event.stopPropagation();
            /**
             * call validator init
             */
            this.initValidator();
            var form = $(event.currentTarget),
                email = form.find('input.email').val(),
                button = form.find('input.btn-submit'),
                view = this;
            if (this.forgot_validator.form() && !form.hasClass("processing")) {
                this.user.set('user_login', email);
                this.user.set('do', 'forgot');
                this.user.request('read', {
                    beforeSend: function () {
                        view.blockUi.block(button);
                        form.addClass('processing');
                    },
                    success: function (user, status) {
                        form.removeClass('processing');
                        view.blockUi.unblock();
                        if (status.success) {
                            view.closeModal();
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'success'
                            });
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: status.msg,
                                notice_type: 'error'
                            });
                        }
                    }
                });
            }
        },
        resendActivationCode: function () {
            var view = this;
            if ($('#user_id').length > 0) {
                view.user = new Models.User(JSON.parse($('#user_id').html()));
            } else {
                view.user = new Models.User();
            }
            view.user.confirmMail({
                beforeSend: function () {
                    view.blockUi.block($('.activation-notification'));
                },
                success: function (user, status) {
                    if (status.success) {
                        AE.pubsub.trigger('ae:notification', {
                            msg: status.msg,
                            notice_type: 'success',
                        });
                        view.closeActivationMessage();
                        view.blockUi.unblock();
                    } else {
                        AE.pubsub.trigger('ae:notification', {
                            msg: status.msg,
                            notice_type: 'error',
                        });
                    }
                }
            });
        },
        closeActivationMessage: function () {
            $('.activation-notification').fadeOut(300);
        },
        changeIcon: function (event) {
            var $target = $(event.currentTarget);
            if ($target.hasClass('chosen-with-drop')) {
                $target.find('.chosen-single div b').attr('class', "fa fa-caret-up");
            }
            else {
                $target.find('.chosen-single div b').attr('class', "fa fa-caret-down");
            }
        }
    });


    //fucntion to check if IS JSON
    function IsJsonString(str) {
        try {
            JSON.parse(str);

        } catch (e) {
            return false;
        }
        return true;
    }

    //MENU HEADER FIXED SETTING
    var previousScroll = 0;
    $(window).scroll(function () {
        $el = $('#header-wrapper');
        var hscroll = $el.height();
        var currentScroll = $(this).scrollTop();
        if (currentScroll > previousScroll) {
            $el.removeClass('sticky-scroll');
            $el.css({'top': -hscroll + 'px'});
        } else {
            $el.addClass('sticky-scroll');
            $el.css({'top': '0'});
        }
        previousScroll = currentScroll;
    });
    function header_menu() {
        $el = $('#header-wrapper');
        $holder = $('#sticky-holder');
        if ($el.length) {
            // event.preventDefault();
            $el.offset().top;
            if ($(window).scrollTop() > $el.height() && $el.css('position') !== 'fixed') {
                $el.css({
                    'position': 'fixed',
                    'top': '0px',
                    'opacity': '0'
                }).animate({
                    opacity: 1
                }, 500);
                $el.addClass("sticky");
                $holder.css({
                    height: $el.height(),
                    display: 'block'
                });
            }
            if ($(window).scrollTop() < $el.height() && $el.css('position') === 'fixed') {
                $el.css({
                    'position': 'relative',
                    'top': '0px'
                });
                $el.removeClass("sticky");
                $holder.hide();
            }
        }
    }

    $(window).scroll(function () {
        header_menu();
    });
    $(window).load(function () {
        header_menu();
    });
    $(document).bind("scrollstart", function () {
        header_menu();
    });
    //GOOGLE MENU
    if ($('#gmenu-main').length > 0) {
        new gnMenu(document.getElementById('gn-menu'));
        $gmenu_hover = $('#gmenu-main .gn-menu li.menu-item-has-children');
        $gmenu_hover.hover(function () {
            if ($(this).find("ul.gn-submenu").length > 0) {
                $('#gmenu-main').toggleClass('active');
            }
        });
    }


    // Custom Navigation Events
    $(".next-testi").click(function () {
        owl.trigger('owl.next');
    });
    $(".prev-testi").click(function () {
        owl.trigger('owl.prev');
    });
    $(window).load(function () {
        $('.mask-color').fadeOut('slow');
    });
    $('.menu-btn').hover(function () {
        $btn_topsearch = $('ul.top-menu-right li.top-search');
        $marsk = $('.marsk-black');
        $option_search = $('.option-search-form-wrapper');

        $btn_topsearch.removeClass('active');
        $option_search.hide();
        $marsk.fadeOut();
    });


    function distance(lat1, lon1, lat2, lon2) {
        var radlat1 = Math.PI * lat1 / 180;
        var radlat2 = Math.PI * lat2 / 180;
        var theta = lon1 - lon2;
        var radtheta = Math.PI * theta / 180;
        var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
        dist = Math.acos(dist);
        dist = dist * 180 / Math.PI;
        // don vi dam
        dist = dist * 60 * 1.1515;
        var unit = ae_globals.units_of_measurement;
        if (unit === 'km') {
            // If distance smaller than 1 Km => convert unit to Metre
            if (dist < 1) {
                dist = Math.ceil(dist * 1000) + ' m';
            } else {
                // Convert unit to Kilometer
                // 1 Mi = 1.609 Km
                dist = Math.ceil(dist * 1.609) + ' Km';
            }
        } else {
            // Convert unit to Miles
            dist = Math.ceil(dist) + ' Mi';
        }
        return dist;
    }


    $(document).ready(function () {

        var h_header = $('#header-wrapper').height();
        $('#sticky-holder').css({'height': h_header + 'px'})
        // resize menu
        var W_width = $(window).width();
        var E_width = $('.top-menu-center').width();
        var R_width = W_width - (E_width + 650);
        if (R_width < 0) {
            $('.top-menu-center').addClass('top-menu-center-resp');
        }
        window.addEventListener('resize', function () {
            var W_width = $(window).width();
            // var E_width = $('.top-menu-center').width();
            var R_width = W_width - (E_width + 650);
            if (R_width < 0) {
                $('.top-menu-center').addClass('top-menu-center-resp');
            } else {
                $('.top-menu-center').removeClass('top-menu-center-resp');
            }
        });
        $('.bar-menu').on('click', function () {
            $('#menu-header-top').slideToggle('slow');
        });
        $('[data-toggle="tooltip"]').tooltip();

        $('#menu-header-top > li').each(function (index) {
            $(this).find('.dropdown-menu').each(function (index) {
                var mega_ul = $(this);
                var mega_length = $(this).children().length;
                var mega_menu = $(this).children();
                if (mega_length > 0) {
                    $(mega_ul).wrapAll('<div class="mega-wrapper"></div>').wrapAll('<div class="mega-menu"></div>');
                    var loop = Math.ceil(mega_length / 5);
                    for (var i = 0; i < loop; i++) {
                        $(mega_menu).slice(i * 5, (i + 1) * 5).wrapAll('<ul class="mega-list"></ul>').parent().insertBefore(mega_ul);
                    }
                    ;
                    $(mega_ul)
                }
                $(this).remove();
            });
        });
        $('.arrow-submenu').on('click', function (event) {
            var _this = $(this).parent()
            var hParent = $(_this).parent().hasClass('menu-header-top-desk');
            var sCurrent = $(_this).hasClass('select');
            /*if(hParent && !sCurrent) {
             $('.menu-item').removeClass('select');
             $('.arrow-submenu').removeClass('active');
             $('.mega-menu').fadeOut(500);
             }*/
            $(this).toggleClass('active');
            var W_width = $(window).width();
            $(this).parent().toggleClass('select');
            var p_menu_width = $(this).parent().width();
            var p_menu_left = $(this).parent().offset().left;
            var p_menu_right = W_width - (p_menu_width + p_menu_left);
            var menu_wrap = $(this).next();
            // console.log($(menu_wrap));
            var menu = $(this).next().children('.mega-menu');
            // $(menu).css({'display': 'block'});
            if (hParent) {
                if (sCurrent) {
                    $(_this).removeClass('select');
                    $(_this).find('.mega-menu').fadeOut(300);
                } else {
                    $(_this).siblings().removeClass('select');
                    $(_this).siblings().find('.mega-menu').fadeOut(300);
                    $(menu).slideToggle(300);
                }
            } else {
                console.log("test");
                // $('.menu-item').removeClass('select');
                // $('.arrow-submenu').removeClass('active');
                // $('.mega-menu').fadeOut(500);
                $(menu).slideToggle(300);
            }


            var left = $(menu).offset().left;
            var w_mega = $(menu).width();
            var right = W_width - (left + w_mega);

            if (p_menu_left > p_menu_right) {
                if (right < 0) {
                    $(menu_wrap).css({'right': '-20px', 'left': 'inherit'});
                    $(menu).css({'right': '0px'});
                    left = $(menu).offset().left;
                    if (left < 0) {
                        while (left < 0) {
                            w_mega -= 248;
                            $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                            left = $(menu).offset().left;
                        }
                    }
                }
            } else {
                if (right < 0) {
                    $(menu_wrap).css({'left': '-20px', 'right': 'inherit'});
                    $(menu).css({'left': '0px'});
                    left = $(menu).offset().left;
                    while (right < 0) {
                        w_mega -= 24;
                        $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                        right = W_width - (left + w_mega);
                    }
                }
            }

            left = $(menu).offset().left;
            if (left < 0) {
                while (left < 0) {
                    w_mega -= 249;
                    $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                    left = $(menu).offset().left;
                }
            }
            if (right < 0 && p_menu_left > p_menu_right) {
                $(menu_wrap).css({'right': '-20px', 'left': 'inherit'});
                $(menu).css({'right': '0px'});
                left = $(menu).offset().left;
                if (left < 0) {
                    while (left < 0) {
                        w_mega -= 249;
                        $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                        left = $(menu).offset().left;
                    }
                }
            }
            if (right < 0 && p_menu_left < p_menu_right) {
                $(menu_wrap).css({'left': '-20px', 'right': 'inherit'});
                $(menu).css({'left': '0px'});
                while (right < 0) {
                    w_mega -= 249;
                    $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                    right = W_width - (left + w_mega);
                }
            }
            left = $(menu).offset().left;
            if (left < 0) {
                while (left < 0) {
                    w_mega -= 249;
                    $(menu).css({'width': w_mega + 'px', 'white-space': 'normal'});
                    left = $(menu).offset().left;
                }
            }

        });

        $(document.body).on('click', function (event) {
            if (!$(event.target).closest('.select').length) {
                // $('.ui-report-ad').fadeOut(500);
                $('.menu-item').removeClass('select');
                $('.arrow-submenu').removeClass('active');
                $('.mega-menu').fadeOut(500);
            }
        });


        // NOTIFICATION
        $('.btn-pending-places').on('click', function () {
            var check = $('.noti-pending-places-wrap').is(":hidden");
            if (check) {
                $('body').css('overflow', 'hidden');
            } else {
                $('body').css('overflow', 'visible');
            }

            $('.noti-pending-places-wrap').slideToggle(1000, function () {
                if (check == true) {

                    $('.noti-marsk-black').fadeIn();
                } else {

                    $('.noti-marsk-black').fadeOut();
                }
            });
        });

        $('.noti-marsk-black').on('click', function () {
            $('body').css('overflow', 'visible');
            $('.noti-marsk-black').fadeOut();
            $('.noti-pending-places-wrap').slideUp(1000);
        });

        $('.notification-hide').on('click', function () {
            $('body').css('overflow', 'visible');
            $('.noti-marsk-black').fadeOut();
            $('.notification-places, .noti-pending-places-wrap').slideUp(1000);
            // Set cookie when visibla Notification
            $.cookie('view-notification', '0');
        });
        // Check user logged
        if (ae_globals.user_ID != 0 && $('#ae-place-notification').length > 0) {
            NotificationItem = Views.PostItem.extend({
                template: _.template($('#ae-place-notification').html()),
                className: 'pending-item',
                onBeforeApprove: function (view, res) {
                    if (res.success) {
                        view.el.remove();
                        var number = $('.btn-pending-places number').text() - 1;

                        $('.btn-pending-places number').text(number);
                    }
                },
                onBeforeApprove: function (view, res) {
                    if (res.success) {
                        AE.pubsub.trigger('ae:notification', {
                            msg: res.msg,
                            notice_type: 'success'
                        });
                    }
                }
            });

            ListNotification = Views.ListPost.extend({
                tagName: 'ul',
                itemView: NotificationItem,
                itemClass: 'pending-item'
            });
            $('.noti-pending-places-wrap').each(function () {
                if ($(this).find('.postdata').length > 0) {
                    var postdata = JSON.parse($(this).find('.postdata').html()),
                        collection = new Collections.Posts(postdata);

                    var loading_html = '<li class="loading-active">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="loading" class="load-place load-place-active" x="0px" y="0px" viewBox="0 0 150 150">' +
                        '<circle id="loading-circle" class="loading-inner loading-inner-active" cx="75" cy="75" r="60"/>' +
                        '<polyline id="loading-check" class="loading-check" fill="none" points="52,63 75,86 101,52" />' +
                        '</svg>' +
                        '</li>';

                    new ListNotification({
                        itemView: NotificationItem,
                        collection: collection,
                        el: $(this).find('ul')
                    });

                    var BlockControlNotification = new Views.BlockControl({
                        collection: collection,
                        el: $(this).find('ul'),
                        onAfterInit: function () {
                            var view = this;
                            setInterval(function () {
                                if ($('.list-pending-places .pending-item').length < 6 && $('.loading-active').length == 0) {
                                    $('.list-pending-places').append(loading_html);
                                    view.loadMore();
                                }
                            }, 3000);
                            $('.noti-pending-places').mCustomScrollbar({
                                callbacks: {
                                    onTotalScroll: function () {
                                        if ($('.loading-active').length == 0 && this.mcs.topPct) {
                                            $('.list-pending-places').append(loading_html);
                                            view.loadMore();
                                        }
                                    }
                                }
                            });
                        },
                        loadMore: function () {
                            var view = this;
                            view.page++;
                            view.query.paged++;
                            // collection fetch
                            view.collection.fetch({
                                remove: false,
                                data: {
                                    query: view.query,
                                    page: view.page,
                                    paged: view.page,
                                    action: 'ae-fetch-posts',
                                    paginate: true,
                                    thumbnail: view.thumbnail,
                                },
                                // get the thumbnail size of post and send to server
                                thumbnail: view.thumbnail,
                                beforeSend: function () {
                                    $('.loading-active').show();
                                    if ($("#loading").length > 0) {
                                        $("#loading").attr('class', 'load-place');
                                    }
                                    $('.noti-pending-places').mCustomScrollbar('scrollTo', ['bottom', 'right']);
                                    var el = document.getElementById("loading");
                                },
                                success: function (result, res, xhr) {
                                    view.blockUi.unblock();
                                    if (res.success) {
                                        if ($("#loading-circle").length > 0 && $("#loading-check").length > 0) {
                                            $("#loading-circle").attr('class', 'loading-inner');
                                            $("#loading-check").attr('class', 'loading-check loading-check-active');
                                        }
                                        setTimeout(function () {
                                            $('.loading-active').remove();
                                            $('.list-pending-places').find('.ae_query').remove();
                                            $('.list-pending-places').append(res.query_noti);
                                        }, 1000);
                                    } else {
                                        if ($("#loading-circle").length > 0 && $("#loading-check").length > 0) {
                                            $("#loading-circle").attr('class', 'loading-inner');
                                            $("#loading-check").attr('class', 'loading-check loading-check-active');
                                        }
                                        setTimeout(function () {
                                            $('.loading-active').hide();
                                        }, 1000);
                                    }
                                }
                            });
                        }
                    });
                }
            });
        }
    });
})(AE.Views, AE.Models, jQuery, Backbone, AE.Collections);